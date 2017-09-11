(function() {
	var toggle_buttons = Array.prototype.slice.call(
		document.querySelectorAll('[data-mobile-toggle]')
	);

	toggle_buttons.forEach(function(button) {
		var target = document.querySelector(button.dataset.mobileToggle);
		if (target) {
			button.addEventListener('click', function(e) {
				target.classList.toggle('visible');
			});
		}
	});
})();