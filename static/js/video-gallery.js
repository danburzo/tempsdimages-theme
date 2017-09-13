(function() {
	var elements = Array.prototype.slice.call(
		document.querySelectorAll('[data-video-embed]')
	);

	var container = document.querySelector('.video-overlay');
	var container__inner = container.querySelector('.video');

	var set_autoplay = function(url) {
		var anchor = document.createElement('a');
		anchor.href = url;
		if (anchor.search) {
			anchor.search += '&autoplay=1';
		} else {
			anchor.search = '?autoplay=1';
		}
		return anchor.href;
	}

	elements.forEach(function(el) {
		el.addEventListener('click', function(e) {
			var html = atob(el.dataset.videoEmbed).replace(/src=\"([^\"]+)\"/, function(match, src) {
				return 'src="' + set_autoplay(src) + '"';
			});
			show_video(html);
			e.stopPropagation();
			e.preventDefault();
		});
	});

	function show_video(html) {
		container__inner.innerHTML = html;
		container.classList.add('visible');
	}

	function hide_video() {
		container__inner.innerHTML = '';
		container.classList.remove('visible');
	}

	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape') {
			hide_video();
		}
	});

	document.addEventListener('click', function(e) {
		if (container.classList.contains('visible')) {
			hide_video();
		}
	});

	container__inner.addEventListener('click', function(e) {
		e.stopPropagation();
	});
})();