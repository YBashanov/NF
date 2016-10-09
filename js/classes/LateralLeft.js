function lateralLeft(id) {
	var posLeft = -350;
	var internal_ = 1000;
	var widthWindow = parseInt(document.body.clientWidth);
	var lateral = (widthWindow - internal_)/2;
	var element = document.getElementById(id);
	var styles = Popup.getStyles(element);
	var leftStyle = parseInt(styles.left);
	leftStyle = posLeft + lateral + "px";
	element.style.left = leftStyle;
}