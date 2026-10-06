<?php

declare(strict_types=1);

namespace OCA\gov_file_drop\Listener;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Files\Events\Node\BeforeNodeCreatedEvent;
use OCP\Files\InvalidPathException;
use Psr\Log\LoggerInterface;

class FileSecurityListener implements IEventListener {

	public const ALLOWED_EXTENSIONS = [
		'doc', 'docx', 'xls', 'xlsx', 'txt', 'pdf', 'rar', 'zip'
	];

	public const DANGEROUS_EXTENSIONS = [
		'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phar', 'phps', 'inc',
		'exe', 'bat', 'cmd', 'sh', 'bash', 'bin', 'vbs', 'vbe', 'js', 'jse', 'wsf', 'wsh',
		'jar', 'msi', 'com', 'scr', 'dll', 'sys', 'iso', 'img',
		'jsp', 'jspx', 'asp', 'aspx', 'cer', 'asa', 'asax',
		'py', 'pl', 'cgi', 'htaccess', 'htpasswd', 'config', 'svg', 'html', 'htm', 'xhtml'
	];

	public function __construct(
		private ?LoggerInterface $logger = null
	) {
	}

	public function handle(Event $event): void {
		if (!($event instanceof BeforeNodeCreatedEvent)) {
			return;
		}

		$node = $event->getNode();
		$filename = $node ? $node->getName() : '';
		if ($filename === '') {
			return;
		}

		if (str_contains($filename, "\0") || str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
			$this->logAlert('Path traversal or null byte detected in filename: ' . $filename);
			throw new InvalidPathException('Cảnh báo an ninh: Tên tệp tin chứa ký tự không hợp lệ.');
		}

		$parts = explode('.', $filename);
		if (count($parts) < 2) {
			$this->logAlert('File rejected - no extension: ' . $filename);
			throw new InvalidPathException('Cảnh báo ATTT: Tệp tin không có phần mở rộng hợp lệ.');
		}

		$ext = strtolower(trim(end($parts)));

		if (!in_array($ext, self::ALLOWED_EXTENSIONS, true)) {
			$this->logAlert('File extension rejected by policy: ' . $filename . ' (ext: ' . $ext . ')');
			throw new InvalidPathException('Cảnh báo ATTT: Định dạng tệp tin ".' . $ext . '" không được phép tải lên. Hệ thống chỉ chấp nhận: .doc, .docx, .xls, .xlsx, .txt, .pdf, .rar, .zip');
		}

		$count = count($parts);
		for ($i = 0; $i < $count - 1; $i++) {
			$subExt = strtolower(trim($parts[$i]));
			if (in_array($subExt, self::DANGEROUS_EXTENSIONS, true)) {
				$this->logAlert('Dangerous double extension detected: ' . $filename . ' (sub-ext: ' . $subExt . ')');
				throw new InvalidPathException('Cảnh báo AN NINH MẠNG: Phát hiện tệp tin chứa dấu hiệu nguy hiểm (Double Extension: .' . $subExt . ').');
			}
		}
	}

	private function logAlert(string $message): void {
		if ($this->logger !== null) {
			$this->logger->warning('[gov_file_drop] ' . $message);
		}
	}
}
