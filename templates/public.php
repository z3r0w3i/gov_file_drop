<?php
$nonce = '';
try {
	if (class_exists('\\OC\\Security\\CSP\\ContentSecurityPolicyNonceManager')) {
		$nonceManager = \OC::$server->get(\OC\Security\CSP\ContentSecurityPolicyNonceManager::class);
		$nonce = $nonceManager->getNonce();
	} elseif (method_exists('\\OCP\\Util', 'getNonce')) {
		$nonce = \OCP\Util::getNonce();
	}
} catch (\Throwable $e) {
	$nonce = '';
}
$nonceAttr = $nonce ? ' nonce="' . htmlspecialchars($nonce, ENT_QUOTES) . '"' : '';

$govLogoUrl = $_['govLogoUrl'] ?? '';
$agencyLogoUrl = $_['agencyLogoUrl'] ?? '';

if (!$govLogoUrl || !$agencyLogoUrl) {
	try {
		$urlGen = class_exists('\\OCP\\Server') ? \OCP\Server::get(\OCP\IURLGenerator::class) : \OC::$server->get(\OCP\IURLGenerator::class);
		if ($urlGen) {
			if (!$govLogoUrl) {
				try {
					$govLogoUrl = $urlGen->imagePath('gov_file_drop', 'gov-logo.png');
				} catch (\Throwable $e) {
					$govLogoUrl = $urlGen->imagePath('files_sharing', 'gov-logo.png');
				}
			}
			if (!$agencyLogoUrl) {
				try {
					$agencyLogoUrl = $urlGen->imagePath('gov_file_drop', 'images.png');
				} catch (\Throwable $e) {
					$agencyLogoUrl = $urlGen->imagePath('files_sharing', 'images.png');
				}
			}
		}
	} catch (\Throwable $e) {
	}
}

if (!$agencyLogoUrl) {
	$agencyLogoUrl = '/apps/files_sharing/img/images.png';
}
if (!$govLogoUrl) {
	$govLogoUrl = '/apps/files_sharing/img/gov-logo.png';
}
$emblemLogo = $agencyLogoUrl ?: $govLogoUrl;
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
<style>
	#header, 
	#nextcloud-header, 
	.header-left, 
	.header-right,
	.header-org-logo,
	#mobile-app-banner,
	.mobile-app-banner,
	.smartbanner,
	#beta-app-banner,
	.app-banner,
	#app-banner,
	.nextcloud-banner,
	.nextcloud-app-banner,
	#app-navigation,
	#app-navigation-vue,
	.app-navigation,
	.app-navigation-menu,
	#app-sidebar,
	#app-sidebar-vue,
	.vue-portal-target,
	#controls,
	.breadcrumb,
	#filestable,
	#notification-container,
	.toast,
	#unified-search,
	#settings,
	#expand,
	.header-shared-by,
	footer:not(.gov-public-footer),
	#footer,
	.guest-box {
		display: none !important;
		height: 0 !important;
		min-height: 0 !important;
		max-height: 0 !important;
		padding: 0 !important;
		margin: 0 !important;
		visibility: hidden !important;
		opacity: 0 !important;
		pointer-events: none !important;
		overflow: hidden !important;
	}

	html {
		--header-height: 0px !important;
		padding: 0 !important;
		margin: 0 !important;
		min-height: 100% !important;
		height: auto !important;
		background-color: #f4f6f8 !important;
		background-image: none !important;
		display: block !important;
		overflow-x: hidden !important;
		overflow-y: auto !important;
		-webkit-overflow-scrolling: touch !important;
		width: 100% !important;
		max-width: 100% !important;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
		-webkit-text-size-adjust: 100% !important;
	}

	body, #body-user, #body-public, #body-login {
		--header-height: 0px !important;
		padding: 0 !important;
		margin: 0 !important;
		min-height: 100vh !important;
		height: auto !important;
		background-color: #f4f6f8 !important;
		background-image: none !important;
		display: block !important;
		overflow: visible !important;
		width: 100% !important;
		max-width: 100vw !important;
		font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
		-webkit-text-size-adjust: 100% !important;
	}

	.wrapper,
	#wrapper,
	div.wrapper,
	#content,
	#content-vue,
	.content,
	#maincontent,
	#app-content,
	#app-content-wrapper,
	#public-upload,
	#public-upload .emptycontent,
	.emptycontent {
		margin: 0 !important;
		padding: 0 !important;
		left: 0 !important;
		right: 0 !important;
		width: 100% !important;
		max-width: 100vw !important;
		min-width: 0 !important;
		height: auto !important;
		min-height: 0 !important;
		border-radius: 0 !important;
		box-shadow: none !important;
		border: none !important;
		display: block !important;
		box-sizing: border-box !important;
		align-self: stretch !important;
		position: static !important;
		overflow: visible !important;
	}

	html.is-file-preview,
	body.is-file-preview {
		height: 100vh !important;
		min-height: 100vh !important;
		max-height: 100vh !important;
		overflow: hidden !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	body.is-file-preview .gov-public-header {
		display: none !important;
	}

	body.is-file-preview #content,
	body.is-file-preview #content-vue,
	body.is-file-preview #app-content,
	body.is-file-preview #app-content-wrapper {
		height: 100vh !important;
		min-height: 100vh !important;
		max-height: 100vh !important;
		overflow: hidden !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	body.is-file-preview .gov-public-shell {
		display: flex !important;
		flex-direction: column !important;
		height: 100vh !important;
		min-height: 100vh !important;
		max-height: 100vh !important;
		overflow: hidden !important;
		margin: 0 !important;
		padding: 0 !important;
		border-radius: 0 !important;
		box-shadow: none !important;
	}

	body.is-file-preview .gov-public-main {
		flex: 1 1 0% !important;
		min-height: 0 !important;
		height: auto !important;
		max-width: none !important;
		width: 100% !important;
		padding: 0 !important;
		margin: 0 !important;
		display: flex !important;
		flex-direction: column !important;
	}

	body.is-file-preview #files-public-content,
	body.is-file-preview #preview,
	body.is-file-preview #imgframe {
		flex: 1 1 0% !important;
		min-height: 0 !important;
		height: 100% !important;
		width: 100% !important;
		max-width: none !important;
		display: flex !important;
		flex-direction: column !important;
		margin: 0 !important;
		padding: 0 !important;
	}

	body.is-file-preview #imgframe #viewer,
	body.is-file-preview #imgframe .viewer__file,
	body.is-file-preview #imgframe iframe {
		width: 100% !important;
		height: 100% !important;
		min-height: 0 !important;
		flex: 1 1 0% !important;
		margin: 0 auto !important;
		border: none !important;
	}

	body.is-file-preview #imgframe .publicpreview,
	body.is-file-preview #imgframe > a,
	body.is-file-preview #imgframe > img,
	body.is-file-preview #imgframe img:not(.viewer__file) {
		display: none !important;
		height: 0 !important;
		width: 0 !important;
		margin: 0 !important;
		padding: 0 !important;
		visibility: hidden !important;
		pointer-events: none !important;
	}
</style>

<script<?= $nonceAttr ?>>
	(function() {
		window.OCA = window.OCA || {};
		window.OCA.Files = window.OCA.Files || {};
		if (!window.OCA.Files.FileActions) {
			window.OCA.Files.FileActions = function() {};
			window.OCA.Files.FileActions.prototype.registerDefaultActions = function() {};
			window.OCA.Files.FileActions.prototype.merge = function() {};
			window.OCA.Files.FileActions.prototype.getDefaultFileAction = function() { return null; };
		}

		var polyfillJQuery = function(jq) {
			if (!jq || !jq.fn) return;
			if (!jq.fn.avatar) {
				jq.fn.avatar = function() { return this; };
			}
			if (!jq.fn.fileupload) {
				jq.fn.fileupload = function() { return this; };
			}
		};

		polyfillJQuery(window.jQuery);
		polyfillJQuery(window.$);

		var _origJQuery = window.jQuery;
		try {
			Object.defineProperty(window, 'jQuery', {
				configurable: true,
				enumerable: true,
				get: function() { return _origJQuery; },
				set: function(val) {
					_origJQuery = val;
					polyfillJQuery(_origJQuery);
				}
			});
		} catch (e) {}

		var _origDollar = window.$;
		try {
			Object.defineProperty(window, '$', {
				configurable: true,
				enumerable: true,
				get: function() { return _origDollar; },
				set: function(val) {
					_origDollar = val;
					polyfillJQuery(_origDollar);
				}
			});
		} catch (e) {}

		var killNextcloudBanners = function() {
			var bannerSelectors = [
				'#mobile-app-banner', '.mobile-app-banner',
				'.smartbanner', '#beta-app-banner',
				'.app-banner', '#app-banner',
				'.nextcloud-banner', '.nextcloud-app-banner'
			];
			bannerSelectors.forEach(function(s) {
				document.querySelectorAll(s).forEach(function(b) {
					b.style.display = 'none';
					b.remove();
				});
			});
			var meta = document.querySelector('meta[name="apple-itunes-app"]');
			if (meta) { meta.remove(); }
			document.cookie = "hideAppBanner=1; path=/; max-age=31536000";
			document.cookie = "oc_app_banner_hidden=1; path=/; max-age=31536000";
		};

		var checkPreview = function() {
			var imgframe = document.getElementById('imgframe');
			if (imgframe) {
				document.documentElement.classList.add('is-file-preview');
				document.body.classList.add('is-file-preview');
			} else {
				document.documentElement.classList.remove('is-file-preview');
				document.body.classList.remove('is-file-preview');
			}
		};

		var ensurePageScrollable = function() {
			if (document.documentElement) {
				document.documentElement.style.setProperty('overflow-x', 'hidden', 'important');
				document.documentElement.style.setProperty('overflow-y', 'auto', 'important');
				document.documentElement.style.setProperty('-webkit-overflow-scrolling', 'touch', 'important');
				document.documentElement.style.setProperty('height', 'auto', 'important');
				document.documentElement.style.setProperty('min-height', '100%', 'important');
			}
			if (document.body) {
				document.body.style.setProperty('overflow', 'visible', 'important');
				document.body.style.setProperty('overflow-x', 'visible', 'important');
				document.body.style.setProperty('overflow-y', 'visible', 'important');
				document.body.style.setProperty('height', 'auto', 'important');
				document.body.style.setProperty('min-height', '100vh', 'important');
			}
			var shell = document.querySelector('.gov-public-shell');
			if (shell) {
				shell.style.setProperty('height', 'auto', 'important');
				shell.style.setProperty('min-height', '100vh', 'important');
				shell.style.setProperty('overflow', 'visible', 'important');
				shell.style.setProperty('overflow-x', 'visible', 'important');
				shell.style.setProperty('overflow-y', 'visible', 'important');
				var node = shell.parentElement;
				while (node && node !== document.body && node !== document.documentElement) {
					node.style.setProperty('height', 'auto', 'important');
					node.style.setProperty('overflow', 'visible', 'important');
					node.style.setProperty('overflow-x', 'visible', 'important');
					node.style.setProperty('overflow-y', 'visible', 'important');
					node = node.parentElement;
				}
			}
		};

		var handleWheel = function(e) {
			try {
				var targetEl = (e.target && e.target.nodeType === 1) ? e.target : (e.target && e.target.parentElement ? e.target.parentElement : null);
				var innerScroll = targetEl && targetEl.closest ? targetEl.closest('#terms-text-box, #drop-pending-files, pre, textarea') : null;
				if (innerScroll) {
					var canScrollUp = innerScroll.scrollTop > 0 && e.deltaY < 0;
					var canScrollDown = (innerScroll.scrollTop + innerScroll.clientHeight < innerScroll.scrollHeight - 1) && e.deltaY > 0;
					if (canScrollUp || canScrollDown) {
						return;
					}
				}
			} catch (err) {}

			var scroller = document.scrollingElement || document.documentElement || document.body;
			var dy = e.deltaY;
			if (e.deltaMode === 1) {
				dy *= 24;
			} else if (e.deltaMode === 2) {
				dy *= window.innerHeight || 800;
			}

			if (Math.abs(dy) > 0) {
				var before = scroller ? scroller.scrollTop : window.pageYOffset;
				requestAnimationFrame(function() {
					var after = scroller ? scroller.scrollTop : window.pageYOffset;
					if (after === before) {
						window.scrollBy(0, dy);
						if (scroller && scroller.scrollTop === before) {
							scroller.scrollTop += dy;
						}
					}
				});
			}
		};
		window.addEventListener('wheel', handleWheel, { passive: true });

		var runAll = function() {
			killNextcloudBanners();
			checkPreview();
			ensurePageScrollable();
			polyfillJQuery(window.jQuery);
			polyfillJQuery(window.$);
		};

		runAll();
		document.addEventListener('DOMContentLoaded', runAll);
		window.addEventListener('load', runAll);

		var initCount = 0;
		var initInterval = setInterval(function() {
			runAll();
			initCount++;
			if (initCount >= 10) {
				clearInterval(initInterval);
			}
		}, 300);

		if (window.MutationObserver && document.body) {
			var observer = new MutationObserver(function(mutations) {
				for (var i = 0; i < mutations.length; i++) {
					if (mutations[i].addedNodes && mutations[i].addedNodes.length > 0) {
						killNextcloudBanners();
						break;
					}
				}
			});
			observer.observe(document.body, { childList: true, subtree: true });
		}
	})();
</script>

<div id="app-content" tabindex="0">

<input type="hidden" id="sharingUserId" value="">
<input type="hidden" id="filesApp" name="filesApp" value="0">
<input type="hidden" id="isPublic" name="isPublic" value="1">
<input type="hidden" name="downloadURL" value="<?php p(!empty($_['downloadURL']) ? $_['downloadURL'] : ''); ?>" id="downloadURL">
<input type="hidden" name="previewURL" value="<?php p(!empty($_['previewURL']) ? $_['previewURL'] : ''); ?>" id="previewURL">
<input type="hidden" name="sharingToken" value="<?php p($_['sharingToken']); ?>" id="sharingToken">
<input type="hidden" name="filename" value="<?php p($_['filename']); ?>" id="filename">
<input type="hidden" name="mimetype" value="<?php p(!empty($_['mimetype']) ? $_['mimetype'] : 'httpd/unix-directory'); ?>" id="mimetype">
<input type="hidden" name="previewSupported" value="<?php p(!empty($_['previewSupported']) ? 'true' : 'false'); ?>" id="previewSupported">
<input type="hidden" name="mimetypeIcon" value="<?php p(!empty($_['mimetypeIcon']) ? $_['mimetypeIcon'] : ''); ?>" id="mimetypeIcon">
<input type="hidden" name="hideDownload" value="<?php p(!empty($_['hideDownload']) ? 'true' : 'false'); ?>" id="hideDownload">
<input type="hidden" id="disclaimerText" value="<?php p(!empty($_['disclaimer']) ? $_['disclaimer'] : (!empty($_['note']) ? $_['note'] : '')); ?>">
<input type="hidden" name="maxFilesizeUpload" value="<?php p(!empty($_['uploadMaxFilesize']) ? $_['uploadMaxFilesize'] : (!empty($_['maxSizeUpload']) ? $_['maxSizeUpload'] : 10737418240)); ?>" id="maxFilesizeUpload">
<input type="hidden" name="maxSizeAnimateGif" value="10" id="maxSizeAnimateGif">

<div class="gov-public-shell">
	<header class="gov-public-header">
		<div class="gov-public-header__inner">
			<div class="gov-public-emblem" aria-hidden="true">
				<img src="<?php p($emblemLogo); ?>" alt="Logo" class="gov-emblem-img">
			</div>
			<div class="gov-public-title">
				<div class="gov-public-kicker">
					<svg width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
					<span>HỆ THỐNG THU NHẬN &amp; TIẾP NHẬN TÀI LIỆU SỐ HÓA</span>
				</div>
				<h1>Tiếp nhận tài liệu điện tử</h1>
				<p>Cổng tiếp nhận tài liệu số bảo mật phục vụ giải quyết hồ sơ</p>
			</div>
			<div class="gov-header-security-wrap">
				<div class="gov-security-badge" title="Hệ thống bảo vệ dữ liệu và mã hóa an toàn kênh truyền">
					<svg class="gov-sec-icon" viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
						<path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
					</svg>
					<span>HỆ THỐNG AN TOÀN &amp; BẢO MẬT DỮ LIỆU</span>
				</div>
			</div>
		</div>
	</header>
	<main class="gov-public-main">

	<input type="hidden" id="upload-only-interface" value="1"/>
	<div id="public-upload">
		<div id="emptycontent" class="emptycontent gov-emptycontent ">
			
			<div class="gov-upload-stepper-wrap">
				<div class="gov-stepper" id="gov-upload-stepper">
					<div class="gov-step gov-step--active" id="gov-step-1">
						<div class="gov-step__circle">1</div>
						<div class="gov-step__label">
							<span class="gov-step__num">Bước 1</span>
							<span class="gov-step__text">Chọn tệp tin</span>
						</div>
					</div>
					<div class="gov-step__line" id="gov-stepper-line-1"></div>
					<div class="gov-step" id="gov-step-2">
						<div class="gov-step__circle">2</div>
						<div class="gov-step__label">
							<span class="gov-step__num">Bước 2</span>
							<span class="gov-step__text">Kiểm tra &amp; Cam kết</span>
						</div>
					</div>
					<div class="gov-step__line" id="gov-stepper-line-2"></div>
					<div class="gov-step" id="gov-step-3">
						<div class="gov-step__circle">3</div>
						<div class="gov-step__label">
							<span class="gov-step__num">Bước 3</span>
							<span class="gov-step__text">Hoàn tất gửi</span>
						</div>
					</div>
				</div>
			</div>

			<div class="gov-dropzone-hero" id="gov-dropzone-hero">
				<div class="gov-hero-shield" aria-hidden="true">
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
						<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
						<polyline points="9 12 11 14 15 10"/>
					</svg>
				</div>
				<h2>Tiếp nhận hồ sơ: <?php p($_['filename']); ?></h2>
				
				<?php if (!empty($_['note'])): ?>
				<div class="gov-note-card">
					<div class="gov-note-title">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
						<span>Ghi chú từ cán bộ tiếp nhận:</span>
					</div>
					<p class="note"><?php p($_['note']); ?></p>
				</div>
				<?php endif; ?>
				
				<div class="gov-upload-action-box">
					<input type="file" id="public-upload-input" name="files[]" multiple accept=".doc,.docx,.xls,.xlsx,.txt,.pdf,.rar,.zip" style="position: absolute; left: -9999px; opacity: 0; width: 1px; height: 1px; pointer-events: none;">
					<label for="public-upload-input" id="public-upload-btn" class="button icon-upload" role="button" tabindex="0" style="cursor: pointer;">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 10px;">
							<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
							<polyline points="17 8 12 3 7 8"></polyline>
							<line x1="12" y1="3" x2="12" y2="15"></line>
						</svg>
						<span>Chọn hoặc kéo thả tệp tin đính kèm</span>
					</label>
					<p class="gov-upload-hint">Chỉ chấp nhận các tệp định dạng: .doc, .docx, .xls, .xlsx, .txt, .pdf, .rar, .zip</p>
				</div>
			</div>

			<div id="upload-confirm-section" class="hidden">
				<div id="upload-confirm-header">
					<div class="confirm-header-left">
						<div class="confirm-header-icon" aria-hidden="true">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
								<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
								<polyline points="14 2 14 8 20 8"></polyline>
								<polyline points="9 15 11 17 15 13"></polyline>
							</svg>
						</div>
						<div class="confirm-header-text">
							<h3>Kiểm tra tệp tin trước khi gửi</h3>
							<p class="confirm-header-subtitle">Vui lòng rà soát danh sách tài liệu và xác nhận cam kết pháp lý</p>
						</div>
					</div>
					<div class="confirm-header-right">
						<span id="pending-file-count" class="gov-pill-badge">Đã chọn 0 tệp tin</span>
					</div>
				</div>

				<div class="pending-files-container">
					<ul id="drop-pending-files"></ul>
				</div>

				<div id="upload-confirm-add-more">
					<a href="#" id="add-more-files-btn" class="button">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="margin-right: 8px;">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="8" x2="12" y2="16"></line>
							<line x1="8" y1="12" x2="16" y2="12"></line>
						</svg>
						Thêm tệp tin khác
					</a>
				</div>

				<div id="terms-agreement">
					<div id="terms-agreement-content">
						<div class="terms-title-wrap">
							<div class="terms-shield-icon" aria-hidden="true">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
									<path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
								</svg>
							</div>
							<div>
								<h4>ĐIỀU KHOẢN BẢO VỆ DỮ LIỆU CÁ NHÂN &amp; CAM KẾT TRÁCH NHIỆM</h4>
								<p class="terms-meta-ref">Quy định về bảo mật, an toàn dữ liệu và trách nhiệm của người gửi tệp tin</p>
							</div>
						</div>

						<div id="terms-text-box">
							<div class="gov-doc-heading">
								<span class="gov-doc-country">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</span>
								<div class="gov-doc-motto-wrap">
									<span class="gov-doc-motto">Độc lập - Tự do - Hạnh phúc</span>
								</div>
							</div>
							<div class="terms-legal-box">
								<p class="terms-legal-ref">
									<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="margin-right:6px;vertical-align:-2px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
									<strong>CĂN CỨ PHÁP LÝ:</strong>
								</p>
								<ul class="terms-legal-list">
									<li><span>Luật An ninh mạng số 116/2025/QH15 ngày 10/12/2025;</span></li>
									<li><span>Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15 ngày 26/6/2025;</span></li>
									<li><span>Nghị định số 356/2025/NĐ-CP ngày 31/12/2025 quy định chi tiết thi hành Luật Bảo vệ dữ liệu cá nhân;</span></li>
									<li><span>Nghị định số 13/2023/NĐ-CP ngày 17/04/2023 của Chính phủ về bảo vệ dữ liệu cá nhân.</span></li>
								</ul>
							</div>

							<p class="terms-intro">Bằng việc tải tệp tin lên hệ thống tiếp nhận này, Người sử dụng xác nhận đã đọc, hiểu rõ và cam kết tuân thủ các điều khoản sau:</p>

							<div class="terms-article">
								<p class="terms-article-title"><span class="article-badge">Điều 1</span> <strong>Cam kết về tính hợp pháp và quyền sở hữu nội dung</strong></p>
								<p>Người dùng cam kết các tệp tin tải lên không chứa dữ liệu cá nhân của bên thứ ba khi chưa có sự đồng ý của chủ thể dữ liệu theo quy định tại Điều 9, Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15; không chứa mã độc, nội dung vi phạm pháp luật hoặc xâm phạm an ninh quốc gia.</p>
							</div>

							<div class="terms-article">
								<p class="terms-article-title"><span class="article-badge">Điều 2</span> <strong>Lưu trữ và thẩm quyền truy cập dữ liệu</strong></p>
								<p>Các tệp tin sau khi tải lên sẽ được lưu trữ trên hệ thống máy chủ cơ quan, bảo vệ bởi giải pháp mã hóa dữ liệu và có thể được truy cập bởi cán bộ có thẩm quyền tiếp nhận theo đúng mục đích công vụ đã xác lập.</p>
							</div>

							<div class="terms-article">
								<p class="terms-article-title"><span class="article-badge">Điều 3</span> <strong>Nguyên tắc xử lý dữ liệu cá nhân</strong></p>
								<p>Bên kiểm soát dữ liệu cá nhân được phép xử lý dữ liệu đã tải lên phù hợp với mục đích đã thông báo, tuân thủ các nguyên tắc quy định tại Điều 3, Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15; không chia sẻ cho bên thứ ba trái pháp luật.</p>
							</div>

							<div class="terms-article">
								<p class="terms-article-title"><span class="article-badge">Điều 4</span> <strong>Quyền của chủ thể dữ liệu</strong></p>
								<p>Theo Điều 4, Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15, Người dùng có các quyền được biết, đồng ý, truy cập, chỉnh sửa, xóa dữ liệu cá nhân đã tải lên bằng cách liên hệ trực tiếp với cơ quan quản lý hệ thống.</p>
							</div>

							<div class="terms-article">
								<p class="terms-article-title"><span class="article-badge">Điều 5</span> <strong>Biện pháp bảo vệ và trách nhiệm pháp lý</strong></p>
								<p>Hệ thống áp dụng đầy đủ các biện pháp quản lý và kỹ thuật phù hợp để bảo vệ dữ liệu cá nhân, ngăn chặn truy cập trái phép theo quy định tại Điều 23, Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15. Người dùng tự chịu trách nhiệm pháp lý về tính chính xác và nguồn gốc tệp tin tải lên.</p>
							</div>
						</div>
					</div>
					<label id="terms-checkbox-label" for="accept-terms-checkbox-final">
						<div class="gov-checkbox-wrap">
							<input type="checkbox" id="accept-terms-checkbox-final" name="accept-terms-checkbox-final" autocomplete="off">
							<span class="gov-checkbox-custom" aria-hidden="true">
								<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3.5">
									<polyline points="20 6 9 17 4 12"></polyline>
								</svg>
							</span>
						</div>
						<span class="gov-checkbox-text">Tôi đã đọc, hiểu rõ, đồng ý và cam kết tuân thủ các điều khoản bảo vệ dữ liệu cá nhân nêu trên</span>
					</label>
				</div>

				<div class="gov-confirm-hint-wrap">
					<p id="gov-confirm-hint" class="gov-confirm-hint">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
						<span>Vui lòng tích chọn đồng ý cam kết điều khoản bảo vệ dữ liệu để mở khóa nộp hồ sơ</span>
					</p>
				</div>

				<div id="upload-confirm-actions">
					<button id="cancel-upload-btn" class="button" type="button">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:15px;height:15px;min-width:15px;max-width:15px;flex:0 0 15px;vertical-align:middle;margin-right:6px;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
						<span>Hủy bỏ</span>
					</button>
					<button id="confirm-upload-btn" class="button primary gov-btn--disabled" disabled type="button">
						<svg class="btn-icon-lock" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;min-width:18px;max-width:18px;flex:0 0 18px;vertical-align:middle;margin-right:8px;">
							<path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
						</svg>
						<svg class="btn-icon-upload" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px;min-width:18px;max-width:18px;flex:0 0 18px;vertical-align:middle;margin-right:8px;display:none;">
							<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
							<polyline points="17 8 12 3 7 8"></polyline>
							<line x1="12" y1="3" x2="12" y2="15"></line>
						</svg>
						<span class="btn-text">Xác nhận và nộp hồ sơ</span>
					</button>
				</div>
			</div>

			<div id="drop-upload-progress-indicator" class="hidden">
				<div class="gov-upload-spinner">
					<svg class="spinner-svg" width="28" height="28" viewBox="0 0 50 50">
						<circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
					</svg>
				</div>
				<div class="gov-progress-text">
					<h4>Đang thực hiện tải lên và tiếp nhận hồ sơ...</h4>
					<p>Vui lòng giữ nguyên cửa sổ trình duyệt cho đến khi hoàn tất</p>
				</div>
			</div>
			<div id="drop-upload-done-indicator" class="hidden">
				<div class="gov-done-badge">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="3">
						<polyline points="20 6 9 17 4 12"></polyline>
					</svg>
				</div>
				<h4>Tài liệu đã được tiếp nhận thành công vào hệ thống</h4>
				<p>Hồ sơ đã được mã hóa an toàn và sẵn sàng cho công tác thẩm tra, xử lý</p>
			</div>
			<ul id="drop-uploaded-files"></ul>
			<div id="gov-done-actions-wrap" class="hidden">
				<button type="button" id="gov-done-upload-more-btn" class="button">
					<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
					<span>Nộp thêm tài liệu khác</span>
				</button>
			</div>
		</div>
	</div>

	<script<?= $nonceAttr ?>>
		(function() {
			window._govClonedScriptActive = true;
			window.OCA = window.OCA || {};
			try {
				Object.defineProperty(window.OCA, 'FilesSharingDrop', {
					configurable: true,
					writable: true,
					value: {
						initialize: function() {},
						addFileToUpload: function() {},
						_updatePendingUI: function() {},
						_updateConfirmButtonState: function() {}
					}
				});
			} catch (e) {}

			if (window.$ && typeof $(document).off === 'function') {
				$(document).off('change', '#accept-terms-checkbox-final');
				$(document).off('click', '#confirm-upload-btn');
			}

			var pendingFiles = [];
			var uploading = false;

			var formatBytes = function(bytes) {
				if (!bytes || bytes <= 0) return '0 B';
				var k = 1024;
				var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
				var i = Math.floor(Math.log(bytes) / Math.log(k));
				if (i >= sizes.length) i = sizes.length - 1;
				return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
			};

			var escapeHtml = function(str) {
				return String(str || '')
					.replace(/&/g, '&amp;')
					.replace(/</g, '&lt;')
					.replace(/>/g, '&gt;')
					.replace(/"/g, '&quot;')
					.replace(/'/g, '&#39;');
			};

			var isTermsChecked = function() {
				var cb = document.getElementById('accept-terms-checkbox-final');
				return cb ? Boolean(cb.checked) : false;
			};

			var getFileInfo = function(filename) {
				var ext = '';
				var dotIdx = (filename || '').lastIndexOf('.');
				if (dotIdx !== -1) {
					ext = filename.substring(dotIdx + 1).toLowerCase();
				}
				var badgeClass = 'badge-generic';
				var badgeLabel = ext ? ext.toUpperCase() : 'TỆP';
				if (badgeLabel.length > 5) { badgeLabel = badgeLabel.substring(0, 4); }

				if (ext === 'doc') {
					badgeClass = 'badge-docx';
					badgeLabel = 'DOC';
				} else if (ext === 'docx') {
					badgeClass = 'badge-docx';
					badgeLabel = 'DOCX';
				} else if (ext === 'pdf') {
					badgeClass = 'badge-pdf';
					badgeLabel = 'PDF';
				} else if (ext === 'xls') {
					badgeClass = 'badge-xlsx';
					badgeLabel = 'XLS';
				} else if (ext === 'xlsx') {
					badgeClass = 'badge-xlsx';
					badgeLabel = 'XLSX';
				} else if (ext === 'rar') {
					badgeClass = 'badge-zip';
					badgeLabel = 'RAR';
				} else if (ext === 'zip') {
					badgeClass = 'badge-zip';
					badgeLabel = 'ZIP';
				} else if (ext === 'txt') {
					badgeClass = 'badge-txt';
					badgeLabel = 'TXT';
				}
				return { ext: ext, badgeClass: badgeClass, badgeLabel: badgeLabel };
			};

			var updateStepper = function(stepNum) {
				var s1 = document.getElementById('gov-step-1');
				var s2 = document.getElementById('gov-step-2');
				var s3 = document.getElementById('gov-step-3');
				var l1 = document.getElementById('gov-stepper-line-1');
				var l2 = document.getElementById('gov-stepper-line-2');
				if (!s1 || !s2 || !s3) return;

				var c1 = s1.querySelector('.gov-step__circle');
				var c2 = s2.querySelector('.gov-step__circle');
				var c3 = s3.querySelector('.gov-step__circle');

				var checkSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>';

				s1.className = 'gov-step';
				s2.className = 'gov-step';
				s3.className = 'gov-step';
				if (c1) c1.textContent = '1';
				if (c2) c2.textContent = '2';
				if (c3) c3.textContent = '3';
				if (l1) l1.className = 'gov-step__line';
				if (l2) l2.className = 'gov-step__line';

				if (stepNum === 1) {
					s1.className = 'gov-step gov-step--active';
				} else if (stepNum === 2) {
					s1.className = 'gov-step gov-step--done';
					if (c1) c1.innerHTML = checkSvg;
					if (l1) l1.className = 'gov-step__line gov-step__line--active';
					s2.className = 'gov-step gov-step--active';
				} else if (stepNum === 3) {
					s1.className = 'gov-step gov-step--done';
					if (c1) c1.innerHTML = checkSvg;
					if (l1) l1.className = 'gov-step__line gov-step__line--active';
					s2.className = 'gov-step gov-step--done';
					if (c2) c2.innerHTML = checkSvg;
					if (l2) l2.className = 'gov-step__line gov-step__line--active';
					s3.className = 'gov-step gov-step--active';
				} else if (stepNum === 4) {
					s1.className = 'gov-step gov-step--done';
					if (c1) c1.innerHTML = checkSvg;
					if (l1) l1.className = 'gov-step__line gov-step__line--active';
					s2.className = 'gov-step gov-step--done';
					if (c2) c2.innerHTML = checkSvg;
					if (l2) l2.className = 'gov-step__line gov-step__line--active';
					s3.className = 'gov-step gov-step--done';
					if (c3) c3.innerHTML = checkSvg;
				}
			};

			var updateConfirmState = function() {
				var btn = document.getElementById('confirm-upload-btn');
				var hint = document.getElementById('gov-confirm-hint');
				if (!btn) return;
				var checked = isTermsChecked();
				var canUpload = pendingFiles.length > 0 && checked && !uploading;
				var lockIcon = btn.querySelector('.btn-icon-lock');
				var uploadIcon = btn.querySelector('.btn-icon-upload');

				if (canUpload) {
					btn.disabled = false;
					btn.removeAttribute('disabled');
					btn.classList.add('gov-btn--active');
					btn.classList.remove('gov-btn--disabled');
					btn.style.setProperty('background', '#0284C7', 'important');
					btn.style.setProperty('background-color', '#0284C7', 'important');
					btn.style.setProperty('color', '#ffffff', 'important');
					btn.style.setProperty('border', 'none', 'important');
					btn.style.setProperty('box-shadow', '0 2px 8px rgba(2, 132, 199, 0.35)', 'important');
					if (lockIcon) {
						lockIcon.style.setProperty('display', 'none', 'important');
					}
					if (uploadIcon) {
						uploadIcon.style.setProperty('display', 'inline-block', 'important');
						uploadIcon.style.setProperty('width', '18px', 'important');
						uploadIcon.style.setProperty('height', '18px', 'important');
						uploadIcon.style.setProperty('min-width', '18px', 'important');
						uploadIcon.style.setProperty('max-width', '18px', 'important');
						uploadIcon.style.setProperty('min-height', '18px', 'important');
						uploadIcon.style.setProperty('max-height', '18px', 'important');
						uploadIcon.style.setProperty('vertical-align', 'middle', 'important');
						uploadIcon.style.setProperty('margin-right', '8px', 'important');
					}
					if (hint) {
						hint.className = 'gov-confirm-hint gov-confirm-hint--ready';
						hint.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Đã đồng ý cam kết. Nhấn nút bên dưới để gửi hồ sơ vào hệ thống.</span>';
					}
				} else {
					btn.disabled = true;
					btn.setAttribute('disabled', 'disabled');
					btn.classList.remove('gov-btn--active');
					btn.classList.add('gov-btn--disabled');
					btn.style.setProperty('background', '#E2E8F0', 'important');
					btn.style.setProperty('background-color', '#E2E8F0', 'important');
					btn.style.setProperty('color', '#94A3B8', 'important');
					btn.style.setProperty('border', '1px solid #CBD5E1', 'important');
					btn.style.setProperty('box-shadow', 'none', 'important');
					if (lockIcon) {
						lockIcon.style.setProperty('display', 'inline-block', 'important');
						lockIcon.style.setProperty('width', '18px', 'important');
						lockIcon.style.setProperty('height', '18px', 'important');
						lockIcon.style.setProperty('min-width', '18px', 'important');
						lockIcon.style.setProperty('max-width', '18px', 'important');
						lockIcon.style.setProperty('min-height', '18px', 'important');
						lockIcon.style.setProperty('max-height', '18px', 'important');
						lockIcon.style.setProperty('vertical-align', 'middle', 'important');
						lockIcon.style.setProperty('margin-right', '8px', 'important');
					}
					if (uploadIcon) {
						uploadIcon.style.setProperty('display', 'none', 'important');
					}
					if (hint) {
						hint.className = 'gov-confirm-hint';
						hint.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg><span>Vui lòng tích chọn đồng ý cam kết điều khoản bảo vệ dữ liệu để mở khóa nộp hồ sơ</span>';
					}
				}
			};

			var renderPendingFiles = function() {
				var list = document.getElementById('drop-pending-files');
				var countEl = document.getElementById('pending-file-count');
				var confirmSection = document.getElementById('upload-confirm-section');
				var dropzoneHero = document.getElementById('gov-dropzone-hero');

				if (!list) return;
				list.innerHTML = '';

				if (pendingFiles.length === 0) {
					if (confirmSection) confirmSection.classList.add('hidden');
					if (dropzoneHero) dropzoneHero.classList.remove('hidden');
					var cb = document.getElementById('accept-terms-checkbox-final');
					if (cb) cb.checked = false;
					updateStepper(1);
					updateConfirmState();
					return;
				}

				if (confirmSection) confirmSection.classList.remove('hidden');
				if (dropzoneHero) dropzoneHero.classList.add('hidden');
				updateStepper(2);

				if (countEl) {
					countEl.textContent = 'Đã chọn ' + pendingFiles.length + ' tệp tin';
				}

				pendingFiles.forEach(function(item, idx) {
					var info = getFileInfo(item.name);
					var escName = escapeHtml(item.name);

					var li = document.createElement('li');
					li.className = 'gov-pending-item';
					li.innerHTML = 
						'<div class="gov-item-left">' +
							'<div class="gov-file-badge ' + info.badgeClass + '">' + info.badgeLabel + '</div>' +
							'<div class="gov-file-details">' +
								'<div class="gov-file-name" title="' + escName + '">' + escName + '</div>' +
								'<div class="gov-file-meta">' +
									'<span class="gov-file-size">' +
										'<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px;vertical-align:-1px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>' +
										formatBytes(item.size) +
									'</span>' +
									'<span class="gov-meta-dot">•</span>' +
									'<span class="gov-file-status">' +
										'<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="3" style="margin-right:4px;vertical-align:-1px;"><polyline points="20 6 9 17 4 12"></polyline></svg>' +
										'Hợp lệ / Sẵn sàng gửi' +
									'</span>' +
								'</div>' +
							'</div>' +
						'</div>' +
						'<div class="gov-item-right">' +
							'<button type="button" class="gov-file-remove-btn" title="Gỡ bỏ tệp tin này">' +
								'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>' +
								'<span>Xóa</span>' +
							'</button>' +
						'</div>';

					var delBtn = li.querySelector('.gov-file-remove-btn');
					delBtn.addEventListener('click', function(e) {
						e.preventDefault();
						e.stopPropagation();
						pendingFiles.splice(idx, 1);
						renderPendingFiles();
					});

					list.appendChild(li);
				});

				updateConfirmState();
			};

			var ALLOWED_EXTS = ['doc', 'docx', 'xls', 'xlsx', 'txt', 'pdf', 'rar', 'zip'];
			var isAllowedFile = function(fileName) {
				if (!fileName || typeof fileName !== 'string') return false;
				var lastDot = fileName.lastIndexOf('.');
				if (lastDot === -1 || lastDot === fileName.length - 1) return false;
				var ext = fileName.substring(lastDot + 1).toLowerCase();
				return ALLOWED_EXTS.indexOf(ext) !== -1;
			};

			var showGovNotice = function(msg, isError) {
				if (window.OC && OC.Notification) {
					if (isError && typeof OC.Notification.show === 'function') {
						OC.Notification.show(msg, { type: 'error' });
						return;
					}
					if (typeof OC.Notification.showTemporary === 'function') {
						OC.Notification.showTemporary(msg);
						return;
					}
				}
				alert(msg);
			};

			var addFiles = function(fileList) {
				if (!fileList || !fileList.length) return;
				var rejected = [];
				for (var i = 0; i < fileList.length; i++) {
					var file = fileList[i];
					var name = (file.name || '').replace(/["'#%`]/gm, '');
					if (!name || name === '.' || name === '..' || name.indexOf('/') !== -1 || name.indexOf('\\') !== -1) {
						continue;
					}
					if (!isAllowedFile(name)) {
						rejected.push(name);
						continue;
					}
					var duplicate = false;
					for (var j = 0; j < pendingFiles.length; j++) {
						if (pendingFiles[j].name === name) {
							duplicate = true;
							break;
						}
					}
					if (!duplicate) {
						pendingFiles.push({
							file: file,
							name: name,
							size: file.size,
							type: file.type
						});
					}
				}
				if (rejected.length > 0) {
					var notice = 'Định dạng tệp không được hỗ trợ: "' + rejected.join('", "') + '". Hệ thống chỉ tiếp nhận các tệp: .doc, .docx, .xls, .xlsx, .txt, .pdf, .rar, .zip';
					showGovNotice(notice, true);
				}
				renderPendingFiles();
			};

			document.addEventListener('change', function(e) {
				if (e.target && (e.target.id === 'public-upload-input' || e.target.name === 'files[]')) {
					e.stopImmediatePropagation();
					if (e.target.files && e.target.files.length) {
						addFiles(e.target.files);
						try { e.target.value = ''; } catch (err) {}
					}
				}
			}, true);

			var onTermsInteraction = function() {
				setTimeout(updateConfirmState, 0);
				setTimeout(updateConfirmState, 30);
				setTimeout(updateConfirmState, 100);
			};

			['change', 'click', 'input'].forEach(function(evt) {
				document.addEventListener(evt, function(e) {
					if (e.target && (e.target.id === 'accept-terms-checkbox-final' || e.target.closest('#terms-checkbox-label'))) {
						onTermsInteraction();
					}
				}, true);
			});

			setInterval(updateConfirmState, 150);

			document.addEventListener('click', function(e) {
				var addMoreBtn = e.target.closest('#add-more-files-btn');
				if (addMoreBtn) {
					e.preventDefault();
					e.stopPropagation();
					var input = document.getElementById('public-upload-input');
					if (input) {
						input.click();
					}
				}
			}, true);

			document.addEventListener('click', function(e) {
				var cancelBtn = e.target.closest('#cancel-upload-btn');
				if (cancelBtn) {
					e.preventDefault();
					e.stopPropagation();
					pendingFiles = [];
					renderPendingFiles();
				}
			}, true);

			var dragCounter = 0;
			var setDragState = function(isDragging) {
				var hero = document.getElementById('gov-dropzone-hero');
				var uploadBtn = document.getElementById('public-upload-btn');
				var emptyContent = document.getElementById('emptycontent');
				var publicUpload = document.getElementById('public-upload');
				[hero, uploadBtn, emptyContent, publicUpload].forEach(function(el) {
					if (el) {
						if (isDragging) {
							el.classList.add('is-dragover');
						} else {
							el.classList.remove('is-dragover');
						}
					}
				});
			};

			window.addEventListener('dragenter', function(e) {
				e.preventDefault();
				e.stopPropagation();
				dragCounter++;
				if (e.dataTransfer && e.dataTransfer.types) {
					setDragState(true);
				}
			}, false);

			window.addEventListener('dragover', function(e) {
				e.preventDefault();
				e.stopPropagation();
				if (e.dataTransfer) {
					e.dataTransfer.dropEffect = 'copy';
				}
				setDragState(true);
			}, false);

			window.addEventListener('dragleave', function(e) {
				e.preventDefault();
				e.stopPropagation();
				dragCounter--;
				if (dragCounter <= 0) {
					dragCounter = 0;
					setDragState(false);
				}
			}, false);

			window.addEventListener('drop', function(e) {
				e.preventDefault();
				e.stopPropagation();
				dragCounter = 0;
				setDragState(false);
				if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
					addFiles(e.dataTransfer.files);
				}
			}, false);

			document.addEventListener('keydown', function(e) {
				var uploadBtn = e.target.closest('#public-upload-btn');
				if (uploadBtn && (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32)) {
					e.preventDefault();
					var input = document.getElementById('public-upload-input');
					if (input) {
						input.click();
					}
				}
			}, true);

			var startUploadQueue = function() {
				var checked = isTermsChecked();
				if (!checked || pendingFiles.length === 0 || uploading) {
					return;
				}
				uploading = true;
				updateStepper(3);
				updateConfirmState();

				var confirmSection = document.getElementById('upload-confirm-section');
				var progressIndicator = document.getElementById('drop-upload-progress-indicator');
				var doneIndicator = document.getElementById('drop-upload-done-indicator');
				var uploadedList = document.getElementById('drop-uploaded-files');
				var dropzoneHero = document.getElementById('gov-dropzone-hero');

				if (confirmSection) confirmSection.classList.add('hidden');
				if (progressIndicator) progressIndicator.classList.remove('hidden');
				if (doneIndicator) doneIndicator.classList.add('hidden');

				var filesToUpload = pendingFiles.slice();
				pendingFiles = [];
				var cb = document.getElementById('accept-terms-checkbox-final');
				if (cb) cb.checked = false;

				var tokenInput = document.getElementById('sharingToken');
				var token = tokenInput ? tokenInput.value.trim() : '';
				var rootPath = (window.OC && typeof OC.getRootPath === 'function') ? OC.getRootPath() : '';
				var requestToken = window.oc_requesttoken || '';

				var uploadNext = function(idx) {
					if (idx >= filesToUpload.length) {
						uploading = false;
						updateStepper(4);
						if (progressIndicator) {
							progressIndicator.classList.add('hidden');
							progressIndicator.style.setProperty('display', 'none', 'important');
						}
						if (doneIndicator) {
							doneIndicator.classList.remove('hidden');
							doneIndicator.style.setProperty('display', 'block', 'important');
						}
						var doneActions = document.getElementById('gov-done-actions-wrap');
						if (doneActions) {
							doneActions.classList.remove('hidden');
							doneActions.style.setProperty('display', 'block', 'important');
						}
						if (dropzoneHero) {
							dropzoneHero.classList.add('hidden');
							dropzoneHero.style.setProperty('display', 'none', 'important');
						}
						updateConfirmState();
						return;
					}

					var item = filesToUpload[idx];
					var escName = escapeHtml(item.name);
					var info = getFileInfo(item.name);

					var itemLi = document.createElement('li');
					itemLi.className = 'gov-uploaded-item';
					itemLi.innerHTML = 
						'<div class="gov-item-left">' +
							'<div class="gov-file-badge ' + info.badgeClass + '">' + info.badgeLabel + '</div>' +
							'<div class="gov-file-details">' +
								'<div class="gov-file-name" title="' + escName + '">' + escName + '</div>' +
								'<div class="gov-file-meta">' +
									'<span class="gov-file-size">' + formatBytes(item.size) + '</span>' +
									'<span class="gov-meta-dot">•</span>' +
									'<span class="upload-percent">0%</span>' +
								'</div>' +
							'</div>' +
						'</div>' +
						'<div class="gov-upload-bar-wrap">' +
							'<progress value="0" max="100"></progress>' +
						'</div>';
					if (uploadedList) {
						uploadedList.appendChild(itemLi);
					}

					var percentEl = itemLi.querySelector('.upload-percent');
					var progEl = itemLi.querySelector('progress');

					var uploadUrl = window.location.origin + rootPath + '/public.php/webdav/' + encodeURIComponent(item.name);
					var xhr = new XMLHttpRequest();
					xhr.open('PUT', uploadUrl, true);

					if (token) {
						xhr.setRequestHeader('Authorization', 'Basic ' + btoa(token + ':'));
					}
					if (requestToken) {
						xhr.setRequestHeader('requesttoken', requestToken);
					}
					xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
					xhr.setRequestHeader('X-NC-Nickname', 'NguoiNop');
					xhr.setRequestHeader('nickname', 'NguoiNop');

					xhr.upload.onprogress = function(e) {
						if (e.lengthComputable) {
							var percent = Math.round((e.loaded / e.total) * 100);
							if (percentEl) percentEl.textContent = 'Đang tải lên: ' + percent + '%';
							if (progEl) progEl.value = percent;
						}
					};

					xhr.onload = function() {
						if (xhr.status >= 200 && xhr.status < 300) {
							itemLi.className = 'gov-uploaded-item gov-uploaded-item--success';
							itemLi.innerHTML = 
								'<div class="gov-item-left">' +
									'<div class="gov-file-badge ' + info.badgeClass + '">' + info.badgeLabel + '</div>' +
									'<div class="gov-file-details">' +
										'<div class="gov-file-name" title="' + escName + '">' + escName + '</div>' +
										'<div class="gov-file-meta">' +
											'<span class="gov-file-size">' + formatBytes(item.size) + '</span>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<div class="gov-item-right">' +
									'<span class="gov-uploaded-status-badge gov-uploaded-status-badge--success">' +
										'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" style="vertical-align:-1px;margin-right:4px;"><polyline points="20 6 9 17 4 12"></polyline></svg>' +
										'Tiếp nhận thành công' +
									'</span>' +
								'</div>';
						} else {
							itemLi.className = 'gov-uploaded-item gov-uploaded-item--error';
							var errText = 'Lỗi tải lên (' + xhr.status + ')';
							if (xhr.status === 409) {
								errText = 'Lỗi 409: Thư mục chưa được cấp quyền ghi';
							}
							itemLi.innerHTML = 
								'<div class="gov-item-left">' +
									'<div class="gov-file-badge ' + info.badgeClass + '">' + info.badgeLabel + '</div>' +
									'<div class="gov-file-details">' +
										'<div class="gov-file-name" title="' + escName + '">' + escName + '</div>' +
										'<div class="gov-file-meta">' +
											'<span class="gov-file-size">' + formatBytes(item.size) + '</span>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<div class="gov-item-right">' +
									'<span class="gov-uploaded-status-badge gov-uploaded-status-badge--error">' +
										'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:-1px;margin-right:4px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>' +
										errText +
									'</span>' +
								'</div>';
						}
						uploadNext(idx + 1);
					};

					xhr.onerror = function() {
						itemLi.className = 'gov-uploaded-item gov-uploaded-item--error';
						itemLi.innerHTML = 
							'<div class="gov-item-left">' +
								'<div class="gov-file-badge ' + info.badgeClass + '">' + info.badgeLabel + '</div>' +
								'<div class="gov-file-details">' +
									'<div class="gov-file-name" title="' + escName + '">' + escName + '</div>' +
									'<div class="gov-file-meta">' +
										'<span class="gov-file-size">' + formatBytes(item.size) + '</span>' +
									'</div>' +
								'</div>' +
							'</div>' +
							'<div class="gov-item-right">' +
								'<span class="gov-uploaded-status-badge gov-uploaded-status-badge--error">' +
									'<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="vertical-align:-1px;margin-right:4px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>' +
									'Lỗi kết nối mạng' +
								'</span>' +
							'</div>';
						uploadNext(idx + 1);
					};

					xhr.send(item.file);
				};

				uploadNext(0);
			};

			document.addEventListener('click', function(e) {
				var confirmBtn = e.target.closest('#confirm-upload-btn');
				if (confirmBtn) {
					e.preventDefault();
					e.stopPropagation();
					startUploadQueue();
				}
				var moreBtn = e.target.closest('#gov-done-upload-more-btn');
				if (moreBtn) {
					e.preventDefault();
					var doneIndicator = document.getElementById('drop-upload-done-indicator');
					var doneActions = document.getElementById('gov-done-actions-wrap');
					var dropzoneHero = document.getElementById('gov-dropzone-hero');
					var uploadedList = document.getElementById('drop-uploaded-files');
					if (doneIndicator) {
						doneIndicator.classList.add('hidden');
						doneIndicator.style.setProperty('display', 'none', 'important');
					}
					if (doneActions) {
						doneActions.classList.add('hidden');
						doneActions.style.setProperty('display', 'none', 'important');
					}
					if (uploadedList) {
						uploadedList.innerHTML = '';
					}
					if (dropzoneHero) {
						dropzoneHero.classList.remove('hidden');
						dropzoneHero.style.setProperty('display', 'block', 'important');
					}
					pendingFiles = [];
					updateStepper(1);
					updateConfirmState();
				}
			}, true);

			var init = function() {
				var cb = document.getElementById('accept-terms-checkbox-final');
				if (cb) {
					cb.checked = false;
				}
				updateStepper(1);
				updateConfirmState();
			};

			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', init);
			} else {
				init();
			}
		})();
	</script>

	</main>
	<footer class="gov-public-footer">
		<div class="gov-public-footer__inner">
			<div class="gov-footer-brand">
				<p class="gov-footer-title">⚙️ HỆ THỐNG TIẾP NHẬN TÀI LIỆU SỐ HÓA &amp; HỒ SƠ ĐIỆN TỬ</p>
				<p class="gov-footer-desc">Được xây dựng và vận hành theo Khung Kiến trúc Chính phủ điện tử Việt Nam.</p>
			</div>
			<div class="gov-footer-meta">
				<div class="gov-cert-tag">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="#10b981"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
					<span>Mã hóa bảo mật TLS 1.3 / AES-256</span>
				</div>
				<p class="gov-copyright">© 2026 Nguyễn Hùng Anh. All rights reserved.</p>
			</div>
		</div>
	</footer>
</div>

</div>

<style>
	:root {
		--gov-blue-primary: #0B4F8A;
		--gov-blue-dark: #083B66;
		--gov-blue-light: #0284C7;
		--gov-blue-soft: #EFF6FF;
		--gov-gold: #F59E0B;
		--gov-gold-dark: #D97706;
		--gov-red: #B91C1C;
		--gov-emerald: #10B981;
		--gov-emerald-soft: #F0FDF4;
		--gov-emerald-border: #86EFAC;
		--gov-text-title: #0F172A;
		--gov-text-body: #334155;
		--gov-text-muted: #64748B;
		--gov-border: #E2E8F0;
		--gov-card-bg: #FFFFFF;
		--gov-canvas: #F4F6F9;
		--gov-shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.08);
		--gov-shadow-md: 0 4px 16px -2px rgba(11, 79, 138, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.04);
		--gov-shadow-lg: 0 12px 30px -4px rgba(11, 79, 138, 0.12), 0 4px 12px -2px rgba(15, 23, 42, 0.06);
		--gov-radius-sm: 6px;
		--gov-radius-md: 10px;
		--gov-radius-lg: 14px;
	}

	.gov-public-shell {
		width: 100% !important;
		max-width: none !important;
		margin: 0 !important;
		padding: 0 !important;
		border-radius: 0 !important;
		min-height: 100vh !important;
		background: #f4f6f8 !important;
		display: flex !important;
		flex-direction: column !important;
		box-sizing: border-box !important;
		overflow: visible !important;
	}

	.gov-public-header {
		background: linear-gradient(135deg, #0B4F8A 0%, #07355C 100%) !important;
		border-bottom: 3px solid #F59E0B !important;
		box-shadow: 0 3px 12px rgba(11, 79, 138, 0.16) !important;
		color: #FFF !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	.gov-public-header__inner {
		display: flex !important;
		align-items: center !important;
		gap: 16px !important;
		max-width: 1120px !important;
		margin: 0 auto !important;
		padding: 9px 20px 10px !important;
		box-sizing: border-box !important;
	}

	.gov-public-emblem {
		flex: 0 0 44px !important;
		width: 44px !important;
		height: 44px !important;
		background: #FFFFFF !important;
		border: 2px solid #F59E0B !important;
		border-radius: 50% !important;
		box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.25), 0 2px 8px rgba(0, 0, 0, 0.14) !important;
		padding: 2px !important;
		box-sizing: border-box !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		overflow: hidden !important;
	}

	.gov-emblem-img {
		width: 100% !important;
		height: 100% !important;
		object-fit: contain !important;
		border-radius: 50% !important;
		display: block !important;
	}

	.gov-public-title {
		flex: 1 1 auto !important;
		text-align: left !important;
		min-width: 0 !important;
	}

	.gov-public-kicker {
		display: inline-flex !important;
		align-items: center !important;
		gap: 5px !important;
		margin: 0 0 3px 0 !important;
		padding: 2px 8px !important;
		background: rgba(245, 158, 11, 0.16) !important;
		border: 1px solid rgba(245, 158, 11, 0.5) !important;
		border-radius: 4px !important;
		color: #FDE68A !important;
		font-size: 10px !important;
		font-weight: 800 !important;
		letter-spacing: 0.4px !important;
		text-transform: uppercase !important;
		line-height: 1.2 !important;
	}

	.gov-public-kicker span {
		border: none !important;
		background: transparent !important;
		padding: 0 !important;
		margin: 0 !important;
		color: inherit !important;
		font-size: inherit !important;
		font-weight: inherit !important;
		letter-spacing: inherit !important;
		text-transform: inherit !important;
		line-height: inherit !important;
		display: inline !important;
		box-shadow: none !important;
	}

	.gov-public-kicker svg {
		width: 12px !important;
		height: 12px !important;
		min-width: 12px !important;
		flex-shrink: 0 !important;
		display: inline-block !important;
		vertical-align: middle !important;
	}

	.gov-public-title h1 {
		margin: 0 0 2px 0 !important;
		padding-top: 1px !important;
		color: #FFFFFF !important;
		font-size: 18px !important;
		font-weight: 800 !important;
		line-height: 1.25 !important;
		letter-spacing: 0.2px !important;
		text-transform: uppercase !important;
		text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) !important;
	}

	.gov-public-title p {
		margin: 0 !important;
		color: rgba(255, 255, 255, 0.9) !important;
		font-size: 11.5px !important;
		line-height: 1.25 !important;
		font-weight: 400 !important;
	}

	.gov-header-security-wrap {
		margin-left: auto !important;
		flex-shrink: 0 !important;
	}

	.gov-security-badge {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		padding: 5px 12px !important;
		background: rgba(255, 255, 255, 0.12) !important;
		border: 1px solid rgba(255, 255, 255, 0.28) !important;
		backdrop-filter: blur(8px) !important;
		-webkit-backdrop-filter: blur(8px) !important;
		border-radius: 20px !important;
		font-size: 10.5px !important;
		font-weight: 700 !important;
		letter-spacing: 0.4px !important;
		color: #FFFFFF !important;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
		white-space: nowrap !important;
	}

	.gov-sec-icon {
		color: #34D399 !important;
		flex-shrink: 0 !important;
	}

	.gov-public-main {
		max-width: 960px !important;
		margin: 0 auto !important;
		padding: 28px 16px 40px !important;
		flex: 1 0 auto !important;
		width: 100% !important;
		box-sizing: border-box !important;
	}

	#public-upload .emptycontent.gov-emptycontent {
		box-sizing: border-box !important;
		width: 100% !important;
		max-width: 920px !important;
		margin: 0 auto !important;
		padding: 32px 36px 36px !important;
		border: 1px solid #D6DDE5 !important;
		border-radius: var(--gov-radius-lg) !important;
		background: #FFFFFF !important;
		box-shadow: var(--gov-shadow-lg) !important;
		text-align: center !important;
		position: relative !important;
	}

	.gov-upload-stepper-wrap {
		width: 100% !important;
		margin: 0 0 28px 0 !important;
		padding-bottom: 20px !important;
		border-bottom: 1px solid #E2E8F0 !important;
	}

	.gov-stepper {
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		max-width: 620px !important;
		margin: 0 auto !important;
		gap: 0 !important;
	}

	.gov-step {
		display: flex !important;
		align-items: center !important;
		gap: 10px !important;
		flex: 0 0 auto !important;
	}

	.gov-step__circle {
		width: 32px !important;
		height: 32px !important;
		border-radius: 50% !important;
		background: #F1F5F9 !important;
		border: 2px solid #CBD5E1 !important;
		color: #64748B !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		font-size: 13px !important;
		font-weight: 800 !important;
		transition: all 0.3s ease !important;
	}

	.gov-step__label {
		display: flex !important;
		flex-direction: column !important;
		text-align: left !important;
		line-height: 1.2 !important;
	}

	.gov-step__num {
		font-size: 10.5px !important;
		font-weight: 700 !important;
		color: #64748B !important;
		text-transform: uppercase !important;
		letter-spacing: 0.5px !important;
	}

	.gov-step__text {
		font-size: 12.5px !important;
		font-weight: 700 !important;
		color: #334155 !important;
	}

	.gov-step__line {
		flex: 1 1 40px !important;
		height: 2.5px !important;
		background: #E2E8F0 !important;
		margin: 0 12px !important;
		border-radius: 2px !important;
		transition: all 0.3s ease !important;
	}

	.gov-step--active .gov-step__circle {
		background: #0B4F8A !important;
		border-color: #0B4F8A !important;
		color: #FFFFFF !important;
		box-shadow: 0 0 0 4px rgba(11, 79, 138, 0.16) !important;
	}

	.gov-step--active .gov-step__num {
		color: #0B4F8A !important;
	}

	.gov-step--active .gov-step__text {
		color: #0B4F8A !important;
	}

	.gov-step--done .gov-step__circle {
		background: #10B981 !important;
		border-color: #10B981 !important;
		color: #FFFFFF !important;
	}

	.gov-step--done .gov-step__num {
		color: #059669 !important;
	}

	.gov-step__line--active {
		background: #10B981 !important;
	}

	.gov-dropzone-hero {
		padding: 10px 0 16px !important;
	}

	.gov-hero-shield {
		width: 72px !important;
		height: 72px !important;
		margin: 0 auto 16px !important;
		border-radius: 50% !important;
		background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%) !important;
		border: 1px solid #BFDBFE !important;
		color: #0B4F8A !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
	}

	#public-upload .emptycontent h2 {
		margin: 0 0 8px !important;
		color: #0F172A !important;
		font-size: 22px !important;
		font-weight: 800 !important;
		line-height: 1.3 !important;
		letter-spacing: -0.2px !important;
	}

	.gov-dest-folder {
		display: inline-flex !important;
		align-items: center !important;
		margin: 0 0 20px !important;
		padding: 6px 14px !important;
		background: #F8FAFC !important;
		border: 1px solid #E2E8F0 !important;
		border-radius: 6px !important;
		color: #334155 !important;
		font-size: 13.5px !important;
		font-weight: 600 !important;
	}

	.gov-note-card {
		box-sizing: border-box !important;
		max-width: 680px !important;
		margin: 0 auto 24px !important;
		padding: 14px 18px !important;
		background: #FEF9C3 !important;
		border: 1px solid #FDE047 !important;
		border-left: 4px solid #CA8A04 !important;
		border-radius: 6px !important;
		text-align: left !important;
	}

	.gov-note-title {
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
		color: #854D0E !important;
		font-size: 13px !important;
		font-weight: 700 !important;
		margin-bottom: 4px !important;
	}

	.gov-note-card .note {
		margin: 0 !important;
		color: #713F12 !important;
		font-size: 13.5px !important;
		line-height: 1.45 !important;
	}

	.gov-upload-action-box {
		margin-top: 14px !important;
	}

	#body-public #public-upload .emptycontent #public-upload-btn,
	#body-public #public-upload .emptycontent .button.icon-upload,
	#body-public #public-upload .emptycontent label#public-upload-btn,
	#body-public #public-upload .emptycontent .gov-dropzone-hero #public-upload-btn,
	#body-public #public-upload .emptycontent label.button.icon-upload,
	#public-upload .emptycontent #public-upload-btn,
	#public-upload .emptycontent .button.icon-upload,
	#public-upload #public-upload-btn,
	#public-upload-btn {
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		min-width: 290px !important;
		max-width: 100% !important;
		height: 52px !important;
		min-height: 52px !important;
		padding: 0 32px !important;
		margin: 8px auto 0 !important;
		background: linear-gradient(135deg, #0B4F8A 0%, #0284C7 100%) !important;
		background-color: #0B4F8A !important;
		background-image: linear-gradient(135deg, #0B4F8A 0%, #0284C7 100%) !important;
		border: 1.5px solid #07355C !important;
		border-radius: 8px !important;
		color: #FFFFFF !important;
		font-size: 15.5px !important;
		font-weight: 700 !important;
		letter-spacing: 0.2px !important;
		text-decoration: none !important;
		opacity: 1 !important;
		visibility: visible !important;
		cursor: pointer !important;
		user-select: none !important;
		-webkit-user-select: none !important;
		box-shadow: 0 4px 16px rgba(11, 79, 138, 0.35), 0 1px 3px rgba(0, 0, 0, 0.12) !important;
		transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
		outline: none !important;
	}

	#body-public #public-upload .emptycontent #public-upload-btn:hover,
	#body-public #public-upload .emptycontent .button.icon-upload:hover,
	#body-public #public-upload .emptycontent label#public-upload-btn:hover,
	#public-upload .emptycontent #public-upload-btn:hover,
	#public-upload .emptycontent .button.icon-upload:hover,
	#public-upload #public-upload-btn:hover,
	#public-upload-btn:hover {
		background: linear-gradient(135deg, #07355C 0%, #0B4F8A 100%) !important;
		background-color: #07355C !important;
		background-image: linear-gradient(135deg, #07355C 0%, #0B4F8A 100%) !important;
		border-color: #042038 !important;
		color: #FFFFFF !important;
		opacity: 1 !important;
		transform: translateY(-2px) !important;
		box-shadow: 0 8px 24px rgba(7, 53, 92, 0.45), 0 2px 6px rgba(0, 0, 0, 0.15) !important;
	}

	#body-public #public-upload .emptycontent #public-upload-btn:active,
	#public-upload-btn:active {
		transform: translateY(0) !important;
		opacity: 1 !important;
		box-shadow: 0 2px 8px rgba(7, 53, 92, 0.35) !important;
	}

	#public-upload-btn * {
		pointer-events: none !important;
	}

	#public-upload-btn svg {
		width: 22px !important;
		height: 22px !important;
		min-width: 22px !important;
		margin-right: 10px !important;
		color: #FFFFFF !important;
		stroke: #FFFFFF !important;
		display: inline-block !important;
		vertical-align: middle !important;
		flex-shrink: 0 !important;
		transition: transform 0.2s ease !important;
	}

	#public-upload-btn:hover svg {
		transform: translateY(-2px) !important;
	}

	#public-upload-btn span {
		color: #FFFFFF !important;
		font-size: 15.5px !important;
		font-weight: 700 !important;
		line-height: 1.2 !important;
		display: inline-block !important;
		vertical-align: middle !important;
	}

	.gov-dropzone-hero {
		padding: 16px 20px 24px !important;
		border: 2px dashed transparent !important;
		border-radius: 12px !important;
		transition: all 0.25s ease !important;
	}

	.gov-dropzone-hero.is-dragover,
	#public-upload.dragover,
	#emptycontent.dragover {
		border: 2.5px dashed #0B4F8A !important;
		background: #EFF6FF !important;
		box-shadow: 0 12px 36px rgba(11, 79, 138, 0.18), inset 0 0 0 2px rgba(11, 79, 138, 0.1) !important;
		transform: scale(1.015) !important;
	}

	.gov-dropzone-hero.is-dragover #public-upload-btn,
	#public-upload-btn.is-dragover {
		background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%) !important;
		background-color: #F59E0B !important;
		border-color: #B45309 !important;
		box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45) !important;
		transform: scale(1.05) !important;
	}

	.gov-dropzone-hero.is-dragover #public-upload-btn svg {
		animation: gov-bounce 0.6s infinite alternate ease-in-out !important;
	}

	@keyframes gov-bounce {
		0% { transform: translateY(0); }
		100% { transform: translateY(-4px); }
	}

	.gov-upload-hint {
		margin: 14px 0 0 !important;
		color: #64748B !important;
		font-size: 13px !important;
		line-height: 1.5 !important;
	}

	#upload-confirm-section {
		width: 100% !important;
		margin-top: 24px !important;
		padding: 0 !important;
		border: 1px solid #CBD5E1 !important;
		border-radius: var(--gov-radius-lg) !important;
		background: #FFFFFF !important;
		box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
		overflow: hidden !important;
		text-align: left !important;
	}

	#upload-confirm-header {
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		padding: 16px 22px !important;
		background: linear-gradient(90deg, #F8FAFC 0%, #F1F5F9 100%) !important;
		border-bottom: 2px solid #E2E8F0 !important;
		position: relative !important;
	}

	#upload-confirm-header::after {
		content: "" !important;
		position: absolute !important;
		left: 0 !important;
		top: 0 !important;
		bottom: 0 !important;
		width: 4px !important;
		background: #0B4F8A !important;
	}

	.confirm-header-left {
		display: flex !important;
		align-items: center !important;
		gap: 12px !important;
	}

	.confirm-header-icon {
		width: 38px !important;
		height: 38px !important;
		border-radius: 8px !important;
		background: #EFF6FF !important;
		border: 1px solid #BFDBFE !important;
		color: #0B4F8A !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		flex-shrink: 0 !important;
	}

	.confirm-header-text h3 {
		all: unset !important;
		display: block !important;
		margin: 0 !important;
		color: #0F172A !important;
		font-size: 16.5px !important;
		font-weight: 800 !important;
		line-height: 1.25 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
	}

	.confirm-header-subtitle {
		margin: 3px 0 0 !important;
		color: #64748B !important;
		font-size: 12.5px !important;
		line-height: 1.35 !important;
	}

	.gov-pill-badge {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		padding: 6px 14px !important;
		background: #EFF6FF !important;
		border: 1px solid #BFDBFE !important;
		border-radius: 20px !important;
		color: #0B4F8A !important;
		font-size: 13px !important;
		font-weight: 700 !important;
		white-space: nowrap !important;
	}

	.pending-files-container {
		padding: 16px 20px !important;
		background: #FAFAFA !important;
	}

	#drop-pending-files {
		list-style: none !important;
		margin: 0 !important;
		padding: 0 !important;
		display: flex !important;
		flex-direction: column !important;
		gap: 10px !important;
		max-height: 240px !important;
		overflow-y: auto !important;
	}

	.gov-pending-item {
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		gap: 14px !important;
		padding: 12px 16px !important;
		background: #FFFFFF !important;
		border: 1px solid #E2E8F0 !important;
		border-radius: var(--gov-radius-md) !important;
		box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04) !important;
		transition: all 0.2s ease !important;
	}

	.gov-pending-item:hover {
		border-color: #93C5FD !important;
		box-shadow: 0 4px 12px rgba(11, 79, 138, 0.08) !important;
		transform: translateY(-1px) !important;
	}

	.gov-item-left {
		display: flex !important;
		align-items: center !important;
		gap: 14px !important;
		min-width: 0 !important;
		flex: 1 1 auto !important;
	}

	.gov-file-badge {
		width: 44px !important;
		height: 44px !important;
		border-radius: 8px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		font-size: 12px !important;
		font-weight: 800 !important;
		color: #FFF !important;
		flex-shrink: 0 !important;
		letter-spacing: 0.5px !important;
		text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
	}

	.badge-docx { background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%) !important; }
	.badge-pdf { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%) !important; }
	.badge-xlsx { background: linear-gradient(135deg, #15803D 0%, #16A34A 100%) !important; }
	.badge-pptx { background: linear-gradient(135deg, #EA580C 0%, #F97316 100%) !important; }
	.badge-zip { background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%) !important; }
	.badge-img { background: linear-gradient(135deg, #7C3AED 0%, #8B5CF6 100%) !important; }
	.badge-txt { background: linear-gradient(135deg, #475569 0%, #64748B 100%) !important; }
	.badge-generic { background: linear-gradient(135deg, #334155 0%, #475569 100%) !important; }

	.gov-file-details {
		min-width: 0 !important;
		flex: 1 1 auto !important;
		text-align: left !important;
	}

	.gov-file-name {
		font-size: 14.5px !important;
		font-weight: 700 !important;
		color: #0F172A !important;
		line-height: 1.35 !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
	}

	.gov-file-meta {
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
		margin-top: 3px !important;
		font-size: 12px !important;
		color: #64748B !important;
	}

	.gov-file-size {
		font-weight: 600 !important;
		color: #475569 !important;
	}

	.gov-meta-dot {
		color: #CBD5E1 !important;
	}

	.gov-file-status {
		color: #059669 !important;
		font-weight: 600 !important;
	}

	.gov-item-right {
		flex-shrink: 0 !important;
	}

	.gov-file-remove-btn {
		all: unset !important;
		box-sizing: border-box !important;
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		height: 32px !important;
		padding: 0 12px !important;
		background: #FFF1F2 !important;
		border: 1px solid #FECDD3 !important;
		border-radius: 6px !important;
		color: #E11D48 !important;
		font-size: 12.5px !important;
		font-weight: 700 !important;
		cursor: pointer !important;
		transition: all 0.2s ease !important;
	}

	.gov-file-remove-btn:hover {
		background: #FFE4E6 !important;
		border-color: #FDA4AF !important;
		color: #BE123C !important;
	}

	#upload-confirm-add-more {
		padding: 10px 20px 16px !important;
		background: #FAFAFA !important;
		border-bottom: 1px solid #E2E8F0 !important;
	}

	#add-more-files-btn {
		all: unset !important;
		box-sizing: border-box !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		height: 38px !important;
		padding: 0 18px !important;
		background: #FFFFFF !important;
		border: 1.5px dashed #0B4F8A !important;
		border-radius: 6px !important;
		color: #0B4F8A !important;
		font-size: 13.5px !important;
		font-weight: 700 !important;
		cursor: pointer !important;
		transition: all 0.2s ease !important;
	}

	#add-more-files-btn:hover {
		background: #EFF6FF !important;
		border-color: #083B66 !important;
		border-style: solid !important;
	}

	#terms-agreement {
		margin: 16px 20px 18px !important;
		border: 1px solid #E2E8F0 !important;
		border-radius: 8px !important;
		background: #FFFFFF !important;
		overflow: hidden !important;
	}

	#terms-agreement-content {
		padding: 16px 16px 12px !important;
		background: #FFFFFF !important;
	}

	.terms-title-wrap {
		display: flex !important;
		align-items: flex-start !important;
		gap: 12px !important;
		margin-bottom: 12px !important;
	}

	.terms-shield-icon {
		width: 38px !important;
		height: 38px !important;
		border-radius: 8px !important;
		background: #FEF2F2 !important;
		border: 1px solid #FECACA !important;
		color: #DC2626 !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		flex-shrink: 0 !important;
	}

	#terms-agreement-content h4 {
		all: unset !important;
		display: block !important;
		margin: 0 0 3px 0 !important;
		color: #03487C !important;
		font-size: 14px !important;
		font-weight: 800 !important;
		line-height: 1.35 !important;
		text-transform: uppercase !important;
		letter-spacing: 0.3px !important;
	}

	.terms-meta-ref {
		margin: 0 !important;
		color: #64748B !important;
		font-size: 11.5px !important;
		line-height: 1.35 !important;
	}

	#terms-text-box {
		box-sizing: border-box !important;
		width: 100% !important;
		height: 260px !important;
		max-height: 280px !important;
		overflow-y: auto !important;
		overflow-x: hidden !important;
		-webkit-overflow-scrolling: touch !important;
		touch-action: pan-y !important;
		overscroll-behavior: auto !important;
		overscroll-behavior-y: auto !important;
		transform: translate3d(0, 0, 0) !important;
		-webkit-transform: translate3d(0, 0, 0) !important;
		will-change: scroll-position !important;
		backface-visibility: hidden !important;
		-webkit-backface-visibility: hidden !important;
		padding: 14px 16px !important;
		background: #FFFFFF !important;
		border: 1px solid #E2E8F0 !important;
		border-radius: 6px !important;
		color: #1E293B !important;
		font-size: 13.5px !important;
		line-height: 1.65 !important;
		text-align: justify !important;
	}

	#terms-text-box::-webkit-scrollbar,
	#drop-pending-files::-webkit-scrollbar {
		width: 6px !important;
	}

	#terms-text-box::-webkit-scrollbar-track,
	#drop-pending-files::-webkit-scrollbar-track {
		background: #F1F5F9 !important;
		border-radius: 3px !important;
	}

	#terms-text-box::-webkit-scrollbar-thumb,
	#drop-pending-files::-webkit-scrollbar-thumb {
		background: #CBD5E1 !important;
		border-radius: 3px !important;
	}

	#terms-text-box::-webkit-scrollbar-thumb:hover,
	#drop-pending-files::-webkit-scrollbar-thumb:hover {
		background: #0B4F8A !important;
	}

	#terms-text-box .gov-doc-heading,
	.gov-doc-heading {
		display: flex !important;
		flex-direction: column !important;
		align-items: center !important;
		justify-content: center !important;
		text-align: center !important;
		margin: 4px auto 16px !important;
		padding: 0 !important;
		width: 100% !important;
	}

	#terms-text-box .gov-doc-country,
	.gov-doc-country {
		display: block !important;
		text-align: center !important;
		margin: 0 0 4px 0 !important;
		font-size: 13.5px !important;
		font-weight: 800 !important;
		color: #0F172A !important;
		letter-spacing: 0.5px !important;
		text-transform: uppercase !important;
		width: 100% !important;
	}

	#terms-text-box .gov-doc-motto-wrap,
	.gov-doc-motto-wrap {
		display: inline-flex !important;
		flex-direction: column !important;
		align-items: center !important;
		justify-content: center !important;
		text-align: center !important;
		margin: 0 auto !important;
	}

	#terms-text-box .gov-doc-motto,
	.gov-doc-motto {
		all: unset !important;
		box-sizing: border-box !important;
		display: inline-block !important;
		text-align: center !important;
		margin: 0 auto !important;
		font-size: 13px !important;
		font-weight: 700 !important;
		color: #1E293B !important;
		padding-bottom: 4px !important;
		border-bottom: 1.5px solid #0B4F8A !important;
	}

	.terms-legal-box {
		padding: 10px 14px !important;
		background: #EFF6FF !important;
		border: 1px solid #BFDBFE !important;
		border-left: 3.5px solid #0B4F8A !important;
		border-radius: 6px !important;
		margin-bottom: 14px !important;
	}

	.terms-legal-ref {
		margin: 0 0 6px !important;
		color: #0B4F8A !important;
		font-size: 12.5px !important;
		font-weight: 800 !important;
		letter-spacing: 0.3px !important;
	}

	.terms-legal-list {
		margin: 0 !important;
		padding-left: 20px !important;
		list-style: decimal !important;
		font-size: 13px !important;
		color: #1E293B !important;
	}

	.terms-legal-list li {
		padding: 2px 0 !important;
	}

	.terms-intro {
		margin: 0 0 12px !important;
		font-size: 13px !important;
		font-style: italic !important;
		color: #475569 !important;
	}

	.terms-article {
		margin-top: 14px !important;
		padding-top: 14px !important;
		border-top: 1px dotted #CBD5E1 !important;
	}

	.terms-article:first-of-type {
		border-top: none !important;
		margin-top: 0 !important;
		padding-top: 0 !important;
	}

	.terms-article-title {
		margin: 0 0 5px !important;
		font-size: 14px !important;
		font-weight: 700 !important;
		color: #03487C !important;
		line-height: 1.4 !important;
	}

	.article-badge {
		display: inline-block !important;
		padding: 2.5px 8px !important;
		background: #E0F2FE !important;
		border-radius: 4px !important;
		font-size: 12px !important;
		font-weight: 700 !important;
		color: #0284C7 !important;
		margin-right: 6px !important;
	}

	.terms-article p:last-child {
		margin: 0 !important;
		font-size: 13.5px !important;
		color: #1E293B !important;
		line-height: 1.65 !important;
		text-align: justify !important;
	}

	#terms-checkbox-label {
		display: flex !important;
		align-items: flex-start !important;
		gap: 10px !important;
		padding: 12px 14px !important;
		background: #FFFFFF !important;
		border-top: 1px solid #E2E8F0 !important;
		cursor: pointer !important;
		user-select: none !important;
		transition: all 0.2s ease !important;
	}

	#terms-checkbox-label:hover {
		background: #F8FAFC !important;
	}

	.gov-checkbox-wrap {
		position: relative !important;
		width: 22px !important;
		height: 22px !important;
		flex-shrink: 0 !important;
		margin-top: 1px !important;
	}

	.gov-checkbox-wrap input[type="checkbox"] {
		position: absolute !important;
		opacity: 0 !important;
		width: 100% !important;
		height: 100% !important;
		margin: 0 !important;
		cursor: pointer !important;
		z-index: 2 !important;
	}

	.gov-checkbox-custom {
		position: absolute !important;
		inset: 0 !important;
		background: #FFFFFF !important;
		border: 2px solid #CBD5E1 !important;
		border-radius: 5px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		transition: all 0.2s ease !important;
	}

	.gov-checkbox-wrap input[type="checkbox"]:checked + .gov-checkbox-custom {
		background: #059669 !important;
		border-color: #059669 !important;
		box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
	}

	.gov-checkbox-custom svg {
		opacity: 0 !important;
		transform: scale(0.6) !important;
		transition: all 0.2s ease !important;
	}

	.gov-checkbox-wrap input[type="checkbox"]:checked + .gov-checkbox-custom svg {
		opacity: 1 !important;
		transform: scale(1) !important;
	}

	.gov-checkbox-text {
		color: #0F172A !important;
		font-size: 13px !important;
		font-weight: 700 !important;
		line-height: 1.4 !important;
	}

	.gov-confirm-hint-wrap {
		padding: 0 20px !important;
		margin: 12px 0 14px 0 !important;
		background: transparent !important;
	}

	.gov-confirm-hint {
		margin: 0 !important;
		padding: 9px 12px !important;
		border-radius: 8px !important;
		background: #F8FAFC !important;
		border: 1px solid #E2E8F0 !important;
		color: #64748B !important;
		font-size: 12px !important;
		font-weight: 500 !important;
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
		line-height: 1.35 !important;
		text-align: left !important;
		transition: all 0.25s ease !important;
	}

	.gov-confirm-hint svg {
		flex-shrink: 0 !important;
	}

	.gov-confirm-hint--ready {
		background: #ECFDF5 !important;
		border-color: #A7F3D0 !important;
		color: #047857 !important;
	}

	#upload-confirm-actions {
		display: flex !important;
		align-items: center !important;
		justify-content: flex-end !important;
		gap: 14px !important;
		padding: 16px 20px 20px !important;
		background: #F8FAFC !important;
		border-top: 1px solid #E2E8F0 !important;
		border-radius: 0 0 var(--gov-radius-lg) var(--gov-radius-lg) !important;
	}

	#cancel-upload-btn,
	#confirm-upload-btn {
		all: unset !important;
		box-sizing: border-box !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		height: 48px !important;
		min-height: 48px !important;
		padding: 0 28px !important;
		border-radius: 8px !important;
		font-size: 14.5px !important;
		font-weight: 700 !important;
		line-height: 1 !important;
		white-space: nowrap !important;
		cursor: pointer !important;
		transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
		user-select: none !important;
		-webkit-tap-highlight-color: transparent !important;
	}

	#cancel-upload-btn {
		background: #FFFFFF !important;
		border: 1.5px solid #CBD5E1 !important;
		color: #475569 !important;
		box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04) !important;
	}

	#cancel-upload-btn:hover {
		background: #F1F5F9 !important;
		border-color: #94A3B8 !important;
		color: #1E293B !important;
	}

	#cancel-upload-btn:active {
		transform: scale(0.98) !important;
	}

	#confirm-upload-btn.gov-btn--disabled {
		background: #E2E8F0 !important;
		border: 1.5px solid #CBD5E1 !important;
		color: #64748B !important;
		cursor: not-allowed !important;
		opacity: 0.8 !important;
		box-shadow: none !important;
		pointer-events: none !important;
	}

	#confirm-upload-btn.gov-btn--active {
		background: linear-gradient(135deg, #0B4F8A 0%, #0284C7 100%) !important;
		border: 1.5px solid #07355C !important;
		color: #FFFFFF !important;
		box-shadow: 0 4px 16px rgba(11, 79, 138, 0.35), 0 2px 4px rgba(11, 79, 138, 0.15) !important;
		cursor: pointer !important;
		opacity: 1 !important;
		pointer-events: auto !important;
	}

	#confirm-upload-btn.gov-btn--active:hover {
		background: linear-gradient(135deg, #083B66 0%, #0369A1 100%) !important;
		box-shadow: 0 6px 22px rgba(11, 79, 138, 0.45), 0 3px 6px rgba(11, 79, 138, 0.25) !important;
		transform: translateY(-1.5px) !important;
	}

	#confirm-upload-btn.gov-btn--active:active {
		transform: translateY(1px) scale(0.98) !important;
		box-shadow: 0 2px 8px rgba(11, 79, 138, 0.3) !important;
	}

	#upload-confirm-actions button svg,
	#confirm-upload-btn svg,
	#cancel-upload-btn svg,
	.btn-icon-lock,
	.btn-icon-upload {
		width: 18px !important;
		height: 18px !important;
		min-width: 18px !important;
		min-height: 18px !important;
		max-width: 18px !important;
		max-height: 18px !important;
		line-height: 18px !important;
		vertical-align: middle !important;
		flex: 0 0 18px !important;
		margin: 0 8px 0 0 !important;
		padding: 0 !important;
		border: none !important;
		box-sizing: content-box !important;
		overflow: hidden !important;
	}

	#cancel-upload-btn svg {
		width: 15px !important;
		height: 15px !important;
		min-width: 15px !important;
		min-height: 15px !important;
		max-width: 15px !important;
		max-height: 15px !important;
		flex: 0 0 15px !important;
		margin-right: 6px !important;
	}

	#body-public .hidden,
	#public-upload .hidden,
	#drop-upload-progress-indicator.hidden,
	#drop-upload-done-indicator.hidden,
	#upload-confirm-section.hidden,
	#gov-dropzone-hero.hidden,
	.gov-dropzone-hero.hidden,
	.hidden {
		display: none !important;
		visibility: hidden !important;
		opacity: 0 !important;
		height: 0 !important;
		min-height: 0 !important;
		max-height: 0 !important;
		padding: 0 !important;
		margin: 0 !important;
		border: none !important;
		pointer-events: none !important;
		overflow: hidden !important;
	}

	#drop-upload-progress-indicator:not(.hidden) {
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 16px !important;
		padding: 24px 20px !important;
		background: #F8FAFC !important;
		border: 1px solid #E2E8F0 !important;
		border-radius: var(--gov-radius-md) !important;
		margin: 20px 0 !important;
		text-align: left !important;
	}

	.gov-upload-spinner .spinner-svg {
		animation: gov-rotate 2s linear infinite !important;
	}

	.gov-upload-spinner .path {
		stroke: #0B4F8A !important;
		stroke-linecap: round !important;
		animation: gov-dash 1.5s ease-in-out infinite !important;
	}

	@keyframes gov-rotate {
		100% { transform: rotate(360deg); }
	}

	@keyframes gov-dash {
		0% { stroke-dasharray: 1, 150; stroke-dashoffset: 0; }
		50% { stroke-dasharray: 90, 150; stroke-dashoffset: -35; }
		100% { stroke-dasharray: 90, 150; stroke-dashoffset: -124; }
	}

	.gov-progress-text h4 {
		margin: 0 0 4px !important;
		font-size: 15px !important;
		font-weight: 700 !important;
		color: #0F172A !important;
	}

	.gov-progress-text p {
		margin: 0 !important;
		font-size: 12.5px !important;
		color: #64748B !important;
	}

	#body-public #public-upload .emptycontent #drop-upload-done-indicator,
	#drop-upload-done-indicator:not(.hidden) {
		display: block !important;
		box-sizing: border-box !important;
		width: 100% !important;
		max-width: 640px !important;
		margin: 24px auto 0 !important;
		padding: 24px 24px !important;
		background: #F0FDF4 !important;
		border: 1.5px solid #86EFAC !important;
		border-radius: 12px !important;
		text-align: center !important;
		box-shadow: 0 2px 10px rgba(16, 185, 129, 0.08) !important;
	}

	.gov-done-badge {
		width: 52px !important;
		height: 52px !important;
		border-radius: 50% !important;
		background: #DCFCE7 !important;
		border: 2px solid #86EFAC !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		margin: 0 auto 12px !important;
		box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12) !important;
	}

	#drop-upload-done-indicator h4 {
		margin: 0 0 6px !important;
		color: #065F46 !important;
		font-size: 17px !important;
		font-weight: 800 !important;
		line-height: 1.35 !important;
	}

	#drop-upload-done-indicator p {
		margin: 0 !important;
		color: #047857 !important;
		font-size: 13.5px !important;
		line-height: 1.45 !important;
	}

	#body-public #public-upload .emptycontent #drop-uploaded-files,
	#body-public #public-upload .emptycontent ul#drop-uploaded-files,
	#drop-uploaded-files {
		box-sizing: border-box !important;
		width: 100% !important;
		max-width: 640px !important;
		margin: 18px auto 0 !important;
		padding: 0 !important;
		list-style: none !important;
		display: flex !important;
		flex-direction: column !important;
		gap: 10px !important;
		text-align: left !important;
	}

	#body-public #public-upload .emptycontent #drop-uploaded-files .gov-uploaded-item,
	#body-public #public-upload .emptycontent li.gov-uploaded-item,
	#drop-uploaded-files li {
		box-sizing: border-box !important;
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		gap: 14px !important;
		width: 100% !important;
		max-width: 100% !important;
		min-height: 60px !important;
		padding: 12px 18px !important;
		margin: 0 !important;
		background: #FFFFFF !important;
		border: 1px solid #E2E8F0 !important;
		border-radius: 10px !important;
		box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04) !important;
		overflow: visible !important;
		white-space: normal !important;
		text-overflow: clip !important;
		transition: all 0.2s ease !important;
	}

	#body-public #public-upload .emptycontent #drop-uploaded-files .gov-uploaded-item--success,
	#drop-uploaded-files li.gov-uploaded-item--success {
		background: #FAFDFA !important;
		border-color: #86EFAC !important;
		box-shadow: 0 2px 6px rgba(16, 185, 129, 0.06) !important;
	}

	#body-public #public-upload .emptycontent #drop-uploaded-files .gov-uploaded-item--error,
	#drop-uploaded-files li.gov-uploaded-item--error {
		background: #FEF2F2 !important;
		border-color: #FECACA !important;
	}

	#body-public #public-upload .emptycontent #drop-uploaded-files .gov-uploaded-item:hover,
	#drop-uploaded-files li:hover {
		border-color: #4ADE80 !important;
		box-shadow: 0 4px 14px rgba(16, 185, 129, 0.12) !important;
		transform: translateY(-1px) !important;
	}

	.gov-uploaded-item .gov-item-left {
		display: flex !important;
		align-items: center !important;
		gap: 14px !important;
		min-width: 0 !important;
		flex: 1 1 auto !important;
	}

	.gov-uploaded-item .gov-file-badge {
		width: 44px !important;
		height: 44px !important;
		border-radius: 8px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: center !important;
		font-size: 12px !important;
		font-weight: 800 !important;
		color: #FFF !important;
		flex-shrink: 0 !important;
		letter-spacing: 0.5px !important;
	}

	.gov-uploaded-item .gov-file-details {
		min-width: 0 !important;
		flex: 1 1 auto !important;
		text-align: left !important;
	}

	.gov-uploaded-item .gov-file-name {
		font-size: 14.5px !important;
		font-weight: 700 !important;
		color: #0F172A !important;
		line-height: 1.35 !important;
		white-space: nowrap !important;
		overflow: hidden !important;
		text-overflow: ellipsis !important;
	}

	.gov-uploaded-item .gov-file-meta {
		display: flex !important;
		align-items: center !important;
		gap: 8px !important;
		margin-top: 3px !important;
		font-size: 12.5px !important;
		color: #64748B !important;
		white-space: nowrap !important;
	}

	.gov-uploaded-item .gov-file-size {
		font-weight: 600 !important;
		color: #475569 !important;
	}

	.gov-uploaded-item .gov-item-right {
		display: flex !important;
		align-items: center !important;
		flex-shrink: 0 !important;
		margin-left: 12px !important;
	}

	.gov-uploaded-status-badge {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		padding: 6px 14px !important;
		border-radius: 20px !important;
		font-size: 12.5px !important;
		font-weight: 700 !important;
		line-height: 1 !important;
		white-space: nowrap !important;
	}

	.gov-uploaded-status-badge--success,
	.gov-status-success {
		background: #ECFDF5 !important;
		border: 1px solid #A7F3D0 !important;
		color: #047857 !important;
	}

	.gov-uploaded-status-badge--error,
	.gov-status-error {
		background: #FEF2F2 !important;
		border: 1px solid #FECACA !important;
		color: #DC2626 !important;
	}

	.gov-upload-bar-wrap {
		width: 140px !important;
		flex-shrink: 0 !important;
	}

	.gov-upload-bar-wrap progress {
		width: 100% !important;
		height: 8px !important;
		border-radius: 4px !important;
		overflow: hidden !important;
	}

	.gov-upload-bar-wrap progress::-webkit-progress-bar {
		background: #E2E8F0 !important;
		border-radius: 4px !important;
	}

	.gov-upload-bar-wrap progress::-webkit-progress-value {
		background: linear-gradient(90deg, #0B4F8A, #0284C7) !important;
		border-radius: 4px !important;
	}

	#gov-done-actions-wrap {
		width: 100% !important;
		max-width: 640px !important;
		margin: 22px auto 0 !important;
		text-align: center !important;
	}

	#gov-done-upload-more-btn {
		all: unset !important;
		box-sizing: border-box !important;
		display: inline-flex !important;
		align-items: center !important;
		justify-content: center !important;
		gap: 8px !important;
		height: 46px !important;
		min-height: 46px !important;
		padding: 0 26px !important;
		border-radius: 8px !important;
		background: linear-gradient(135deg, #0B4F8A 0%, #0284C7 100%) !important;
		border: 1.5px solid #07355C !important;
		color: #FFFFFF !important;
		font-size: 14.5px !important;
		font-weight: 700 !important;
		cursor: pointer !important;
		box-shadow: 0 4px 14px rgba(11, 79, 138, 0.28) !important;
		transition: all 0.2s ease !important;
		user-select: none !important;
	}

	#gov-done-upload-more-btn:hover {
		background: linear-gradient(135deg, #083B66 0%, #0369A1 100%) !important;
		box-shadow: 0 6px 20px rgba(11, 79, 138, 0.38) !important;
		transform: translateY(-1.5px) !important;
	}

	#gov-done-upload-more-btn:active {
		transform: translateY(1px) scale(0.98) !important;
	}

	#gov-done-upload-more-btn svg {
		width: 17px !important;
		height: 17px !important;
		min-width: 17px !important;
		min-height: 17px !important;
		flex: 0 0 17px !important;
		margin: 0 !important;
	}

	.gov-public-footer {
		margin-top: 36px !important;
		border-top: 1px solid #D6DDE5 !important;
		background: #FFFFFF !important;
		color: #475569 !important;
		box-shadow: 0 -2px 10px rgba(15, 23, 42, 0.03) !important;
	}

	.gov-public-footer__inner {
		max-width: 960px !important;
		margin: 0 auto !important;
		padding: 22px 20px !important;
		display: flex !important;
		align-items: center !important;
		justify-content: space-between !important;
		gap: 20px !important;
		text-align: left !important;
	}

	.gov-footer-badge {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		color: #0B4F8A !important;
		font-size: 11px !important;
		font-weight: 800 !important;
		letter-spacing: 0.6px !important;
		text-transform: uppercase !important;
		margin-bottom: 4px !important;
	}

	.gov-footer-title {
		margin: 0 0 4px !important;
		font-size: 13.5px !important;
		font-weight: 800 !important;
		color: #0F172A !important;
		letter-spacing: 0.2px !important;
	}

	.gov-footer-desc {
		margin: 0 !important;
		font-size: 12px !important;
		color: #64748B !important;
		line-height: 1.4 !important;
		max-width: 580px !important;
	}

	.gov-footer-meta {
		text-align: right !important;
		flex-shrink: 0 !important;
	}

	.gov-cert-tag {
		display: inline-flex !important;
		align-items: center !important;
		gap: 6px !important;
		padding: 4px 10px !important;
		background: #F0FDF4 !important;
		border: 1px solid #86EFAC !important;
		border-radius: 4px !important;
		font-size: 11.5px !important;
		font-weight: 700 !important;
		color: #065F46 !important;
		margin-bottom: 4px !important;
	}

	.gov-copyright {
		margin: 0 !important;
		font-size: 11.5px !important;
		color: #94A3B8 !important;
	}

	@media only screen and (max-width: 768px) {
		html {
			overflow-x: hidden !important;
			width: 100% !important;
		}

		body, #body-public {
			overflow: visible !important;
			width: 100% !important;
		}

		.gov-public-header__inner {
			flex-direction: column !important;
			align-items: center !important;
			text-align: center !important;
			padding: 8px 12px 9px !important;
			gap: 6px !important;
		}

		.gov-public-emblem {
			width: 40px !important;
			height: 40px !important;
			margin: 0 auto !important;
			flex: 0 0 40px !important;
		}

		.gov-public-title {
			text-align: center !important;
		}

		.gov-public-kicker {
			justify-content: center !important;
			font-size: 10.5px !important;
			margin-bottom: 8px !important;
		}

		.gov-public-title h1 {
			font-size: 17.5px !important;
			line-height: 1.4 !important;
			margin: 0 0 4px !important;
			padding-top: 2px !important;
		}

		.gov-public-title p {
			font-size: 12px !important;
			line-height: 1.45 !important;
			margin: 0 !important;
		}

		.gov-header-security-wrap {
			margin-left: 0 !important;
			order: 3 !important;
			margin-top: 4px !important;
		}

		.gov-security-badge {
			font-size: 10px !important;
			padding: 5px 12px !important;
		}

		.gov-public-main {
			padding: 12px 10px 24px !important;
		}

		#public-upload .emptycontent.gov-emptycontent {
			padding: 16px 10px !important;
			border-radius: 8px !important;
		}

		.gov-upload-stepper-wrap {
			padding: 8px 6px !important;
			margin-bottom: 12px !important;
		}

		.gov-stepper {
			width: 100% !important;
			max-width: 100% !important;
			justify-content: space-between !important;
			gap: 2px !important;
			padding: 4px 6px !important;
		}

		.gov-step {
			gap: 6px !important;
		}

		.gov-step__circle {
			width: 28px !important;
			height: 28px !important;
			min-width: 28px !important;
			font-size: 11.5px !important;
		}

		.gov-step__circle svg {
			width: 13px !important;
			height: 13px !important;
		}

		.gov-step__num {
			font-size: 9.5px !important;
		}

		.gov-step__text {
			font-size: 10.5px !important;
			white-space: nowrap !important;
		}

		.gov-step__line {
			flex: 1 1 14px !important;
			min-width: 10px !important;
			margin: 0 4px !important;
			height: 2px !important;
		}

		.gov-dropzone-hero {
			padding: 8px 4px 14px !important;
		}

		.gov-hero-shield {
			width: 42px !important;
			height: 42px !important;
			margin-bottom: 8px !important;
		}

		.gov-hero-shield svg {
			width: 24px !important;
			height: 24px !important;
		}

		.gov-dropzone-hero h2 {
			font-size: 16px !important;
		}

		.gov-dest-folder {
			font-size: 12.5px !important;
		}

		#public-upload-btn {
			width: 100% !important;
			min-height: 48px !important;
			font-size: 14.5px !important;
			padding: 12px 14px !important;
			border-radius: 8px !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			box-shadow: 0 3px 10px rgba(11, 79, 138, 0.22) !important;
		}

		.gov-upload-hint {
			font-size: 11.5px !important;
			margin-top: 8px !important;
		}

		#upload-confirm-section {
			width: 100% !important;
			margin-top: 14px !important;
			border-radius: 8px !important;
		}

		#upload-confirm-header {
			padding: 12px 14px !important;
			gap: 8px !important;
			flex-direction: column !important;
			align-items: flex-start !important;
		}

		.confirm-header-icon {
			width: 32px !important;
			height: 32px !important;
			min-width: 32px !important;
		}

		.confirm-header-text h3 {
			font-size: 14px !important;
		}

		.confirm-header-subtitle {
			font-size: 11.5px !important;
		}

		#pending-file-count {
			font-size: 11px !important;
			padding: 2px 8px !important;
		}

		.pending-files-container {
			padding: 8px 10px !important;
			max-height: 200px !important;
		}

		.gov-pending-item {
			flex-direction: row !important;
			align-items: center !important;
			justify-content: space-between !important;
			padding: 10px 10px !important;
			gap: 8px !important;
			border-radius: 8px !important;
		}

		.gov-pending-item .gov-item-left {
			gap: 10px !important;
			min-width: 0 !important;
			flex: 1 1 auto !important;
		}

		.gov-pending-item .gov-file-badge {
			width: 36px !important;
			height: 36px !important;
			min-width: 36px !important;
			font-size: 10.5px !important;
		}

		.gov-pending-item .gov-file-name {
			font-size: 13px !important;
		}

		.gov-pending-item .gov-file-meta {
			font-size: 11px !important;
		}

		.gov-pending-item .gov-file-remove-btn {
			height: 30px !important;
			padding: 0 8px !important;
			font-size: 11.5px !important;
		}

		#upload-confirm-add-more {
			margin: 10px 10px 14px !important;
		}

		#add-more-files-btn {
			width: 100% !important;
			min-height: 42px !important;
			justify-content: center !important;
			font-size: 13px !important;
			border-radius: 6px !important;
		}

		#upload-confirm-section {
			padding: 14px !important;
			border-radius: 10px !important;
			border: 1px solid #E2E8F0 !important;
			background: #FFFFFF !important;
			box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05) !important;
			margin: 10px auto !important;
			width: 100% !important;
			max-width: 100% !important;
			box-sizing: border-box !important;
		}

		#terms-agreement {
			margin: 0 0 12px 0 !important;
			border: 1px solid #E2E8F0 !important;
			border-radius: 8px !important;
			background: #FFFFFF !important;
		}

		#terms-agreement-content {
			padding: 12px 12px 10px 12px !important;
			background: #FFFFFF !important;
		}

		.terms-title-wrap {
			display: flex !important;
			align-items: flex-start !important;
			gap: 10px !important;
			margin-bottom: 10px !important;
		}

		.terms-shield-icon {
			width: 36px !important;
			height: 36px !important;
			min-width: 36px !important;
			border-radius: 8px !important;
			background: #FEF2F2 !important;
			border: 1px solid #FECACA !important;
			color: #DC2626 !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			flex-shrink: 0 !important;
		}

		#terms-agreement-content h4 {
			color: #03487C !important;
			font-size: 14px !important;
			font-weight: 800 !important;
			line-height: 1.35 !important;
			text-transform: uppercase !important;
			margin: 0 0 3px 0 !important;
		}

		.terms-meta-ref {
			color: #64748B !important;
			font-size: 12.5px !important;
			line-height: 1.45 !important;
			margin: 0 !important;
		}

		#terms-text-box {
			height: 380px !important;
			min-height: 340px !important;
			max-height: 52vh !important;
			background: #FFFFFF !important;
			border: 1px solid #E2E8F0 !important;
			border-radius: 6px !important;
			padding: 16px 14px !important;
			font-size: 14.5px !important;
			line-height: 1.7 !important;
			color: #0F172A !important;
			text-align: left !important;
			word-break: break-word !important;
			overflow-y: auto !important;
			overflow-x: hidden !important;
			-webkit-overflow-scrolling: touch !important;
			touch-action: pan-y !important;
			overscroll-behavior: auto !important;
			overscroll-behavior-y: auto !important;
			transform: translate3d(0, 0, 0) !important;
			-webkit-transform: translate3d(0, 0, 0) !important;
			will-change: scroll-position !important;
			backface-visibility: hidden !important;
			-webkit-backface-visibility: hidden !important;
			margin: 0 0 12px 0 !important;
		}

		#terms-text-box p {
			font-size: 14.5px !important;
			line-height: 1.7 !important;
			color: #1E293B !important;
			text-align: left !important;
			margin: 0 0 10px 0 !important;
		}

		#terms-text-box .terms-article-title {
			font-size: 15px !important;
			line-height: 1.45 !important;
			color: #03487C !important;
			margin-bottom: 6px !important;
			text-align: left !important;
		}

		#terms-text-box .article-badge {
			font-size: 12.5px !important;
			font-weight: 700 !important;
			padding: 3px 9px !important;
			border-radius: 4px !important;
		}

		#terms-text-box .terms-article p:last-child {
			font-size: 14.5px !important;
			line-height: 1.7 !important;
			color: #1E293B !important;
			text-align: left !important;
		}

		#terms-text-box .terms-intro {
			font-size: 14px !important;
			line-height: 1.65 !important;
			color: #334155 !important;
			text-align: left !important;
			margin-bottom: 12px !important;
		}

		#terms-text-box .terms-legal-box {
			font-size: 13.5px !important;
			line-height: 1.65 !important;
			padding: 12px 14px !important;
			margin-bottom: 14px !important;
			text-align: left !important;
		}

		#terms-text-box .terms-legal-ref {
			font-size: 13.5px !important;
			line-height: 1.4 !important;
			font-weight: 800 !important;
		}

		#terms-text-box .terms-legal-list {
			font-size: 13.5px !important;
			line-height: 1.65 !important;
			padding-left: 20px !important;
		}

		#terms-text-box .terms-legal-list li {
			font-size: 13.5px !important;
			line-height: 1.65 !important;
			padding: 3px 0 !important;
		}

		#terms-text-box .gov-doc-country {
			font-size: 14px !important;
			font-weight: 800 !important;
		}

		#terms-text-box .gov-doc-motto {
			font-size: 13.5px !important;
			font-weight: 700 !important;
		}

		#terms-checkbox-label {
			background: #FFFFFF !important;
			border-top: 1px solid #E2E8F0 !important;
			padding: 13px 12px !important;
			gap: 10px !important;
			display: flex !important;
			align-items: flex-start !important;
		}

		.gov-checkbox-wrap {
			width: 22px !important;
			height: 22px !important;
			min-width: 22px !important;
			margin-top: 1px !important;
		}

		.gov-checkbox-custom {
			width: 22px !important;
			height: 22px !important;
			min-width: 22px !important;
			border-radius: 5px !important;
		}

		.gov-checkbox-text {
			color: #0F172A !important;
			font-size: 13.5px !important;
			font-weight: 700 !important;
			line-height: 1.45 !important;
		}

		.gov-confirm-hint-wrap {
			padding: 0 !important;
			margin: 10px 0 12px 0 !important;
			background: transparent !important;
		}

		.gov-confirm-hint {
			font-size: 11.5px !important;
			padding: 8px 12px !important;
			border-radius: 8px !important;
		}

		#upload-confirm-actions {
			display: grid !important;
			grid-template-columns: 1fr 1.35fr !important;
			gap: 10px !important;
			padding: 0 !important;
			margin: 0 !important;
			background: transparent !important;
			border-top: none !important;
			width: 100% !important;
			box-sizing: border-box !important;
		}

		#cancel-upload-btn {
			width: 100% !important;
			height: 42px !important;
			min-height: 42px !important;
			padding: 0 8px !important;
			order: 1 !important;
			border-radius: 6px !important;
			background: #FFFFFF !important;
			border: 1.5px solid #CBD5E1 !important;
			color: #334155 !important;
			font-size: 13.5px !important;
			font-weight: 600 !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 6px !important;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
		}

		#confirm-upload-btn {
			width: 100% !important;
			height: 42px !important;
			min-height: 42px !important;
			padding: 0 8px !important;
			order: 2 !important;
			border-radius: 6px !important;
			font-size: 12.5px !important;
			font-weight: 700 !important;
			white-space: nowrap !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			gap: 6px !important;
		}

		#drop-upload-progress-indicator:not(.hidden) {
			padding: 18px 12px !important;
			flex-direction: column !important;
			text-align: center !important;
			gap: 12px !important;
		}

		.gov-progress-text {
			text-align: center !important;
		}

		#drop-upload-done-indicator:not(.hidden) {
			padding: 18px 14px !important;
			margin: 16px auto 0 !important;
		}

		#drop-uploaded-files {
			margin: 14px auto 0 !important;
			gap: 8px !important;
		}

		#drop-uploaded-files li,
		.gov-uploaded-item {
			padding: 10px 12px !important;
		}

		#gov-done-actions-wrap {
			margin: 16px auto 0 !important;
		}

		#gov-done-upload-more-btn {
			width: 100% !important;
			min-height: 48px !important;
			justify-content: center !important;
			font-size: 14.5px !important;
		}

		.gov-public-footer {
			padding: 18px 12px !important;
		}

		.gov-public-footer__inner {
			flex-direction: column !important;
			gap: 14px !important;
			text-align: center !important;
			align-items: center !important;
		}

		.gov-footer-badge {
			margin: 0 auto 6px !important;
			font-size: 10px !important;
		}

		.gov-footer-title {
			font-size: 12.5px !important;
		}

		.gov-footer-desc {
			font-size: 11.5px !important;
			line-height: 1.4 !important;
		}

		.gov-footer-meta {
			text-align: center !important;
			width: 100% !important;
		}

		.gov-cert-tag {
			margin: 0 auto 6px !important;
			font-size: 11px !important;
		}

		.gov-copyright {
			font-size: 11px !important;
		}
	}

	@media only screen and (max-width: 440px) {
		.gov-step__text {
			display: none !important;
		}
		.gov-step__num {
			font-size: 10px !important;
		}
		.gov-step {
			flex-direction: column !important;
			gap: 3px !important;
		}
		.gov-step__label {
			text-align: center !important;
		}
		.gov-step__line {
			margin-top: -12px !important;
		}
	}
</style>

<script<?= $nonceAttr ?>>
	(function() {
		var forceFilePreviewStyles = function() {
			var imgframe = document.getElementById('imgframe');
			var iframe = document.querySelector('#imgframe iframe, #imgframe .viewer__file, #imgframe #viewer');
			var header = document.querySelector('.gov-public-header');
			
			if (imgframe && (iframe || imgframe.children.length > 0 || imgframe.querySelector('img, video, audio, .text-preview'))) {
				if (header) {
					header.style.setProperty('display', 'none', 'important');
				}
				
				document.documentElement.style.setProperty('height', '100vh', 'important');
				document.documentElement.style.setProperty('min-height', '100vh', 'important');
				document.documentElement.style.setProperty('max-height', '100vh', 'important');
				document.documentElement.style.setProperty('overflow', 'hidden', 'important');
				document.documentElement.style.setProperty('margin', '0', 'important');
				document.documentElement.style.setProperty('padding', '0', 'important');
				
				document.body.style.setProperty('height', '100vh', 'important');
				document.body.style.setProperty('min-height', '100vh', 'important');
				document.body.style.setProperty('max-height', '100vh', 'important');
				document.body.style.setProperty('overflow', 'hidden', 'important');
				document.body.style.setProperty('margin', '0', 'important');
				document.body.style.setProperty('padding', '0', 'important');

				var content = document.getElementById('content');
				if (content) {
					content.style.setProperty('height', '100vh', 'important');
					content.style.setProperty('min-height', '100vh', 'important');
					content.style.setProperty('max-height', '100vh', 'important');
					content.style.setProperty('overflow', 'hidden', 'important');
					content.style.setProperty('margin', '0', 'important');
					content.style.setProperty('padding', '0', 'important');
					content.style.setProperty('max-width', 'none', 'important');
					content.style.setProperty('width', '100%', 'important');
				}

				var appContent = document.getElementById('app-content');
				if (appContent) {
					appContent.style.setProperty('height', '100vh', 'important');
					appContent.style.setProperty('min-height', '100vh', 'important');
					appContent.style.setProperty('max-height', '100vh', 'important');
					appContent.style.setProperty('overflow', 'hidden', 'important');
					appContent.style.setProperty('margin', '0', 'important');
					appContent.style.setProperty('padding', '0', 'important');
					appContent.style.setProperty('max-width', 'none', 'important');
					appContent.style.setProperty('width', '100%', 'important');
				}

				var shell = document.querySelector('.gov-public-shell');
				if (shell) {
					shell.style.setProperty('height', '100vh', 'important');
					shell.style.setProperty('min-height', '100vh', 'important');
					shell.style.setProperty('max-height', '100vh', 'important');
					shell.style.setProperty('overflow', 'hidden', 'important');
					shell.style.setProperty('margin', '0', 'important');
					shell.style.setProperty('padding', '0', 'important');
					shell.style.setProperty('display', 'flex', 'important');
					shell.style.setProperty('flex-direction', 'column', 'important');
					shell.style.setProperty('border-radius', '0', 'important');
					shell.style.setProperty('box-shadow', 'none', 'important');
				}

				var main = document.querySelector('.gov-public-main');
				if (main) {
					main.style.setProperty('flex', '1 1 0%', 'important');
					main.style.setProperty('min-height', '0', 'important');
					main.style.setProperty('height', 'auto', 'important');
					main.style.setProperty('padding', '0', 'important');
					main.style.setProperty('margin', '0', 'important');
					main.style.setProperty('max-width', 'none', 'important');
					main.style.setProperty('width', '100%', 'important');
					main.style.setProperty('display', 'flex', 'important');
					main.style.setProperty('flex-direction', 'column', 'important');
				}

				var filesPublicContent = document.getElementById('files-public-content');
				if (filesPublicContent) {
					filesPublicContent.style.setProperty('flex', '1 1 0%', 'important');
					filesPublicContent.style.setProperty('min-height', '0', 'important');
					filesPublicContent.style.setProperty('height', '100%', 'important');
					filesPublicContent.style.setProperty('width', '100%', 'important');
					filesPublicContent.style.setProperty('max-width', 'none', 'important');
					filesPublicContent.style.setProperty('display', 'flex', 'important');
					filesPublicContent.style.setProperty('flex-direction', 'column', 'important');
					filesPublicContent.style.setProperty('margin', '0', 'important');
					filesPublicContent.style.setProperty('padding', '0', 'important');
				}

				var preview = document.getElementById('preview');
				if (preview) {
					preview.style.setProperty('flex', '1 1 0%', 'important');
					preview.style.setProperty('min-height', '0', 'important');
					preview.style.setProperty('height', '100%', 'important');
					preview.style.setProperty('width', '100%', 'important');
					preview.style.setProperty('max-width', 'none', 'important');
					preview.style.setProperty('display', 'flex', 'important');
					preview.style.setProperty('flex-direction', 'column', 'important');
					preview.style.setProperty('margin', '0', 'important');
					preview.style.setProperty('padding', '0', 'important');
				}

				imgframe.style.setProperty('flex', '1 1 0%', 'important');
				imgframe.style.setProperty('min-height', '0', 'important');
				imgframe.style.setProperty('height', '100%', 'important');
				imgframe.style.setProperty('width', '100%', 'important');
				imgframe.style.setProperty('max-width', 'none', 'important');
				imgframe.style.setProperty('display', 'flex', 'important');
				imgframe.style.setProperty('flex-direction', 'column', 'important');
				imgframe.style.setProperty('margin', '0', 'important');
				imgframe.style.setProperty('padding', '0', 'important');

				var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;
				var mobileViewer = document.getElementById('pdf-mobile-viewer');

				if (isMobileDevice && mobileViewer) {
					if (iframe) {
						iframe.style.setProperty('display', 'none', 'important');
					}
					mobileViewer.style.setProperty('display', 'flex', 'important');
				} else if (iframe) {
					iframe.style.setProperty('width', '100%', 'important');
					iframe.style.setProperty('height', '100%', 'important');
					iframe.style.setProperty('min-height', '0', 'important');
					iframe.style.setProperty('flex', '1 1 0%', 'important');
					iframe.style.setProperty('margin', '0 auto', 'important');
					iframe.style.setProperty('border', 'none', 'important');
				}

				var icons = imgframe.querySelectorAll('.publicpreview, img:not(.viewer__file), a');
				for (var j = 0; j < icons.length; j++) {
					if (icons[j] !== iframe && (!mobileViewer || !mobileViewer.contains(icons[j])) && !icons[j].contains(iframe)) {
						icons[j].remove();
					}
				}
			}
		};

		forceFilePreviewStyles();
		document.addEventListener('DOMContentLoaded', forceFilePreviewStyles);
		window.addEventListener('load', forceFilePreviewStyles);
		setInterval(forceFilePreviewStyles, 100);
	})();
</script>
