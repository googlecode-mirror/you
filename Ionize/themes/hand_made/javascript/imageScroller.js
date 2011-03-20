/** 
 * ImageScroller
 *	
 * Scrolls images
 *
 * @usage :		
 *				
 * @author : 	Studio Partikule, 2009
 */

var ImageScroller = new Class({
	
	Implements: Array(Options, Events),

	options: {
		mode: 'horizontal',
		id: ''
	},

	initialize: function(parent, options)
	{
		this.setOptions(options);
		this.parent = $(parent);
		this.buttons = this.options.buttons;
		this.current = 0;
		this.curPos = 0;
		
		this.comment = (this.options.comment) ? $(this.options.comment) : false;
		
		this.scroller = this.parent.getFirst();

		// Pictures sources aray
		this.srcPictures = Array();
		
		// pictures array
		this.pictures = Array();

		// pictures objects array
		this.objPictures = this.scroller.getElements('img');
		
		// feed the srcPictures array
		for (var i = 0; i < this.objPictures.length; i++)
		{
			this.srcPictures[i] = this.objPictures[i].getProperty('src');
		}

		// Main picture size get event.
		// Fired by this.getPictureSize()
		/*
		this.addEvent('onImagesize', function()
		{
			this.build();		
		});
		
		this.getPictureSize(this.pictures[0]);
		*/
		this.loadPictures();
	},

	build:function()
	{
		var n = this.pictures.length;
		
		if (n > 1)
		{

			// first picture size : all picture must have the same size.
			this.size = {x:this.pictures[0].width, y:this.pictures[0].height};
			
			// Set the inner scroller size
			this.scroller.setStyles({
				'height': (this.options.mode == 'horizontal') ? this.size.y : n * this.size.y,
				'width': (this.options.mode == 'horizontal') ? n * this.size.x : this.size.x
			});

			this.mover = new Fx.Move(this.scroller, {
				relativeTo: this.parent,
							duration: 500,
							transition: Fx.Transitions.Quad.easeInOut,
				link: 'chain',
				position: 'upperLeft',
				edge: 'upperLeft'
			});
			// Set the buttons links
			this.setButtonsEvents();
			this.moveTo(0,0);
		}
		
	},
	
	loadPictures: function()
	{
		this.pictures = new Asset.images(this.srcPictures, 
		{
			onComplete: this.build.bind(this),
			onProgress: function(counter, index){
	//			$('loading').set('html', 'loading : ' + (100/this.images.length * (counter+1)) + ' %');
			}.bind(this)
		});

	},

	setButtonsEvents: function()
	{
		var next = $(this.buttons.next);
		var prec = $(this.buttons.prec);
		
		next.addEvent('click', function(e) {this.next(1);}.bind(this));
		prec.addEvent('click', function(e) {this.next(-1);}.bind(this));
	},
	
	next: function(dir)
	{
		var n = this.getNext(dir);
		
		if (n != this.current) {
			this.moveTo(n, dir)
		}
	
	},
	
	moveTo: function(n, dir)
	{
		this.current = n;
		
		if (this.comment)
		{
			var title = this.objPictures[n].getProperty('title');
			title = (title != null) ? title.replace('|', '<br/>') : '';
			var content = new Element('p', {'html' : title});
			this.comment.fade('hide');
			this.comment.empty();
			content.inject(this.comment);
			this.comment.fade('in');			
		}
		
		if (dir != 0)
		{
			var oldPos = this.curPos;
			this.curPos += (dir * this.size.x) * - 1;
	
			this.mover.start({
				offset: {x:this.curPos,y:0}
			});
		}
	},
	
	getNext: function(dir)
	{
		var n = this.current + dir;
		if (n > (this.pictures.length-1)) return this.pictures.length-1;
		else if (n < 0)	return 0;
		else return n;
	},
	
	
	/**
	 * Called on initialize to get one image size
	 * On some browsers, the image size is not available on load event
	 * So the function waits 50 ms before calling itself again if no size has been detected
	 * On size detect, fires the event 'imagesize'
	 * @deprecated.
	 * @param	HTMLImageElement		the image to get size from
	getPictureSize: function(img)
	{
		var size = img.getSize();
		
		if(size.y != 0)
		{
			this.size = size;
			this.fireEvent('imagesize');
		}
		else
			(this.getPictureSize).delay(50, this, img);
	}
	 */
	
});

