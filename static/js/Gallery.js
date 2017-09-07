Array.prototype.slice.call(document.querySelectorAll('.gallery')).map(function(el) {
	return new Galleria(el);
});