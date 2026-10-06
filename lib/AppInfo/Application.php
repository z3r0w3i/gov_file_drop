<?php

declare(strict_types=1);

namespace OCA\gov_file_drop\AppInfo;

use OCA\gov_file_drop\Listener\FileSecurityListener;
use OCA\gov_file_drop\Provider\GovPublicShareTemplateProvider;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Files\Events\Node\BeforeNodeCreatedEvent;
use OCP\Share\IPublicShareTemplateProvider;

class Application extends App implements IBootstrap {
	public const APP_ID = 'gov_file_drop';

	public function __construct(array $urlParams = []) {
		parent::__construct(self::APP_ID, $urlParams);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerService(
			GovPublicShareTemplateProvider::class,
			function ($c) {
				return new GovPublicShareTemplateProvider(
					$c->get(\OCP\IURLGenerator::class),
					$c->get(\OCP\IL10N::class),
					$c->get(\OCP\IConfig::class),
					$c->get(\OCP\IRequest::class),
					$c->get(\OCP\Accounts\IAccountManager::class),
					$c->get(\OCP\IUserManager::class),
					$c->get(\OCP\IPreview::class),
					$c->get(\OCP\EventDispatcher\IEventDispatcher::class)
				);
			}
		);

		$context->registerPublicShareTemplateProvider(GovPublicShareTemplateProvider::class);

		$context->registerEventListener(BeforeNodeCreatedEvent::class, FileSecurityListener::class);
	}

	public function boot(IBootContext $context): void {
	}
}
