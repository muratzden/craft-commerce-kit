(function () {
	function copyText(text, button) {
		function markCopied() {
			var original = button.textContent;

			button.textContent = 'Copied';
			button.classList.add('is-copied');

			window.setTimeout(function () {
				button.textContent = original;
				button.classList.remove('is-copied');
			}, 1600);
		}

		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text).then(markCopied).catch(function () {
				fallbackCopy(text, markCopied);
			});
			return;
		}

		fallbackCopy(text, markCopied);
	}

	function fallbackCopy(text, callback) {
		var textarea = document.createElement('textarea');

		textarea.value = text;
		textarea.setAttribute('readonly', 'readonly');
		textarea.style.position = 'absolute';
		textarea.style.left = '-9999px';
		document.body.appendChild(textarea);
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);
		callback();
	}

	function init() {
		var adminRoot = document.querySelector('.cck-admin-dashboard');
		var previewCanvas = document.querySelector('[data-preview-canvas]');

		if (!adminRoot) {
			adminRoot = null;
		}

		if (previewCanvas) {
			var previewButtons = Array.prototype.slice.call(document.querySelectorAll('.cck-admin-preview-tabs [data-preview-mode]'));

			if (previewButtons.length) {
				var setPreviewMode = function (mode) {
					previewCanvas.classList.remove('cck-preview-desktop', 'cck-preview-tablet', 'cck-preview-mobile');
					previewCanvas.classList.add('cck-preview-' + mode);

					previewButtons.forEach(function (button) {
						var isActive = button.getAttribute('data-preview-mode') === mode;

						button.classList.toggle('is-active', isActive);
						button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
					});
				};

				previewButtons.forEach(function (button) {
					button.addEventListener('click', function () {
						setPreviewMode(button.getAttribute('data-preview-mode'));
					});
				});

				setPreviewMode('desktop');
			}
		}

		if (adminRoot) {
			adminRoot.addEventListener('click', function (event) {
				var button = event.target.closest('.cck-admin-copy');

				if (!button || !adminRoot.contains(button)) {
					return;
				}

				copyText(button.getAttribute('data-cck-copy') || '', button);
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
