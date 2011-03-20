
/** Log
 *	
 *	@author : Michel-Ange K. partikule, 2009
 *
 */

var Log = new Class({

	initialize: function(element)
	{
		this.el = $(element);
	},

	log:function(key, value, clean)
	{
		if (this.el) {
		
			if (clean== true)
				this.el.set('html', key + ':' + '<b>' + value + '</b><br/>');
			else
				this.el.set('html', this.el.get('html') + key + ':' + '<b>' + value + '</b><br/>');
		}
	}
});

var log;

