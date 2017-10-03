var Duotone = function(start, end, id) {

	if (start && end) {
		try {
			var start_color = chroma(start);
			var end_color = chroma(end);
		} catch(e) {
			console.error(e);
		}

		if (start_color && end_color && id) {

			var colors = [start_color, end_color].sort(function(a, b) {
				return a.luminance() - b.luminance();
			})

			this.initialize(colors[1].rgb(), colors[0].rgb(), id);
		}
	}
};

Duotone.prototype.initialize = function(start, end, id) {

	var SVGNS = "http://www.w3.org/2000/svg";

	var svg_el = document.createElementNS(SVGNS, "svg");
	var filter_el = document.createElementNS(SVGNS, "filter");
	var feMatrix_el = document.createElementNS(SVGNS, "feColorMatrix");

	svg_el.style.height	= '0';
	svg_el.style.width = '0';
	svg_el.style.display = 'block';

	var values = [
		(start[0] / 256 - end[0] / 256) + " 0 0 0 " + (end[0] / 256),
		(start[1] / 256 - end[1] / 256) + " 0 0 0 " + (end[1] / 256),
		(start[2] / 256 - end[2] / 256) + " 0 0 0 " + (end[2] / 256),
        "0 0 0 1 0"
	].join(" ");

	feMatrix_el.setAttribute('values', values);
	filter_el.setAttribute('id', id);
	filter_el.setAttribute('color-interpolation-filters', 'sRGB');

	filter_el.appendChild(feMatrix_el);
	svg_el.appendChild(filter_el);
	document.body.appendChild(svg_el);
};