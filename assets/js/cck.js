(function () {
	function closeProductVideo() {
		var modal = document.querySelector('[data-cck-product-video-modal]');
		var frame = modal ? modal.querySelector('[data-cck-product-video-frame]') : null;

		if (frame) {
			frame.src = '';
		}

		if (modal) {
			modal.hidden = true;
		}

		document.body.classList.remove('cck-product-video-open');
	}

	document.addEventListener('DOMContentLoaded', function () {
		var elements = document.querySelectorAll('.cck-component, .cck-section');

		elements.forEach(function (element) {
			element.classList.add('cck-ready');
		});
	});

	document.addEventListener('click', function (event) {
		var trigger = event.target.closest('[data-cck-product-video]');

		if (trigger) {
			var modal = document.querySelector('[data-cck-product-video-modal]');
			var frame = modal ? modal.querySelector('[data-cck-product-video-frame]') : null;

			if (!modal || !frame) {
				return;
			}

			frame.src = trigger.getAttribute('data-cck-product-video') || '';
			modal.hidden = false;
			document.body.classList.add('cck-product-video-open');
			return;
		}

		if (event.target.closest('[data-cck-product-video-close]')) {
			closeProductVideo();
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeProductVideo();
		}
	});
})();
