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

	document.addEventListener('click', function (event) {
		var button = event.target.closest('.cck-admin-copy');

		if (!button) {
			return;
		}

		copyText(button.getAttribute('data-cck-copy') || '', button);
	});
})();
