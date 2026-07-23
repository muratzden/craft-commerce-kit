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
		var galleryThumb = event.target.closest('[data-cck-gallery-image]');

		if (galleryThumb) {
			var gallery = galleryThumb.closest('.cck-product-gallery');
			var mainImage = gallery ? gallery.querySelector('.cck-product-gallery__main-image') : null;
			var nextSrc = galleryThumb.getAttribute('data-cck-gallery-image');

			if (mainImage && nextSrc) {
				mainImage.src = nextSrc;
				mainImage.removeAttribute('srcset');
				mainImage.removeAttribute('sizes');

				gallery.querySelectorAll('.cck-product-gallery__thumb').forEach(function (thumb) {
					thumb.classList.remove('is-active');
				});

				galleryThumb.classList.add('is-active');
			}

			return;
		}

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
