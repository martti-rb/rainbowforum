/*----------------------------------------------------------------------------\
|                                Slider 1.02                                  |
|-----------------------------------------------------------------------------|
|                         Created by Erik Arvidsson                           |
|                  (http://webfx.eae.net/contact.html#erik)                   |
|                      For WebFX (http://webfx.eae.net/)                      |
|-----------------------------------------------------------------------------|
| A  slider  control that  degrades  to an  input control  for non  supported |
| browsers.                                                                   |
|-----------------------------------------------------------------------------|
|                Copyright (c) 2002, 2003, 2006 Erik Arvidsson                |
|-----------------------------------------------------------------------------|
| Licensed under the Apache License, Version 2.0 (the "License"); you may not |
| use this file except in compliance with the License.  You may obtain a copy |
| of the License at http://www.apache.org/licenses/LICENSE-2.0                |
| - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - |
| Unless  required  by  applicable law or  agreed  to  in  writing,  software |
| distributed under the License is distributed on an  "AS IS" BASIS,  WITHOUT |
| WARRANTIES OR  CONDITIONS OF ANY KIND,  either express or implied.  See the |
| License  for the  specific language  governing permissions  and limitations |
| under the License.                                                          |
|-----------------------------------------------------------------------------|
| Dependencies: timer.js - an OO abstraction of timers                        |
|               range.js - provides the data model for the slider             |
|               winclassic.css or any other css file describing the look      |
|-----------------------------------------------------------------------------|
| 2002-10-14 | Original version released                                      |
| 2003-03-27 | Added a test in the constructor for missing oElement arg       |
| 2003-11-27 | Only use mousewheel when focused                               |
| 2006-05-28 | Changed license to Apache Software License 2.0.                |
|-----------------------------------------------------------------------------|
| Created 2002-10-14 | All changes are in the log above. | Updated 2006-05-28 |
\----------------------------------------------------------------------------*/

Slider.isSupported = typeof document.createElement != "undefined" &&
	typeof document.documentElement != "undefined" &&
	typeof document.documentElement.offsetWidth == "number";



function Slider(oElement, oInput, sOrientation) {
	if (!oElement) return;
	this._orientation = sOrientation || "horizontal";
	this._range = new Range();
	this._range.setExtent(0);
	this._blockIncrement = 10;
	this._unitIncrement = 1;
	this._timer = new Timer(100);


	if (Slider.isSupported && oElement) {

		this.document = oElement.ownerDocument || oElement.document;

		this.element = oElement;
		this.element.slider = this;
		this.element.unselectable = "on";

		// add class name tag to class name
		this.element.className = this._orientation + " " + this.classNameTag + " " + this.element.className;

		// create line
		this.line = this.document.createElement("DIV");
		this.line.className = "line";
		this.line.unselectable = "on";
		this.line.appendChild(this.document.createElement("DIV"));
		this.element.appendChild(this.line);

		// create handle
		this.handle = this.document.createElement("DIV");
		this.handle.className = "handle";
		this.handle.unselectable = "on";
		this.handle.appendChild(this.document.createElement("DIV"));
		this.handle.firstChild.appendChild(
			this.document.createTextNode(String.fromCharCode(160)));
		this.element.appendChild(this.handle);
	}

	this.input = oInput;

	// events
	var oThis = this;
	this._range.onchange = function () {
		oThis.recalculate();
		if (typeof oThis.onchange == "function")
			oThis.onchange();
	};

	if (Slider.isSupported && oElement) {
		this.element.onfocus		= Slider.eventHandlers.onfocus;
		this.element.onblur			= Slider.eventHandlers.onblur;
		this.element.onmousedown	= Slider.eventHandlers.onmousedown;
		this.element.onmouseover	= Slider.eventHandlers.onmouseover;
		this.element.onmouseout		= Slider.eventHandlers.onmouseout;
		this.element.onkeydown		= Slider.eventHandlers.onkeydown;
		this.element.onkeypress		= Slider.eventHandlers.onkeypress;
		this.element.onmousewheel	= Slider.eventHandlers.onmousewheel;
		this.handle.onselectstart	=
		this.element.onselectstart	= function () { return false; };

		this._timer.ontimer = function () {
			oThis.ontimer();
		};

		// extra recalculate for ie
		window.setTimeout(function() {
			oThis.recalculate();
		}, 1);
	}
	else {
		this.input.onchange = function (e) {
			oThis.setValue(oThis.input.value);
		};
	}
}

Slider.eventHandlers = {

	// helpers to make events a bit easier
	getEvent:	function (e, el) {
		if (!e) {
			if (el)
				e = el.document.parentWindow.event;
			else
				e = window.event;
		}
		if (!e.srcElement) {
			var el = e.target;
			while (el != null && el.nodeType != 1)
				el = el.parentNode;
			e.srcElement = el;
		}
		if (typeof e.offsetX == "undefined") {
			e.offsetX = e.layerX;
			e.offsetY = e.layerY;
		}

		return e;
	},

	getDocument:	function (e) {
		if (e.target)
			return e.target.ownerDocument;
		return e.srcElement.document;
	},

	getSlider:	function (e) {
		var el = e.target || e.srcElement;
		while (el != null && el.slider == null)	{
			el = el.parentNode;
		}
		if (el)
			return el.slider;
		return null;
	},

	getLine:	function (e) {
		var el = e.target || e.srcElement;
		while (el != null && el.className != "line")	{
			el = el.parentNode;
		}
		return el;
	},

	getHandle:	function (e) {
		var el = e.target || e.srcElement;
		var re = /handle/;
		while (el != null && !re.test(el.className))	{
			el = el.parentNode;
		}
		return el;
	},
	// end helpers

	onfocus:	function (e) {
		var s = this.slider;
		s._focused = true;
		s.handle.className = "handle hover";
	},

	onblur:	function (e) {
		var s = this.slider
		s._focused = false;
		s.handle.className = "handle";
	},

	onmouseover:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		var s = this.slider;
		if (e.srcElement == s.handle)
			s.handle.className = "handle hover";
	},

	onmouseout:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		var s = this.slider;
		if (e.srcElement == s.handle && !s._focused)
			s.handle.className = "handle";
	},

	onmousedown:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		var s = this.slider;
		if (s.element.focus)
			s.element.focus();

		Slider._currentInstance = s;
		var doc = s.document;

		if (doc.addEventListener) {
			doc.addEventListener("mousemove", Slider.eventHandlers.onmousemove, true);
			doc.addEventListener("mouseup", Slider.eventHandlers.onmouseup, true);
		}
		else if (doc.attachEvent) {
			doc.attachEvent("onmousemove", Slider.eventHandlers.onmousemove);
			doc.attachEvent("onmouseup", Slider.eventHandlers.onmouseup);
			doc.attachEvent("onlosecapture", Slider.eventHandlers.onmouseup);
			s.element.setCapture();
		}

		if (Slider.eventHandlers.getHandle(e)) {	// start drag
			Slider._sliderDragData = {
				screenX:	e.screenX,
				screenY:	e.screenY,
				dx:			e.screenX - s.handle.offsetLeft,
				dy:			e.screenY - s.handle.offsetTop,
				startValue:	s.getValue(),
				slider:		s
			};
		}
		else {
			var lineEl = Slider.eventHandlers.getLine(e);
			s._mouseX = e.offsetX + (lineEl ? s.line.offsetLeft : 0);
			s._mouseY = e.offsetY + (lineEl ? s.line.offsetTop : 0);
			s._increasing = null;
			s.ontimer();
		}
	},

	onmousemove:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);

		if (Slider._sliderDragData) {	// drag
			var s = Slider._sliderDragData.slider;

			var boundSize = s.getMaximum() - s.getMinimum();
			var size, pos, reset;

			if (s._orientation == "horizontal") {
				size = s.element.offsetWidth - s.handle.offsetWidth;
				pos = e.screenX - Slider._sliderDragData.dx;
				reset = Math.abs(e.screenY - Slider._sliderDragData.screenY) > 100;
			}
			else {
				size = s.element.offsetHeight - s.handle.offsetHeight;
				pos = s.element.offsetHeight - s.handle.offsetHeight -
					(e.screenY - Slider._sliderDragData.dy);
				reset = Math.abs(e.screenX - Slider._sliderDragData.screenX) > 100;
			}
			s.setValue(reset ? Slider._sliderDragData.startValue :
						s.getMinimum() + boundSize * pos / size);
			return false;
		}
		else {
			var s = Slider._currentInstance;
			if (s != null) {
				var lineEl = Slider.eventHandlers.getLine(e);
				s._mouseX = e.offsetX + (lineEl ? s.line.offsetLeft : 0);
				s._mouseY = e.offsetY + (lineEl ? s.line.offsetTop : 0);
			}
		}

	},

	onmouseup:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		var s = Slider._currentInstance;
		var doc = s.document;
		if (doc.removeEventListener) {
			doc.removeEventListener("mousemove", Slider.eventHandlers.onmousemove, true);
			doc.removeEventListener("mouseup", Slider.eventHandlers.onmouseup, true);
		}
		else if (doc.detachEvent) {
			doc.detachEvent("onmousemove", Slider.eventHandlers.onmousemove);
			doc.detachEvent("onmouseup", Slider.eventHandlers.onmouseup);
			doc.detachEvent("onlosecapture", Slider.eventHandlers.onmouseup);
			s.element.releaseCapture();
		}

		if (Slider._sliderDragData) {	// end drag
			Slider._sliderDragData = null;
		}
		else {
			s._timer.stop();
			s._increasing = null;
		}
		Slider._currentInstance = null;
	},

	onkeydown:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		//var s = Slider.eventHandlers.getSlider(e);
		var s = this.slider;
		var kc = e.keyCode;
		switch (kc) {
			case 33:	// page up
				s.setValue(s.getValue() + s.getBlockIncrement());
				break;
			case 34:	// page down
				s.setValue(s.getValue() - s.getBlockIncrement());
				break;
			case 35:	// end
				s.setValue(s.getOrientation() == "horizontal" ?
					s.getMaximum() :
					s.getMinimum());
				break;
			case 36:	// home
				s.setValue(s.getOrientation() == "horizontal" ?
					s.getMinimum() :
					s.getMaximum());
				break;
			case 38:	// up
			case 39:	// right
				s.setValue(s.getValue() + s.getUnitIncrement());
				break;

			case 37:	// left
			case 40:	// down
				s.setValue(s.getValue() - s.getUnitIncrement());
				break;
		}

		if (kc >= 33 && kc <= 40) {
			return false;
		}
	},

	onkeypress:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		var kc = e.keyCode;
		if (kc >= 33 && kc <= 40) {
			return false;
		}
	},

	onmousewheel:	function (e) {
		e = Slider.eventHandlers.getEvent(e, this);
		var s = this.slider;
		if (s._focused) {
			s.setValue(s.getValue() + e.wheelDelta / 120 * s.getUnitIncrement());
			// windows inverts this on horizontal sliders. That does not
			// make sense to me
			return false;
		}
	}
};



Slider.prototype.classNameTag = "dynamic-slider-control",

Slider.prototype.setValue = function (v) {
	this._range.setValue(v);
	this.input.value = this.getValue();
};


Slider.prototype.setValueFromValue = function (sender, maximum) 
{
	var keep_value = sender.value;
	keep_value = (keep_value > maximum) ? maximum : keep_value;
	keep_value = (keep_value < 0) ? 0 : keep_value;	
	this._range.setValue(keep_value);
	this.input.value = this.getValue();
	sender.value = keep_value;
};


Slider.prototype.getValue = function () {
	return this._range.getValue();
};

Slider.prototype.setMinimum = function (v) {
	this._range.setMinimum(v);
	this.input.value = this.getValue();
};

Slider.prototype.getMinimum = function () {
	return this._range.getMinimum();
};

Slider.prototype.setMaximum = function (v) {
	this._range.setMaximum(v);
	this.input.value = this.getValue();
};

Slider.prototype.getMaximum = function () {
	return this._range.getMaximum();
};

Slider.prototype.setUnitIncrement = function (v) {
	this._unitIncrement = v;
};

Slider.prototype.getUnitIncrement = function () {
	return this._unitIncrement;
};

Slider.prototype.setBlockIncrement = function (v) {
	this._blockIncrement = v;
};

Slider.prototype.getBlockIncrement = function () {
	return this._blockIncrement;
};

Slider.prototype.getOrientation = function () {
	return this._orientation;
};

Slider.prototype.setOrientation = function (sOrientation) {
	if (sOrientation != this._orientation) {
		if (Slider.isSupported && this.element) {
			// add class name tag to class name
			this.element.className = this.element.className.replace(this._orientation,
									sOrientation);
		}
		this._orientation = sOrientation;
		this.recalculate();

	}
};

Slider.prototype.recalculate = function() {
	if (!Slider.isSupported || !this.element) return;

	var w = this.element.offsetWidth;
	var h = this.element.offsetHeight;
	var hw = this.handle.offsetWidth;
	var hh = this.handle.offsetHeight;
	var lw = this.line.offsetWidth;
	var lh = this.line.offsetHeight;

	// this assumes a border-box layout

	if (this._orientation == "horizontal") {
		this.handle.style.left = (w - hw) * (this.getValue() - this.getMinimum()) /
			(this.getMaximum() - this.getMinimum()) + "px";
		this.handle.style.top = (h - hh) / 2 + "px";

		this.line.style.top = (h - lh) / 2 + "px";
		this.line.style.left = hw / 2 + "px";
		//this.line.style.right = hw / 2 + "px";
		this.line.style.width = Math.max(0, w - hw - 2)+ "px";
		this.line.firstChild.style.width = Math.max(0, w - hw - 4)+ "px";
	}
	else {
		this.handle.style.left = (w - hw) / 2 + "px";
		this.handle.style.top = h - hh - (h - hh) * (this.getValue() - this.getMinimum()) /
			(this.getMaximum() - this.getMinimum()) + "px";

		this.line.style.left = (w - lw) / 2 + "px";
		this.line.style.top = hh / 2 + "px";
		this.line.style.height = Math.max(0, h - hh - 2) + "px";	//hard coded border width
		//this.line.style.bottom = hh / 2 + "px";
		this.line.firstChild.style.height = Math.max(0, h - hh - 4) + "px";	//hard coded border width
	}
};

Slider.prototype.ontimer = function () {
	var hw = this.handle.offsetWidth;
	var hh = this.handle.offsetHeight;
	var hl = this.handle.offsetLeft;
	var ht = this.handle.offsetTop;

	if (this._orientation == "horizontal") {
		if (this._mouseX > hl + hw &&
			(this._increasing == null || this._increasing)) {
			this.setValue(this.getValue() + this.getBlockIncrement());
			this._increasing = true;
		}
		else if (this._mouseX < hl &&
			(this._increasing == null || !this._increasing)) {
			this.setValue(this.getValue() - this.getBlockIncrement());
			this._increasing = false;
		}
	}
	else {
		if (this._mouseY > ht + hh &&
			(this._increasing == null || !this._increasing)) {
			this.setValue(this.getValue() - this.getBlockIncrement());
			this._increasing = false;
		}
		else if (this._mouseY < ht &&
			(this._increasing == null || this._increasing)) {
			this.setValue(this.getValue() + this.getBlockIncrement());
			this._increasing = true;
		}
	}

	this._timer.start();
};

/* lightflower mod start */



Slider.prototype.setSCG = function(oSCG) 
{
	this._SCG = oSCG;
};

Slider.prototype.getSCG = function () 
{
	return this._SCG;
};

/***********************/




function CCanvas(oElement, oSlider)
{
	this.element = oElement;
	
	this._height = 110;
	this._width = 640;
	this._xOffset = 8;
	
	this._lines_num = 8;
	
	this.slider = oSlider;
	
	this.color_slider_groups = new Array();
}

CCanvas.prototype.drawLines = function () 
{
	var ctx = this.element.getContext("2d");	

	ctx.strokeStyle = "white";		
	ctx.lineWidth = 1;
	
	var step = Math.floor(this._width / this._lines_num);
		
	for (var x = 0; x < this._lines_num; x++)
	{
		ctx.beginPath();
		ctx.moveTo(x * step + this._xOffset, 0);			
		ctx.lineTo(x * step + this._xOffset, this._height - 1);
		ctx.stroke();  
	}	
};


CCanvas.prototype.setWidth = function (iWidth) {
	this._width = iWidth;
};

CCanvas.prototype.getWidth = function () {
	return this._width;
};

CCanvas.prototype.setHeight = function (iHeight) {
	this._heigth = iHeight;
};

CCanvas.prototype.getHeight = function () {
	return this._height;
};

CCanvas.prototype.setXOffset = function (ixOffset) {
	this._xOffset = ixOffset;
};

CCanvas.prototype.getXOffset = function () {
	return this._xOffset;
};

CCanvas.prototype.addColorSliderGroup = function(oCSG)
{
	this.color_slider_groups.push(oCSG);
};


CCanvas.prototype.setCBarEdit = function(oCBar)
{
	this._CBar_edit = oCBar;

	for (var i = 0; i < this.color_slider_groups.length; i++)
	{
		var csg = this.color_slider_groups[i];
		var segment = this._CBar_edit.segments[i];

		csg.setIndex(i);
		csg.setHexColor(segment.getHexColor());	
	}
};


CCanvas.prototype.csgChange = function(oCSG)
{
	this._CBar_edit.segments[oCSG.getIndex()].changeColor(oCSG.red_value, oCSG.green_value, oCSG.blue_value);
};

/**********************/

function CBar(oCCanvas, oEdit, oBg)
{
	this.canvas = oCCanvas.element;
	this._parent = oCCanvas;

	this._yOffset = 0;
	this._height = 20;
	
	this.edit = oEdit;
	
	var hcs_id = (oEdit) ? 'hcs' + this.edit.value : 'hcs0';	
	this.ohcs = document.getElementById(hcs_id);

	this.bg = (oBg) ? oBg : false;
}

CBar.prototype.setHeight = function (iHeight) {
	this._height = iHeight;
};

CBar.prototype.getHeight = function () {
	return this._height;
};

CBar.prototype.setYOffset = function (iyOffset) {
	this._yOffset = iyOffset;
};

CBar.prototype.getYOffset = function () {
	return this._yOffset;
};

CBar.prototype.createSegments = function (iColorAry) 
{
	var ohcs_string = this.ohcs.value.substring(2);

	this.segment_num = Math.floor(ohcs_string.length / 6);
	this.segments = new Array(this.segment_num);
	this.segment_width = Math.floor(this._parent._width / this.segment_num);
	
	for (var i = 0; i < this.segment_num; i++)
	{
		this.segments[i] = new CSegment(this, i);
		var ci = i * 6;
		var red = parseInt(ohcs_string.substr(ci, 2), 16);
		var green = parseInt(ohcs_string.substr(ci + 2, 2), 16);
		var blue = parseInt(ohcs_string.substr(ci + 4, 2), 16);		
		this.segments[i].setColor(red, green, blue);	
	}

	for (var i = 0; i < this.segment_num; i++)
	{
		this.segments[i].setNextAndPrevious();	
	}	
};

CBar.prototype.change_edit = function(strEdit)
{	

	this.edit = document.getElementById(strEdit);
	var hcs_id = (strEdit) ? 'hcs' + this.edit.value : 'hcs0';
	this.ohcs = document.getElementById(hcs_id);
	
	var ohcs_string = this.ohcs.value.substring(2);

	for (var i = 0; i < this.segment_num; i++)
	{
		var ci = i * 6;
		var red = parseInt(ohcs_string.substr(ci, 2), 16);
		var green = parseInt(ohcs_string.substr(ci + 2, 2), 16);
		var blue = parseInt(ohcs_string.substr(ci + 4, 2), 16);		
		this.segments[i].setColor(red, green, blue);		
	}
	
	this.draw();
}

CBar.prototype.draw = function () 
{
	var ctx = this.canvas.getContext("2d");	
	var grad = ctx.createLinearGradient(this._parent._xOffset, 0, this._parent._xOffset + this._parent._width, 0);

	for (var i = 0; i < this.segment_num; i++)
	{	
		grad.addColorStop(i / (this.segment_num), this.segments[i].getRGBColor());	
	}
		
	ctx.fillStyle = grad;	
	ctx.moveTo(0, 0);	
	ctx.fillRect(this._parent._xOffset, this._yOffset, this._parent._width, this._height);	
};

CBar.prototype.getHexColor = function (value) 
{
	var f1 = (value % this.segment_width) / this.segment_width;
	var f2 = (this.segment_width - (value % this.segment_width)) / this.segment_width;
	var segment_index = Math.floor(value/this.segment_width);
	
	segment_index = (segment_index < this.segment_num) ? segment_index : this.segment_num - 1;
	
	var base_segment = this.segments[segment_index];

	var color_1 = Array(3);
	var color_2 = Array(3);
	
	color_1 = base_segment.getColor();
	
	color_2 = (base_segment._last) ? color_1 : base_segment._next.getColor();
	
	var red = Math.floor((color_1[0] * f2) + (color_2[0] * f1));           
	var green = Math.floor((color_1[1] * f2) + (color_2[1] * f1)); 	
	var blue = Math.floor((color_1[2] * f2) + (color_2[2] * f1)); 	

	return (to_hex(red) + to_hex(green) + to_hex(blue));
};

CBar.prototype.getColor = function (value) 
{
	var f1 = (value % this.segment_width) / this.segment_width;
	var f2 = (this.segment_width - (value % this.segment_width)) / this.segment_width;
	var segment_index = Math.floor(value/this.segment_width);
	
	segment_index = (segment_index < this.segment_num) ? segment_index : this.segment_num - 1;
	
	var base_segment = this.segments[segment_index];

	var color_1 = Array(3);
	var color_2 = Array(3);
	
	color_1 = base_segment.getColor();
	
	color_2 = (base_segment._last) ? color_1 : base_segment._next.getColor();
	
	var red = Math.floor((color_1[0] * f2) + (color_2[0] * f1));           
	var green = Math.floor((color_1[1] * f2) + (color_2[1] * f1)); 	
	var blue = Math.floor((color_1[2] * f2) + (color_2[2] * f1)); 	

	return ([red, green, blue]);
};


/*******************************/

function CSegment(oCBar, index)
{
	this._parent = oCBar;
	this._index = index;
	this._first = false;
	this._last = false;
	
	if (this._index == 0)
	{
		this._first = true;
	}
	
	if (this._index == (this._parent.segment_num - 1))
	{
		this._last = true; 
	}	
}

CSegment.prototype.setNextAndPrevious = function () 
{
	if (this._first)
	{
		this._previous = null;
		this._next = this._parent.segments[this._index + 1];		
	}
	else if (this._last)
	{
		this._previous = this._parent.segments[this._index - 1];
		this._next = null;	
	}
	else
	{
		this._previous = this._parent.segments[this._index - 1];
		this._next = this._parent.segments[this._index + 1];	
	}

};



CSegment.prototype.setColor = function (red, green, blue) 
{
	this.red = red;
	this.green = green;
	this.blue = blue;
};

CSegment.prototype.getColor = function () 
{
	return [this.red, this.green, this.blue]; 	
};

CSegment.prototype.getRGBColor = function () 
{
	return 'rgb('+this.red+', '+this.green+', '+this.blue+')'; 	
};

CSegment.prototype.getHexColor = function ()
{
	return to_hex(this.red) + to_hex(this.green) + to_hex(this.blue);
}


CSegment.prototype.changeColor = function (red, green, blue)
{
	this.red = red;
	this.green = green;
	this.blue = blue;

	var ctx = this._parent.canvas.getContext("2d");	

	if (this._first)
	{
		var grad = ctx.createLinearGradient(this._parent._parent._xOffset, 0, 
			this._parent.segment_width + this._parent._parent._xOffset, 0);
		grad.addColorStop(0, this.getRGBColor());
		grad.addColorStop(1, this._next.getRGBColor());

		ctx.fillStyle = grad;	
		ctx.moveTo(0, 0);	
		ctx.fillRect(this._parent._parent._xOffset, 
			this._parent._yOffset, 
			this._parent.segment_width, 
			this._parent._height);			
	}
	else if (this._last)
	{
		grad = ctx.createLinearGradient(this._parent._parent._xOffset + (this._parent.segment_width * (this._index - 1)), 0, 
			this._parent._parent._xOffset + (this._parent.segment_width * (this._index)), 1);
		grad.addColorStop(0, this._previous.getRGBColor());
		grad.addColorStop(1, this.getRGBColor());

		ctx.fillStyle = grad;	
		ctx.moveTo(0, 0);	
		ctx.fillRect(this._parent._parent._xOffset + (this._parent.segment_width * (this._index - 1)), 
			this._parent._yOffset, 
			this._parent.segment_width * 2, 
			this._parent._height);				
	}
	else
	{
		grad = ctx.createLinearGradient(this._parent._parent._xOffset + (this._parent.segment_width * (this._index - 1)), 0, 
			this._parent._parent._xOffset + (this._parent.segment_width * (this._index + 1)), 0);	
		grad.addColorStop(0, this._previous.getRGBColor());	
		grad.addColorStop(1/2, this.getRGBColor());
		grad.addColorStop(2/2, this._next.getRGBColor());	
		
		ctx.fillStyle = grad;	
		ctx.moveTo(0, 0);	
		ctx.fillRect(this._parent._parent._xOffset + (this._parent.segment_width * (this._index - 1)), 
			this._parent._yOffset, 
			this._parent.segment_width * 2, 
			this._parent._height);			
	}	
};


/***************************************/


function to_hex(n) 
{
	n = parseInt(n,10);
	if (isNaN(n)) return '00';
	n = Math.max(0,Math.min(n,255));
	return '0123456789ABCDEF'.charAt((n-n%16)/16)
	  + '0123456789ABCDEF'.charAt(n%16); 
}

/************************************/

function ColorSliderGroup(oElement, sColor)  
{
	this.segment = oSegment;

	this.red_value = parseInt(sColor.substring(0, 2), 16);
	this.green_value = parseInt(sColor.substring(2, 4), 16);
	this.blue_value = parseInt(sColor.substring(4, 6), 16);


	this.is_dark = ((this.red_value + this.green_value + this.blue_value) < 381); 

	this.document = oElement.ownerDocument || oElement.document;
	this.element = oElement;
	

	
	// 
	
	this.float80_div = this.document.createElement("DIV");
	this.float80_div.style.cssFloat = 'left';
	this.float80_div.width = 80;
	
	this.csg_div = this.document.createElement("DIV");
	this.csg_div.style.cssFloat = 'left';
	this.csg_div.style.width = '80px';
	this.csg_div.className = 'color-slider-group';
	
	
	//
	
	this.overall_div = this.document.createElement("DIV");
	this.overall_div.style.cssFloat = 'left';
	this.overall_div.unselectable = "on";
	
	//
	
	this.input = this.document.createElement("INPUT");
	this.input.className = 'color-slider-group-input';
	this.input.name = 'color';
	this.input.type = 'text';
	this.input.size = 6;
	this.input.maxLength = 6;
	this.input._parent = this;
	
	this.input.value = sColor;
	this.input.style.backgroundColor = '#' + sColor;
	
	if (this.is_dark)
	{
		this.input.style.color = 'white';
	}
	else
	{
		this.input.style.color = 'black';	
	}
	
	this.input.onchange = ColorSliderGroup_input_change;

	//
	
	this.slider_div = this.document.createElement("DIV");

	// red
	
	this.red_slider_div = this.document.createElement("DIV");
	this.red_slider_div.style.cssFloat = 'left';		
	
	this.red_slider = this.document.createElement("DIV");
	this.red_slider.className = 'slider';		
	
	this.red_slider_input = this.document.createElement("INPUT");
	this.red_slider_input.className = 'slider-input';

	this.red_slider.appendChild(this.red_slider_input);
	this.red_slider_div.appendChild(this.red_slider);		
	this.slider_div.appendChild(this.red_slider_div);

	// green
	
	this.green_slider_div = this.document.createElement("DIV");
	this.green_slider_div.style.cssFloat = 'left';		
	
	this.green_slider = this.document.createElement("DIV");
	this.green_slider.className = 'slider';		
	
	this.green_slider_input = this.document.createElement("INPUT");
	this.green_slider_input.className = 'slider-input';

	this.green_slider.appendChild(this.green_slider_input);
	this.green_slider_div.appendChild(this.green_slider);		
	this.slider_div.appendChild(this.green_slider_div);		
	
	// blue

	this.blue_slider_div = this.document.createElement("DIV");
	this.blue_slider_div.style.cssFloat = 'left';		
	
	this.blue_slider = this.document.createElement("DIV");
	this.blue_slider.className = 'slider';		
	
	this.blue_slider_input = this.document.createElement("INPUT");
	this.blue_slider_input.className = 'slider-input';

	this.blue_slider.appendChild(this.blue_slider_input);
	this.blue_slider_div.appendChild(this.blue_slider);		
	this.slider_div.appendChild(this.blue_slider_div);

	// 
	
	this.overall_div.appendChild(this.input);
	this.overall_div.appendChild(this.slider_div);
	
	this.csg_div.appendChild(this.overall_div);
	this.float80_div.appendChild(this.csg_div);
	
	this.element.appendChild(this.float80_div);

	this.red = new Slider(this.red_slider , this.red_slider_input , 'red');
	this.green = new Slider(this.green_slider , this.green_slider_input , 'green');
	this.blue = new Slider(this.blue_slider , this.blue_slider_input , 'blue');		
	
	this.red.setMaximum(255);
	this.green.setMaximum(255);		
	this.blue.setMaximum(255);		
	
	this.red.setValue(this.red_value);
	this.green.setValue(this.green_value);
	this.blue.setValue(this.blue_value); 
		
	this.red.setSCG(this);
	this.green.setSCG(this);
	this.blue.setSCG(this);
		
	this.red.onchange = ColorSliderGroup_change; 
	this.green.onchange = ColorSliderGroup_change;
	this.blue.onchange = ColorSliderGroup_change;
}


function ColorSliderGroup_change(e)
{
	var SCG = this.getSCG();
	var sColor = SCG.input.value;
	CSG._CBar_Edit
	
	if (this._orientation == 'red')
	{
		if (SCG.red_value != this.getValue())
		{
			SCG.red_value = this.getValue();
			var hex = to_hex(SCG.red_value);
			sColor =  hex + sColor.substring(2,6);
			var v = CSG._CBar_Edit.ohcs.value;
			CSG._CBar_Edit.ohcs.value = '1' + v.substring(1, 2 + 
		} 
	}
	else if (this._orientation == 'green')
	{
		if (SCG.green_value != this.getValue())
		{
			SCG.green_value = this.getValue();
			hex = to_hex(SCG.green_value);
			sColor = sColor.substring(0, 2) + hex + sColor.substring(4,6);
		}	
	}
	else
	{
		if (SCG.blue_value != this.getValue())
		{	
			SCG.blue_value = this.getValue();
			hex = to_hex(SCG.blue_value);
			sColor = sColor.substring(0, 4) + hex;

			
		}		
	}
	
	var lightness = SCG.red_value + SCG.green_value + SCG.blue_value;
	
	if (SCG.is_dark)
	{
		if (lightness > 381)
		{
			SCG.input.style.color = 'black';
			SCG.is_dark = false; 
		}
	}
	else
	{
		if (lightness < 382)
		{
			SCG.input.style.color = 'white';
			SCG.is_dark = true; 
		}	
	}
	
	SCG.input.value = sColor;
	SCG.input.style.backgroundColor = '#' + sColor;

	can.csgChange(SCG);	
};


function ColorSliderGroup_input_change(e)
{
	var SCG = this._parent;
	var HexColor = /^([0-9a-f](6))$/i;
	
	SCG.red_value = parseInt(this.value.substring(0, 2), 16);
	SCG.green_value = parseInt(this.value.substring(2, 4), 16);
	SCG.blue_value = parseInt(this.value.substring(4, 6), 16);
	
	SCG.red.setValue(SCG.red_value);
	SCG.green.setValue(SCG.green_value);	
	SCG.blue.setValue(SCG.blue_value);	
};

ColorSliderGroup.prototype.setHexColor = function (hexColor) 
{
	this.red_value = parseInt(hexColor.substring(0, 2), 16);
	this.green_value = parseInt(hexColor.substring(2, 4), 16);
	this.blue_value = parseInt(hexColor.substring(4, 6), 16);

	var lightness = this.red_value + this.green_value + this.blue_value;
	
	if (this.is_dark)
	{
		if (lightness > 381)
		{
			this.input.style.color = 'black';
			this.is_dark = false; 
		}
	}
	else
	{
		if (lightness < 382)
		{
			this.input.style.color = 'white';
			this.is_dark = true; 
		}	
	}
	
	this.input.value = hexColor;

	this.red.setValue(this.red_value);
	this.green.setValue(this.green_value);	
	this.blue.setValue(this.blue_value);	
};

ColorSliderGroup.prototype.setIndex = function (index) 
{
	this._index = index;
};

ColorSliderGroup.prototype.getIndex = function () 
{
	return this._index;
};

ColorSliderGroup.prototype.setSegment = function (oCSegment)
{
	this.segment = oCSegment;
};







