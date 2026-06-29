(function () {
	document.addEventListener('DOMContentLoaded', function () {
		var elements = document.querySelectorAll('.cck-component, .cck-section');

		elements.forEach(function (element) {
			element.classList.add('cck-ready');
		});
	});
})();
