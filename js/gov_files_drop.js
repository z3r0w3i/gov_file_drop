/**
 * Gov File Drop - Cổng Tiếp nhận Tài liệu Điện tử
 * Tuân thủ Luật Bảo vệ dữ liệu cá nhân số 91/2025/QH15
 */
(function () {
	'use strict';

	if (window._govFileDropInitialized || window._govClonedScriptActive || document.getElementById('gov-upload-stepper')) {
		return;
	}
	window._govFileDropInitialized = true;

	var ensureViewportMeta = function () {
		var meta = document.querySelector('meta[name="viewport"]');
		if (!meta) {
			meta = document.createElement('meta');
			meta.name = 'viewport';
			document.head.appendChild(meta);
		}
		meta.content = 'width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover';
	};
	ensureViewportMeta();

	window.OCA = window.OCA || {};
	window.OCA.Files = window.OCA.Files || {};
	if (!window.OCA.Files.FileActions) {
		window.OCA.Files.FileActions = function () { };
		window.OCA.Files.FileActions.prototype.registerDefaultActions = function () { };
		window.OCA.Files.FileActions.prototype.merge = function () { };
		window.OCA.Files.FileActions.prototype.getDefaultFileAction = function () { return null; };
	}

	var polyfillJQuery = function (jq) {
		if (!jq || !jq.fn) return;
		if (!jq.fn.avatar) { jq.fn.avatar = function () { return this; }; }
		if (!jq.fn.fileupload) { jq.fn.fileupload = function () { return this; }; }
	};
	polyfillJQuery(window.jQuery);
	polyfillJQuery(window.$);

	var makeFullWidth = function () {
		if (document.documentElement) {
			document.documentElement.style.setProperty('width', '100%', 'important');
			document.documentElement.style.setProperty('max-width', '100%', 'important');
			document.documentElement.style.setProperty('margin', '0', 'important');
			document.documentElement.style.setProperty('padding', '0', 'important');
			document.documentElement.style.setProperty('height', 'auto', 'important');
			document.documentElement.style.setProperty('min-height', '100%', 'important');
			document.documentElement.style.setProperty('overflow-x', 'hidden', 'important');
			document.documentElement.style.setProperty('overflow-y', 'auto', 'important');
			document.documentElement.style.setProperty('touch-action', 'pan-y', 'important');
			document.documentElement.style.setProperty('-webkit-overflow-scrolling', 'touch', 'important');
		}
		if (document.body) {
			document.body.style.setProperty('width', '100%', 'important');
			document.body.style.setProperty('max-width', '100%', 'important');
			document.body.style.setProperty('min-width', '100%', 'important');
			document.body.style.setProperty('margin', '0', 'important');
			document.body.style.setProperty('padding', '0', 'important');
			document.body.style.setProperty('display', 'block', 'important');
			document.body.style.setProperty('height', 'auto', 'important');
			document.body.style.setProperty('min-height', '100vh', 'important');
			document.body.style.setProperty('overflow-x', 'hidden', 'important');
			document.body.style.setProperty('overflow-y', 'visible', 'important');
			document.body.style.setProperty('touch-action', 'pan-y', 'important');
			document.body.style.setProperty('-webkit-overflow-scrolling', 'touch', 'important');
		}
		var shell = document.querySelector('.gov-public-shell');
		if (shell) {
			shell.style.setProperty('height', 'auto', 'important');
			shell.style.setProperty('min-height', '100vh', 'important');
			shell.style.setProperty('overflow-x', 'hidden', 'important');
			shell.style.setProperty('overflow-y', 'visible', 'important');
			shell.style.setProperty('touch-action', 'pan-y', 'important');
			var node = shell.parentElement;
			while (node && node !== document.body && node !== document.documentElement) {
				node.style.setProperty('width', '100%', 'important');
				node.style.setProperty('max-width', '100%', 'important');
				node.style.setProperty('min-width', '100%', 'important');
				node.style.setProperty('height', 'auto', 'important');
				node.style.setProperty('margin', '0', 'important');
				node.style.setProperty('padding', '0', 'important');
				node.style.setProperty('border', 'none', 'important');
				node.style.setProperty('box-shadow', 'none', 'important');
				node.style.setProperty('border-radius', '0', 'important');
				node.style.setProperty('box-sizing', 'border-box', 'important');
				node.style.setProperty('align-self', 'stretch', 'important');
				node.style.setProperty('overflow-x', 'hidden', 'important');
				node.style.setProperty('overflow-y', 'visible', 'important');
				node.style.setProperty('touch-action', 'pan-y', 'important');
				node = node.parentElement;
			}
		}
	};

	var killBanners = function () {
		var selectors = [
			'#header', '#header-vue', 'header.header-main', '#nextcloud-header',
			'.header-right', '.header-left', '#app-navigation', '#app-navigation-vue',
			'.app-navigation', '.app-navigation-menu', '#app-sidebar', '#app-sidebar-vue',
			'#controls', '#filestable', '.breadcrumb',
			'#mobile-app-banner', '.mobile-app-banner', '.smartbanner', '#beta-app-banner',
			'.nextcloud-banner', '.app-banner', '#app-banner'
		];
		selectors.forEach(function (s) {
			document.querySelectorAll(s).forEach(function (el) { el.remove(); });
		});
		var meta = document.querySelector('meta[name="apple-itunes-app"]');
		if (meta) { meta.remove(); }
		document.cookie = "hideAppBanner=1; path=/; max-age=31536000";
		document.cookie = "oc_app_banner_hidden=1; path=/; max-age=31536000";
		makeFullWidth();
	};
	killBanners();
	var kbCount = 0;
	var kbInterval = setInterval(function () {
		killBanners();
		kbCount++;
		if (kbCount >= 10) {
			clearInterval(kbInterval);
		}
	}, 300);

	window._govPendingFiles = window._govPendingFiles || [];
	var pendingFiles = window._govPendingFiles;
	var uploading = false;

	var formatBytes = function (bytes) {
		if (!bytes || bytes <= 0) return '0 B';
		var k = 1024;
		var sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
		var i = Math.floor(Math.log(bytes) / Math.log(k));
		if (i >= sizes.length) i = sizes.length - 1;
		return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
	};

	var escapeHtml = function (str) {
		return String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
	};

	var isTermsChecked = function () {
		var cb = document.getElementById('accept-terms-checkbox-final');
		if (!cb && window.$) {
			cb = $('#accept-terms-checkbox-final')[0];
		}
		return cb ? Boolean(cb.checked) : false;
	};

	var updateConfirmState = function () {
		var btn = document.getElementById('confirm-upload-btn');
		if (!btn) return;
		var checked = isTermsChecked();
		var hasFiles = pendingFiles.length > 0 || (document.querySelectorAll('#drop-pending-files li').length > 0);
		var guidanceBanner = document.getElementById('terms-guidance-banner');
		if (!btn) return;
		var checked = isTermsChecked();
		var hasFiles = pendingFiles.length > 0 || (document.querySelectorAll('#drop-pending-files li').length > 0);
		var canUpload = hasFiles && checked && !uploading;

		if (guidanceBanner) {
			if (hasFiles && !checked) {
				guidanceBanner.style.setProperty('display', 'flex', 'important');
			} else {
				guidanceBanner.style.setProperty('display', 'none', 'important');
			}
		}

		if (canUpload) {
			btn.disabled = false;
			btn.removeAttribute('disabled');
			btn.classList.remove('disabled');
			btn.style.setProperty('opacity', '1', 'important');
			btn.style.setProperty('cursor', 'pointer', 'important');
			btn.style.setProperty('pointer-events', 'auto', 'important');
			btn.style.setProperty('background', 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)', 'important');
			btn.style.setProperty('color', '#ffffff', 'important');
			btn.style.setProperty('box-shadow', '0 4px 14px rgba(2, 132, 199, 0.35)', 'important');
		} else {
			btn.disabled = true;
			btn.setAttribute('disabled', 'disabled');
			btn.classList.add('disabled');
			btn.style.setProperty('opacity', '0.55', 'important');
			btn.style.setProperty('cursor', 'not-allowed', 'important');
			btn.style.setProperty('pointer-events', 'none', 'important');
			btn.style.setProperty('background', '#cbd5e1', 'important');
			btn.style.setProperty('color', '#64748b', 'important');
			btn.style.setProperty('box-shadow', 'none', 'important');
		}
	};

	var updateStepperState = function (step) {
		var s1 = document.getElementById('step-node-1');
		var s2 = document.getElementById('step-node-2');
		var s3 = document.getElementById('step-node-3');
		var l1 = document.getElementById('step-line-1');
		var l2 = document.getElementById('step-line-2');

		[s1, s2, s3].forEach(function (el) {
			if (el) el.classList.remove('gov-step--active', 'gov-step--completed');
		});
		if (l1) l1.classList.remove('active');
		if (l2) l2.classList.remove('active');

		if (step === 1) {
			if (s1) s1.classList.add('gov-step--active');
		} else if (step === 2) {
			if (s1) s1.classList.add('gov-step--completed');
			if (l1) l1.classList.add('active');
			if (s2) s2.classList.add('gov-step--active');
		} else if (step === 3) {
			if (s1) s1.classList.add('gov-step--completed');
			if (s2) s2.classList.add('gov-step--completed');
			if (l1) l1.classList.add('active');
			if (l2) l2.classList.add('active');
			if (s3) s3.classList.add('gov-step--active');
		}
	};

	var getFileExtBadge = function (filename) {
		var parts = (filename || '').split('.');
		var ext = parts.length > 1 ? parts[parts.length - 1].toLowerCase().trim() : 'FILE';
		var bg = '#0284c7';
		var text = ext.toUpperCase();

		if (ext === 'xlsx' || ext === 'xls') {
			bg = '#107c41';
		} else if (ext === 'docx' || ext === 'doc') {
			bg = '#185abd';
		} else if (ext === 'pdf') {
			bg = '#d9381e';
		} else if (ext === 'zip' || ext === 'rar') {
			bg = '#ca5010';
		} else if (ext === 'txt') {
			bg = '#475569';
		}

		return { ext: text, bg: bg };
	};

	var renderPendingFiles = function () {
		var list = document.getElementById('drop-pending-files');
		var countEl = document.getElementById('pending-file-count');
		var confirmSection = document.getElementById('upload-confirm-section');
		var heroSection = document.getElementById('gov-hero-section');
		var uploadBtn = document.getElementById('public-upload-btn');

		if (!list) return;
		list.innerHTML = '';

		if (pendingFiles.length === 0) {
			if (confirmSection) confirmSection.classList.add('hidden');
			if (heroSection) heroSection.classList.remove('hidden');
			if (uploadBtn) uploadBtn.classList.remove('hidden');
			var cb = document.getElementById('accept-terms-checkbox-final');
			if (cb) cb.checked = false;
			updateStepperState(1);
			updateConfirmState();
			return;
		}

		if (confirmSection) confirmSection.classList.remove('hidden');
		if (heroSection) heroSection.classList.add('hidden');
		if (uploadBtn) uploadBtn.classList.add('hidden');
		updateStepperState(2);

		if (countEl) {
			countEl.textContent = 'Đã chọn ' + pendingFiles.length + ' tệp tin';
		}

		pendingFiles.forEach(function (item, idx) {
			var badge = getFileExtBadge(item.name);

			var li = document.createElement('li');
			li.className = 'gov-file-item';
			li.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:12px 16px;margin-bottom:10px;background:#ffffff;border:1px solid #e2e8f0;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,0.03);gap:12px;';

			var leftDiv = document.createElement('div');
			leftDiv.style.cssText = 'display:flex;align-items:center;gap:14px;flex:1;min-width:0;';

			var badgeSpan = document.createElement('span');
			badgeSpan.style.cssText = 'display:inline-flex;align-items:center;justify-content:center;min-width:44px;height:40px;padding:0 8px;border-radius:8px;background:' + badge.bg + ';color:#ffffff;font-size:11.5px;font-weight:800;letter-spacing:0.5px;flex-shrink:0;box-shadow:0 2px 5px rgba(0,0,0,0.1);';
			badgeSpan.textContent = badge.ext;

			var textGroup = document.createElement('div');
			textGroup.style.cssText = 'display:flex;flex-direction:column;gap:3px;flex:1;min-width:0;';

			var nameSpan = document.createElement('span');
			nameSpan.style.cssText = 'font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#0f172a;font-size:14px;';
			nameSpan.textContent = item.name;
			nameSpan.title = item.name;

			var metaSpan = document.createElement('span');
			metaSpan.style.cssText = 'font-size:12px;color:#64748b;display:flex;align-items:center;gap:6px;';
			metaSpan.innerHTML = formatBytes(item.size) + ' <span style="color:#cbd5e1;">•</span> <span style="color:#16a34a;font-weight:600;">✓ Hợp lệ / Sẵn sàng gửi</span>';

			textGroup.appendChild(nameSpan);
			textGroup.appendChild(metaSpan);

			leftDiv.appendChild(badgeSpan);
			leftDiv.appendChild(textGroup);

			var delBtn = document.createElement('button');
			delBtn.type = 'button';
			delBtn.className = 'gov-file-del-btn';
			delBtn.style.cssText = 'display:inline-flex;align-items:center;gap:5px;cursor:pointer;background:#fef2f2;border:1px solid #fee2e2;color:#ef4444;font-size:12px;font-weight:600;padding:6px 12px;border-radius:6px;flex-shrink:0;transition:all 0.2s ease;';
			delBtn.innerHTML = '<span>✕</span> <span>Xóa</span>';
			delBtn.title = 'Xóa tệp tin này';
			delBtn.addEventListener('mouseenter', function () {
				delBtn.style.background = '#fee2e2';
				delBtn.style.borderColor = '#fca5a5';
			});
			delBtn.addEventListener('mouseleave', function () {
				delBtn.style.background = '#fef2f2';
				delBtn.style.borderColor = '#fee2e2';
			});
			delBtn.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				pendingFiles.splice(idx, 1);
				renderPendingFiles();
			});

			li.appendChild(leftDiv);
			li.appendChild(delBtn);
			list.appendChild(li);
		});

		updateConfirmState();
	};

	var ALLOWED_EXTENSIONS = ['doc', 'docx', 'xls', 'xlsx', 'txt', 'pdf', 'rar', 'zip'];
	var DANGEROUS_EXTENSIONS = [
		'php', 'php3', 'php4', 'php5', 'php7', 'phtml', 'phar', 'phps', 'inc',
		'exe', 'bat', 'cmd', 'sh', 'bash', 'bin', 'vbs', 'vbe', 'js', 'jse', 'wsf', 'wsh',
		'jar', 'msi', 'com', 'scr', 'dll', 'sys', 'iso', 'img',
		'jsp', 'jspx', 'asp', 'aspx', 'cer', 'asa', 'asax',
		'py', 'pl', 'cgi', 'htaccess', 'htpasswd', 'config', 'svg', 'html', 'htm', 'xhtml'
	];

	var alertTimeout = null;
	var hideSecurityAlert = function () {
		var box = document.getElementById('gov-security-alert-box');
		if (box) {
			box.classList.remove('active');
			box.classList.add('hidden');
			box.style.setProperty('display', 'none', 'important');
		}
		if (alertTimeout) clearTimeout(alertTimeout);
	};

	var showSecurityAlert = function (message, title) {
		var box = document.getElementById('gov-security-alert-box');
		var body = document.getElementById('gov-security-alert-body');
		var titleEl = document.getElementById('gov-security-alert-title');
		if (!box || !body) {
			alert((title ? title + ':\n' : '') + message);
			return;
		}
		if (title && titleEl) {
			var textSpan = titleEl.querySelector('span');
			if (textSpan) textSpan.textContent = title;
		}
		body.textContent = message;
		box.classList.remove('hidden');
		box.classList.add('active');
		box.style.setProperty('display', 'flex', 'important');
		if (alertTimeout) clearTimeout(alertTimeout);
		alertTimeout = setTimeout(function () {
			hideSecurityAlert();
		}, 7000);
	};

	document.addEventListener('click', function (e) {
		if (e.target && e.target.closest('#gov-security-alert-close')) {
			hideSecurityAlert();
		}
	});

	hideSecurityAlert();

	var validateFileSecurity = function (file) {
		if (!file) return { valid: false, reason: 'Tệp tin không tồn tại' };

		var originalName = file.name || '';
		if (originalName.indexOf('\0') !== -1 || originalName.indexOf('..') !== -1 || originalName.indexOf('/') !== -1 || originalName.indexOf('\\') !== -1) {
			return { valid: false, reason: 'Tên tệp tin vi phạm an toàn thông tin (chứa ký tự cấm hoặc đường dẫn nguy hại).' };
		}

		if (file.size <= 0) {
			return { valid: false, reason: 'Tệp tin rỗng (0 Byte). Hệ thống không tiếp nhận tệp tin không có dung lượng.' };
		}

		var cleanName = originalName.trim();
		var parts = cleanName.split('.');
		if (parts.length < 2) {
			return { valid: false, reason: 'Tệp tin không có định dạng mở rộng. Hệ thống chỉ chấp nhận: .doc, .docx, .xls, .xlsx, .txt, .pdf, .rar, .zip' };
		}

		var ext = parts[parts.length - 1].toLowerCase().trim();

		if (ALLOWED_EXTENSIONS.indexOf(ext) === -1) {
			return {
				valid: false,
				reason: 'Định dạng ".' + ext + '" không được phép tải lên. Hệ thống CHỈ TIẾP NHẬN: .doc, .docx, .xls, .xlsx, .txt, .pdf, .rar, .zip theo chính sách ATTT.'
			};
		}

		for (var i = 0; i < parts.length - 1; i++) {
			var subExt = parts[i].toLowerCase().trim();
			if (DANGEROUS_EXTENSIONS.indexOf(subExt) !== -1) {
				return {
					valid: false,
					reason: 'CẢNH BÁO AN NINH MẠNG: Tệp tin chứa dấu hiệu tấn công mã độc đuôi kép (.' + subExt + '). Hệ thống tự động từ chối để bảo đảm an toàn.'
				};
			}
		}

		return { valid: true };
	};

	var addFiles = function (fileList) {
		if (!fileList || !fileList.length) return;
		var rejectedFiles = [];
		for (var i = 0; i < fileList.length; i++) {
			var file = fileList[i];
			var check = validateFileSecurity(file);
			if (!check.valid) {
				rejectedFiles.push('"' + file.name + '": ' + check.reason);
				continue;
			}
			var name = (file.name || '').replace(/["'#%`]/gm, '');
			var dup = false;
			for (var j = 0; j < pendingFiles.length; j++) {
				if (pendingFiles[j].name === name) { dup = true; break; }
			}
			if (!dup) {
				pendingFiles.push({ file: file, name: name, size: file.size, type: file.type });
			}
		}

		if (rejectedFiles.length > 0) {
			showSecurityAlert(rejectedFiles.join('\n'), 'TỪ CHỐI TỆP TIN THEO CHÍNH SÁCH ATTT');
		}

		renderPendingFiles();
	};

	document.addEventListener('change', function (e) {
		if (e.target && (e.target.id === 'public-upload-input' || e.target.name === 'files[]')) {
			e.stopImmediatePropagation();
			if (e.target.files && e.target.files.length) {
				addFiles(e.target.files);
				try { e.target.value = ''; } catch (err) { }
			}
		}
	}, true);

	var onTermsInteraction = function () {
		setTimeout(updateConfirmState, 0);
		setTimeout(updateConfirmState, 30);
		setTimeout(updateConfirmState, 100);
	};
	['change', 'click', 'input'].forEach(function (evt) {
		document.addEventListener(evt, function (e) {
			if (e.target && (e.target.id === 'accept-terms-checkbox-final' || e.target.closest('#terms-checkbox-label'))) {
				onTermsInteraction();
			}
		}, true);
	});

	document.addEventListener('click', function (e) {
		if (e.target && e.target.closest('#add-more-files-btn')) {
			e.preventDefault();
			e.stopPropagation();
			var input = document.getElementById('public-upload-input');
			if (input) { input.click(); }
		}
	}, true);

	document.addEventListener('click', function (e) {
		if (e.target && e.target.closest('#cancel-upload-btn')) {
			e.preventDefault();
			e.stopPropagation();
			pendingFiles = [];
			renderPendingFiles();
		}
	}, true);

	['dragenter', 'dragover'].forEach(function (evt) {
		document.addEventListener(evt, function (e) { e.preventDefault(); e.stopPropagation(); }, false);
	});
	['dragleave', 'drop'].forEach(function (evt) {
		document.addEventListener(evt, function (e) { e.preventDefault(); e.stopPropagation(); }, false);
	});
	document.addEventListener('drop', function (e) {
		e.preventDefault();
		e.stopPropagation();
		if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
			addFiles(e.dataTransfer.files);
		}
	}, false);

	var startUploadQueue = function () {
		if (!isTermsChecked() || pendingFiles.length === 0 || uploading) {
			return;
		}
		uploading = true;
		updateStepperState(3);
		updateConfirmState();

		var confirmSection = document.getElementById('upload-confirm-section');
		var progressIndicator = document.getElementById('drop-upload-progress-indicator');
		var doneIndicator = document.getElementById('drop-upload-done-indicator');
		var uploadedList = document.getElementById('drop-uploaded-files');
		var uploadBtn = document.getElementById('public-upload-btn');

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

		var uploadNext = function (idx) {
			if (idx >= filesToUpload.length) {
				uploading = false;
				if (progressIndicator) progressIndicator.classList.add('hidden');
				var successSection = document.getElementById('gov-success-section');
				if (successSection) successSection.classList.remove('hidden');
				if (doneIndicator) doneIndicator.classList.remove('hidden');
				updateStepperState(3);
				updateConfirmState();
				return;
			}

			var item = filesToUpload[idx];
			var escName = escapeHtml(item.name);

			var itemLi = document.createElement('li');
			itemLi.style.cssText = 'padding:10px 14px;margin-bottom:8px;background:#fff;border:1px solid #e2e8f0;border-radius:6px;';
			itemLi.innerHTML =
				'<div style="display:flex;justify-content:space-between;margin-bottom:6px;">' +
				'<span style="font-weight:600;font-size:14px;color:#1e293b;">' + escName + '</span>' +
				'<span class="upload-percent" style="font-size:12px;color:#0082c9;font-weight:700;">0%</span>' +
				'</div>' +
				'<progress value="0" max="100" style="width:100%;height:6px;border-radius:3px;"></progress>';
			if (uploadedList) { uploadedList.appendChild(itemLi); }

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

			xhr.upload.onprogress = function (e) {
				if (e.lengthComputable) {
					var percent = Math.round((e.loaded / e.total) * 100);
					if (percentEl) percentEl.textContent = percent + '%';
					if (progEl) progEl.value = percent;
				}
			};

			xhr.onload = function () {
				if (xhr.status >= 200 && xhr.status < 300) {
					itemLi.style.borderColor = '#10b981';
					itemLi.style.background = '#f0fdf4';
					itemLi.innerHTML =
						'<div style="display:flex;align-items:center;justify-content:space-between;">' +
						'<span style="font-weight:600;font-size:14px;color:#065f46;">✓ ' + escName + '</span>' +
						'<span style="font-size:12px;color:#059669;font-weight:600;">' + formatBytes(item.size) + ' - Thành công</span>' +
						'</div>';
				} else {
					itemLi.style.borderColor = '#ef4444';
					itemLi.style.background = '#fef2f2';
					var errText = 'Lỗi tải lên (' + xhr.status + ')';
					if (xhr.status === 409) {
						errText = 'Lỗi 409 Conflict: Thư mục chưa có quyền ghi (Cần chọn quyền "Cho phép tải lên và chỉnh sửa")';
					} else if (xhr.status === 503) {
						errText = 'Lỗi 503: Tính năng mã hóa trên máy chủ chưa sẵn sàng (Encryption not ready)';
					}
					itemLi.innerHTML =
						'<div style="display:flex;align-items:center;justify-content:space-between;">' +
						'<span style="font-weight:600;font-size:14px;color:#991b1b;">✕ ' + escName + '</span>' +
						'<span style="font-size:12px;color:#dc2626;font-weight:600;">' + errText + '</span>' +
						'</div>';
				}
				uploadNext(idx + 1);
			};

			xhr.onerror = function () {
				itemLi.style.borderColor = '#ef4444';
				itemLi.style.background = '#fef2f2';
				itemLi.innerHTML =
					'<div style="display:flex;align-items:center;justify-content:space-between;">' +
					'<span style="font-weight:600;font-size:14px;color:#991b1b;">✕ ' + escName + '</span>' +
					'<span style="font-size:12px;color:#dc2626;font-weight:600;">Lỗi kết nối mạng</span>' +
					'</div>';
				uploadNext(idx + 1);
			};

			xhr.send(item.file);
		};

		uploadNext(0);
	};

	document.addEventListener('click', function (e) {
		if (e.target && e.target.closest('#confirm-upload-btn')) {
			e.preventDefault();
			e.stopPropagation();
			startUploadQueue();
		}
	}, true);

	document.addEventListener('click', function (e) {
		if (e.target && e.target.closest('#reset-upload-btn')) {
			e.preventDefault();
			e.stopPropagation();
			var successSection = document.getElementById('gov-success-section');
			var heroSection = document.getElementById('gov-hero-section');
			var confirmSection = document.getElementById('upload-confirm-section');
			var progressIndicator = document.getElementById('drop-upload-progress-indicator');
			var uploadedList = document.getElementById('drop-uploaded-files');
			var uploadBtn = document.getElementById('public-upload-btn');

			if (successSection) successSection.classList.add('hidden');
			if (confirmSection) confirmSection.classList.add('hidden');
			if (progressIndicator) progressIndicator.classList.add('hidden');
			if (heroSection) heroSection.classList.remove('hidden');
			if (uploadBtn) uploadBtn.classList.remove('hidden');
			if (uploadedList) uploadedList.innerHTML = '';

			pendingFiles = [];
			var cb = document.getElementById('accept-terms-checkbox-final');
			if (cb) cb.checked = false;

			updateStepperState(1);
			updateConfirmState();
		}
	}, true);

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', updateConfirmState);
	} else {
		updateConfirmState();
	}
})();
