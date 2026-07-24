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

	function setActiveGalleryImage(gallery, galleryThumb) {
	var mainImage = gallery.querySelector('.cck-product-gallery__main-image');
	var nextSrc = galleryThumb.getAttribute('data-cck-gallery-image');

	if (!mainImage || !nextSrc) {
		return;
	}

	mainImage.src = nextSrc;
	mainImage.removeAttribute('srcset');
	mainImage.removeAttribute('sizes');

	gallery.querySelectorAll('.cck-product-gallery__thumb').forEach(function (thumb) {
		thumb.classList.remove('is-active');
		thumb.setAttribute('aria-pressed', 'false');
	});

	galleryThumb.classList.add('is-active');
	galleryThumb.setAttribute('aria-pressed', 'true');
}

	document.addEventListener('click', function (event) {
	var galleryThumb = event.target.closest('[data-cck-gallery-image]');
	var galleryNav = event.target.closest('[data-cck-gallery-nav]');

	if (galleryThumb) {
		var gallery = galleryThumb.closest('.cck-product-gallery');

		if (gallery) {
			setActiveGalleryImage(gallery, galleryThumb);
		}

		return;
	}

	if (galleryNav) {
		var navGallery = galleryNav.closest('.cck-product-gallery');

		if (!navGallery) {
			return;
		}

		var thumbs = Array.prototype.slice.call(
			navGallery.querySelectorAll('.cck-product-gallery__thumb[data-cck-gallery-image]')
		);

		if (thumbs.length < 2) {
			return;
		}

		var activeIndex = thumbs.findIndex(function (thumb) {
			return thumb.classList.contains('is-active');
		});

		if (activeIndex < 0) {
			activeIndex = 0;
		}

		var direction = galleryNav.getAttribute('data-cck-gallery-nav');
		var nextIndex;

		if ('prev' === direction) {
			nextIndex = (activeIndex - 1 + thumbs.length) % thumbs.length;
		} else {
			nextIndex = (activeIndex + 1) % thumbs.length;
		}

		setActiveGalleryImage(navGallery, thumbs[nextIndex]);
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

	function initProductGalleryZoom() {
	document.querySelectorAll('.cck-product-gallery__main').forEach(function (main) {
		var image = main.querySelector('.cck-product-gallery__main-image');

		if (!image) {
			return;
		}

		main.addEventListener('mousemove', function (event) {
			var rect = main.getBoundingClientRect();
			var x = ((event.clientX - rect.left) / rect.width) * 100;
			var y = ((event.clientY - rect.top) / rect.height) * 100;

			image.style.transformOrigin = x + '% ' + y + '%';
		});

		main.addEventListener('mouseleave', function () {
			image.style.transformOrigin = 'center center';
		});
	});
}

document.addEventListener('DOMContentLoaded', initProductGalleryZoom);

})();
