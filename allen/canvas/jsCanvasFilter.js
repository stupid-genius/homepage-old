/********************************************
*	jsCanvasFilter
*	a filtering object for HTML5 canvas's
*	written by Allen Ng
********************************************/
function jsCanvasFilter(canvas)
{
	var width = $(canvas).width();
	var height = $(canvas).height();
	var pitch = width*4;
	var context = canvas.getContext("2d");
	var linkedContext;
	var surfaces = [context.createImageData(width, height), context.createImageData(width, height)];
	var surfCount = surfaces.length;
	var frameCount = 0;
	this.frontBuffer = surfaces[1];
	this.backBuffer = surfaces[0];
	var map = Array.prototype.map;
	var lens;
	var lensWidth;
	var lensDepth;
	this.linkCanvas = function(tgtElem)
	{
		linkedContext = tgtElem.getContext("2d");
	}

	this.kernelFirstPass = function(val, index, array)
	{
		if(index<pitch || index>=array.length-pitch || index%pitch<4 || index%pitch>pitch-5 || index%4==3)
			return;
		var c = 1,
			l1 = 1,
			l2 = 1,
			l3 = 1,
			l4 = 1;
		var avg =
		(
			c*this.frontBuffer.data[index]+
			l1*this.frontBuffer.data[index-4]+
			l1*this.frontBuffer.data[index+4]
			/*l2*this.frontBuffer.data[index-8]+
			l2*this.frontBuffer.data[index+8]+
			l3*this.frontBuffer.data[index-12]+
			l3*this.frontBuffer.data[index+12]+
			l4*this.frontBuffer.data[index-16]+
			l4*this.frontBuffer.data[index+16]*/
		)/(c+l1+l1);//+l2+l2+l3+l3+l4+l4);
		//var adjLeft = this.frontBuffer.data[index-4]-avg;
		//var adjRight = this.frontBuffer.data[index+4]-avg;
		//array[index] = avg+.75*(adjLeft+adjRight);
		array[index] = avg;
	}
	this.kernelSecondPass = function(val, index, array)
	{
		if(index<pitch || index>=array.length-pitch || index%pitch<4 || index%pitch>=pitch-4 || index%4==3)
			return;
		var c = 1,
			l1 = 1,
			l2 = 1,
			l3 = 1,
			l4 = 1;
		var avg =
		(
			c*array[index]+
			l1*array[index-pitch]+
			l1*array[index+pitch]
			/*l2*array[index-pitch*2]+
			l2*array[index+pitch*2]+
			l3*array[index-pitch*3]+
			l3*array[index+pitch*3]+
			l4*array[index-pitch*4]+
			l4*array[index+pitch*4]*/
		)/(c+l1+l1);//+l2+l2+l3+l3+l4+l4);
		//var adjLeft = this.frontBuffer.data[index-pitch]-avg;
		//var adjRight = this.frontBuffer.data[index+pitch]-avg;
		//array[index] = avg+.25*(adjLeft+adjRight);
		array[index] = avg;
	}
	this.fireFirstPass = function(val, index, array)
	{
		if(index<pitch || index>=array.length-pitch || index%pitch<4 || index%pitch>pitch-5 || index%4==3)
			return;
		var c = 1,
			l1 = 1,
			l2 = 1,
			l3 = 1,
			l4 = 1;
		var avg =
		(
			c*this.frontBuffer.data[index]+
			l1*this.frontBuffer.data[index-4]+
			l1*this.frontBuffer.data[index+4]+
			l1*array[index+pitch]
		)/(c+l1+l1+l1);
		array[index] = avg;
	}
	this.fireSecondPass = function(val, index, array)
	{
		if(index<pitch || index>=array.length-pitch || index%pitch<4 || index%pitch>=pitch-4 || index%4==3)
			return;
		var c = 1,
			l1 = 1,
			l2 = 1,
			l3 = 1,
			l4 = 1;
		var avg =
		(
			c*array[index]+
			l1*array[index+pitch]
		)/(c+l1);
		array[index] = avg;
	}

	this.setPixel = function(x, y, r, g, b, a)
	{
		var i = (x + (y*width))*4;
		this.backBuffer.data[i] = r;
		this.backBuffer.data[i+1] = g;
		this.backBuffer.data[i+2] = b;
		this.backBuffer.data[i+3] = a;
	}
	this.setPixelF = function(x, y, r, g, b, a)
	{
		var i = (x + y*width)*4;
		this.frontBuffer.data[i] = r;
		this.frontBuffer.data[i+1] = g;
		this.frontBuffer.data[i+2] = b;
		this.frontBuffer.data[i+3] = a;
	}
	this.fill = function(r, g, b, a)
	{
		var pixels = this.backBuffer.data;
		var count = pixels.length;
		for(var i = 0;i<count;i+=4)
		{
			pixels[i] = r;
			pixels[i+1] = g;
			pixels[i+2] = b;
			pixels[i+3] = a;
		}
	}
	this.fillF = function(r, g, b, a)
	{
		var pixels = this.frontBuffer.data;
		var count = pixels.length;
		for(var i = 0;i<count;i+=4)
		{
			pixels[i] = r;
			pixels[i+1] = g;
			pixels[i+2] = b;
			pixels[i+3] = a;
		}
	}

	this.drawPoint = function(x, y, c)
	{
		this.drawLine(x, y, x, y, c);
	}
	this.drawLine = function(x1, y1, x2, y2, c)
	{
		context.strokeStyle = "#"+c[0].toString(16)+c[1].toString(16)+c[2].toString(16);
		context.beginPath();
		context.moveTo(x1, y1);
		context.lineTo(x2, y2);
		context.stroke();
	}
	this.drawRect = function()
	{
		
	}
	this.drawCircle = function(x, y, r, f, c)
	{
		context.beginPath();
		context.arc(x, y, r, 0, 2*Math.PI);
		if(f)
		{
			context.fillStyle = "#"+c[0].toString(16)+c[1].toString(16)+c[2].toString(16);
			context.fill();
		}
		else
		{
			context.strokeStyle = "#"+c[0].toString(16)+c[1].toString(16)+c[2].toString(16);
			context.stroke();
		}
	}

	this.setFont = function(sProp, c)
	{
		context.font = sProp;
		context.fillStyle = "#"+c[0].toString(16)+c[1].toString(16)+c[2].toString(16);
	}
	this.print = function(s, x, y)
	{
		context.fillText(s, x, y);
	}

	this.blit = function(src, x, y)
	{
		context.drawImage(src, x, y, width, height);
	}
	this.createLens = function(w, d)
	{
		lensWidth = w;
		lensDepth = d;
		lens = new Array();
		for(var i=0;i<w*w;++i)
			lens.push(0);
		var r = Math.round(w/2);
		var r2 = r*r;
		var center = r*(w+1);
		var offset;
		for(var i=0;i<r2;++i)
		{
			var x = i%r;
			var y = Math.floor(i/r);
			var x2 = x*x;
			var y2 = y*y;
			var ix = 0;
			var iy = 0;
			if((x2+y2) < (r2))
			{
				var shift = d/Math.sqrt(d*d - (x2 + y2 - r2));
				ix = Math.floor(x*shift) - x;
				iy = Math.floor(y*shift) - y;
			}

			offset = (iy*width + ix)*4;
			lens[center - (y*w) - x] = -offset;
			lens[center + (y*w) + x] = offset;
			offset = (-iy*width + ix)*4;
			lens[center - (y*w) + x] = offset;
			lens[center + (y*w) - x] = -offset;
		}
	}
	this.createWarp = function(w,idx)
	{
		lensWidth = w;
		//lensDepth = d;
		lens = new Array();
		for(var i=0;i<w*w;++i)
			lens.push(0);
		var radius = Math.round(w/2);
		var r2 = radius*radius;
		var center = radius*(w+1);
		var offset;
		for(var i=0;i<r2;++i)
		{
			var x = i%radius;
			var y = Math.floor(i/radius);
			var x2 = x*x;
			var y2 = y*y;
			var ix = 0;
			var iy = 0;
			if((x2+y2) < (r2))
			{
				var shift = (1-(Math.log(Math.sqrt(x2+y2))/Math.log(radius)))*idx;
				ix = Math.floor(x*shift);
				iy = Math.floor(y*shift);
			}

			offset = (iy*width + ix)*4;
			lens[center - (y*w) - x] = -offset;
			lens[center + (y*w) + x] = offset;
			offset = (-iy*width + ix)*4;
			lens[center - (y*w) + x] = offset;
			lens[center + (y*w) - x] = -offset;
		}
	}
	this.applyLens = function(dx, dy)
	{
		var pos;
		for(var i in lens)
		{
			pos = (((dy+Math.floor(i/lensWidth))*width)+(dx+(i%lensWidth)))*4;
			this.frontBuffer.data[pos] = this.backBuffer.data[pos+lens[i]];
			this.frontBuffer.data[pos+1] = this.backBuffer.data[pos+1+lens[i]];
			this.frontBuffer.data[pos+2] = this.backBuffer.data[pos+2+lens[i]];
			this.frontBuffer.data[pos+3] = this.backBuffer.data[pos+3+lens[i]];
		}
	}
	this.createLightBloom = function(x, y, r, c)
	{
		this.drawCircle(x, y, r-2, true, c);
		this.syncF();
		var w = 2*r;
		var r2 = r*r;
		var center = (y*width+x)*4;
		for(var i=0;i<=r2;++i)
		{
			var xCur = i%r;
			var yCur = Math.floor(i/r);
			var x2 = xCur*xCur;
			var y2 = yCur*yCur;
			var alpha;
			if((x2+y2) < r2)
			{
				alpha = (1 - (Math.log(Math.sqrt(x2+y2))/Math.log(r)))*c[3];
				this.frontBuffer.data[center - (yCur*pitch) - xCur*4 +3] = alpha;
				this.frontBuffer.data[center + (yCur*pitch) + xCur*4 +3] = alpha;
				this.frontBuffer.data[center - (yCur*pitch) + xCur*4 +3] = alpha;
				this.frontBuffer.data[center + (yCur*pitch) - xCur*4 +3] = alpha;
			}
		}
		this.refresh();
	}

	this.convolute = function()
	{
		map.call(this.backBuffer.data, this.kernelFirstPass, this);
		map.call(this.backBuffer.data, this.kernelSecondPass, this);
	}
	this.burn = function()
	{
		map.call(this.backBuffer.data, this.fireFirstPass, this);
		//map.call(this.backBuffer.data, this.fireSecondPass, this);
	}

	this.sync = function(offscreen)
	{
		if(offscreen)
			surfaces[frameCount%surfCount] = linkedContext.getImageData(0,0,width,height);
		else
			surfaces[frameCount%surfCount] = context.getImageData(0,0,width,height);
		this.backBuffer = surfaces[frameCount%surfCount];
	}
	this.syncF = function(offscreen)
	{
		if(offscreen)
			surfaces[(frameCount+1)%surfCount] = linkedContext.getImageData(0,0,width,height);
		else
			surfaces[(frameCount+1)%surfCount] = context.getImageData(0,0,width,height);
		this.frontBuffer = surfaces[(frameCount+1)%surfCount];
	}
	this.flip = function()
	{
		this.frontBuffer = surfaces[frameCount++%surfCount];
		this.backBuffer = surfaces[frameCount%surfCount];
	}
	this.refresh = function(offscreen)
	{
		if(offscreen)
			linkedContext.putImageData(this.frontBuffer,0,0);
		else
			context.putImageData(this.frontBuffer,0,0);
	}
	this.update = function()
	{
		this.flip();
		this.refresh();
	}
}