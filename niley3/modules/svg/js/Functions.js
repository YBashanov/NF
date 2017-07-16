var SVGNS = "http://www.w3.org/2000/svg";
var XLINKNS = "http://www.w3.org/1999/xlink";
var ROOT = document.documentElement;

if (! Catalog) var Catalog = {};
if (! Rects) var Rects = {};
if (! Circle) var Circle = {};
if (! Images) var Images = {};
if (! Text) var Text = {};
if (! Path) var Path = {};
if (! Polyline) var Polyline = {};
if (! Polygon) var Polygon = {};
if (! Line) var Line = {};
if (! G) var G = {};

//создание изображения
//element - куда вставлять. Если = null, то возвращается созданный элемент image
Images.create = function (attributes, href, element) {
	var images = document.createElementNS (SVGNS, "image");
	if (attributes) {
		for (var val in attributes) {
			images.setAttribute (val, attributes[val]);
		}
	}
	if (href) {
		images.setAttributeNS(XLINKNS, "href", href);
	}
	else {
		alert("Images.create: Нет ссылки на изображение");
		return false;
	}
	if (element) {
		element.appendChild (images);
		return images;
	}
	else return images;
}
//создание паттерна изображения
//element - куда вставлять. Если = null, то возвращается созданный элемент image
Images.createPattern = function (attributes, href, element) {
	var pattern = document.createElementNS (SVGNS, "pattern");
	pattern.setAttribute ("patternUnits", "userSpaceOnUse");
	if (attributes) {
		for (var val in attributes) {
			pattern.setAttribute (val, attributes[val]);
		}
	}
	if (href) {
		//создание изображения
		var image = document.createElementNS (SVGNS, "image");
		image.setAttribute ("x", "0");
		image.setAttribute ("y", "0");
		if (attributes) {
			for (var val in attributes) {
				if (val == "width" || val == "height") {
					image.setAttribute (val, attributes[val]);
				}
			}
		}
		image.setAttributeNS(XLINKNS, "href", href);
	}
	else {
		alert("Images.createPattern: Нет ссылки на изображение");
		return false;
	}
	if (pattern) {
		pattern.appendChild (image);
	}
	else return false;
	
	if (element) {
		element.appendChild (pattern);
		return pattern;
	}
	else return pattern;
}
//создание текстового узла
Text.create = function (attributes, text, element, dx, dy) {
	var textNode = document.createElementNS(SVGNS, "text");
	if (attributes) {
		for (var val in attributes) {
			textNode.setAttribute (val, attributes[val]);
			//чтобы атрибуты не переписывать заново
			if (val == "x" || val == "y") {
				if (dx) {
					var newX = dx + attributes.x;
					textNode.setAttribute ("x", newX);
				}
				if (dy) {
					var newY = dy + attributes.y;
					textNode.setAttribute ("y", newY);
				}
			}
		}
	}
	if (text) {
		var string = document.createTextNode(text);
		textNode.appendChild (string);
	}
	else {
		alert ("Text.create: Нет текста");
		return false;
	}
	if (element) {
		element.appendChild (textNode);
		return textNode;
	}
	else return textNode;
}
//создание прямоугольника
Rects.create = function (attributes, element) {
	var rectNode = document.createElementNS(SVGNS, "rect");
	if (attributes) {
		for (var val in attributes) {
			rectNode.setAttribute (val, attributes[val]);
		}
	}
	if (element) {
		element.appendChild (rectNode);
		return rectNode;
	}
	else return rectNode;
}
//создание эллипса (круга)
Circle.create = function (attributes, element) {
	var circle = document.createElementNS(SVGNS, "circle");
	if (attributes) {
		for (var val in attributes) {
			circle.setAttribute (val, attributes[val]);
		}
	}
	if (element) {
		element.appendChild (circle);
		return circle;
	}
	else return circle;
}
//создание произвольной фигуры
Path.create = function (attributes, element) {
	var path = document.createElementNS(SVGNS, "path");
	if (attributes) {
		for (var val in attributes) {
			path.setAttribute (val, attributes[val]);
		}
	}
	if (element) {
		element.appendChild (path);
		return path;
	}
	else return path;
}
//создание ломаной
Polyline.create = function (attributes, element) {
	var polyline = document.createElementNS(SVGNS, "polyline");
	if (attributes) {
		for (var val in attributes) {
			polyline.setAttribute (val, attributes[val]);
		}
	}
	if (element) {
		element.appendChild (polyline);
		return polyline;
	}
	else return polyline;
}
//создание многоугольника
Polygon.create = function (attributes, element) {
	var polygon = document.createElementNS(SVGNS, "polygon");
	if (attributes) {
		for (var val in attributes) {
			polygon.setAttribute (val, attributes[val]);
		}
	}
	if (element) {
		element.appendChild (polygon);
		return polygon;
	}
	else return polygon;
}
//создание прямой линии
Line.create = function (attributes, element) {
	var line = document.createElementNS(SVGNS, "line");
	if (attributes) {
		for (var val in attributes) {
			line.setAttribute (val, attributes[val]);
		}
	}
	if (element) {
		element.appendChild (line);
		return line;
	}
	else return line;
}
//создание группы с заданным id
G.create = function (id, element) {
	var g = document.createElementNS(SVGNS, "g");
	g.setAttribute ("id", id);
	if (element) {
		element.appendChild (g);
		return g;
	}
	else return g;
}
//удалить элемент
function removeElement (element) {
	if (element) {
		element.parentNode.removeChild(element);
	}
}


//рисование окружности-метки
Circle.createLabel = function (cx, cy, id, object) {
	var g = document.createElementNS(SVGNS, "g");
	g.setAttribute ("id", id);
	
	var attributes = {
		"cx" : cx,
		"cy" : cy,
		"r" : 5,
		"class" : "circle1",
		"id" : id + "_1"
	}
	Circle.create (attributes, g);
	attributes = {
		"cx" : cx,
		"cy" : cy,
		"r" : 4,
		"class" : "circle2",
		"id" : id + "_2"
	}
	Circle.create (attributes, g);
	attributes = {
		"cx" : cx,
		"cy" : cy,
		"r" : 2,
		"class" : "circle1",
		"id" : id + "_3"
	}
	Circle.create (attributes, g);
	attributes = {
		"cx" : cx,
		"cy" : cy,
		"r" : 5,
		"class" : "circle3",
		"id" : id + "_4"
	}
	var returned = Circle.create (attributes, g);
	
	object.appendChild (g);
	return returned;
}