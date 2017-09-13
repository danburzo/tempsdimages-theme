(function() {
	var toggle_buttons = Array.prototype.slice.call(
		document.querySelectorAll('[data-mobile-toggle]')
	);

	toggle_buttons.forEach(function(button) {
		var target = document.querySelector(button.dataset.mobileToggle);
		if (target) {
			button.addEventListener('click', function(e) {
				target.classList.toggle('visible');
				e.stopPropagation();
				e.preventDefault();
			});
		}
	});

	var click_outside_els = Array.prototype.slice.call(
		document.querySelectorAll('[data-mobile-closeonclickoutside]')
	);

	click_outside_els.forEach(function(el) {
		document.addEventListener('click', function(e) {
			el.classList.remove('visible');
		});

		el.addEventListener('click', function(e) {
			e.stopPropagation();
		});
	})
})();