<?php

declare(strict_types=1);

namespace OCA\gov_file_drop\Provider;

use OCA\gov_file_drop\AppInfo\Application;
use OCP\Accounts\IAccountManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\Constants;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\Files\FileInfo;
use OCP\Files\Folder;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IPreview;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\IUser;
use OCP\IUserManager;
use OCP\Share\IPublicShareTemplateProvider;
use OCP\Share\IPublicShareTemplateProviderWithPriority;
use OCP\Share\IShare;
use OCP\Util;

class GovPublicShareTemplateProvider implements IPublicShareTemplateProvider, IPublicShareTemplateProviderWithPriority {

	public function __construct(
		private IURLGenerator $urlGenerator,
		private IL10N $l10n,
		private IConfig $config,
		private IRequest $request,
		private IAccountManager $accountManager,
		private IUserManager $userManager,
		private IPreview $previewManager,
		private IEventDispatcher $eventDispatcher,
	) {
	}

	public function getPriority(): int {
		return 100;
	}

	public function shouldRespond(IShare $share): bool {
		$node = $share->getNode();
		if ($node instanceof Folder && ($share->getPermissions() & Constants::PERMISSION_CREATE)) {
			return true;
		}

		return false;
	}

	public function renderPage(IShare $share, string $token, string $path): TemplateResponse {
		$shareNode = $share->getNode();

		$tmpl = [];
		$tmpl['owner'] = '';
		$tmpl['shareOwner'] = '';

		$owner = $this->userManager->get($share->getShareOwner());
		if ($owner instanceof IUser) {
			$ownerAccount = $this->accountManager->getAccount($owner);
			$ownerName = $ownerAccount->getProperty(IAccountManager::PROPERTY_DISPLAYNAME);
			if ($ownerName && $ownerName->getScope() === IAccountManager::SCOPE_PUBLISHED) {
				$tmpl['owner'] = $owner->getUID();
				$tmpl['shareOwner'] = $owner->getDisplayName();
			}
		}

		$tmpl['filename'] = $shareNode ? $shareNode->getName() : 'Hồ sơ';
		$tmpl['note'] = $share->getNote();
		$tmpl['sharingToken'] = $token;
		$tmpl['mimetype'] = $shareNode ? $shareNode->getMimetype() : 'httpd/unix-directory';
		$tmpl['previewSupported'] = false;
		$tmpl['hideDownload'] = true;
		$tmpl['hideFileList'] = true;
		$tmpl['disclaimer'] = '';

		$freeSpace = FileInfo::SPACE_UNLIMITED;
		try {
			if ($shareNode) {
				$freeSpace = $shareNode->getStorage()->free_space($shareNode->getInternalPath());
			}
		} catch (\Throwable $e) {}

		$maxUploadFilesize = ($freeSpace > 0 && $freeSpace < FileInfo::SPACE_UNLIMITED) ? $freeSpace : 10737418240;
		$tmpl['uploadMaxFilesize'] = $maxUploadFilesize;
		$tmpl['nonHumanFileSize'] = 0;
		$tmpl['fileSize'] = Util::humanFileSize(0);

		$logoUrl = $this->urlGenerator->getAbsoluteURL($this->urlGenerator->imagePath('core', 'logo/logo.svg'));
		$tmpl['logoUrl'] = $logoUrl;
		$tmpl['govLogoUrl'] = $this->urlGenerator->imagePath(Application::APP_ID, 'gov-logo.png');
		$tmpl['agencyLogoUrl'] = $this->urlGenerator->imagePath(Application::APP_ID, 'images.png');

		Util::addStyle(Application::APP_ID, 'gov_public');
		Util::addScript(Application::APP_ID, 'gov_files_drop');

		return new TemplateResponse(Application::APP_ID, 'public', $tmpl, 'guest');
	}
}
