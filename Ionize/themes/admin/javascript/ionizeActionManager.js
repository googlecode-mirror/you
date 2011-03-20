/** ActionManager
 *	Provides actions on HTMLDomElement
 *
 *	Options :
 *
 *		baseUrl:			URL to the website
 *		element:			The element name. Each elemetn ID must be named like : element_ID
 *		itemContainer:		The items container DOM element 
 *
 */

var IonizeActionManager = new Class(
{
	Implements: Options,

    options: {
		baseUrl:		'',
		element:		'',
		idParent:		'',
		parent:		'',
		container:		false
    },

	initialize: function(options)
	{
		this.setOptions(options);
		
		this.baseUrl =		this.options.baseUrl;
	},
	
	
	/**
	 * Set an item online / offline depending on its current status
	 *
	 * @param	int		item ID
	 * @param	int		current status. 1 : online, 0 : offline
	 *
	 */
	switchOnline: function(id)
	{
		// Show the spinner
		MUI.showSpinner();
		
		var xhr = new Request.JSON(
		{
			url: this.baseUrl + 'admin/' + this.element + '/switch_online/' + id,
			method: 'post',
			onSuccess: function(responseJSON, responseText)
			{
				// Change the item status icon
				if ( responseJSON.message_type == 'success' )
				{
					// Set the new online status icon on all articles images
					var icons = $$('.online_' + this.element + '_status' + responseJSON.id);
					
					if (icons)
					{
						// Offline icon
						var src = 'icon_16_offline.png';
						
						// Get the online icon
						if (responseJSON.status == 1) {	src = 'icon_16_online.png';	}
						
						src = this.baseUrl + 'themes/admin/images/' + src;

						// Set the new src to each online icon
						icons.each(function(item, idx)
						{
							item.setProperty('src', src);
						});
					}
				
					// Get the update table and do the jobs
					if (responseJSON.update != null && responseJSON.update != '')
					{
						MUI.updateElements(responseJSON.update);
					}
					
				}
				// User message
				MUI.notification(responseJSON.message_type, responseJSON.message);		
				
				// Hides the spinner
				MUI.hideSpinner();
				
			}.bind(this),
			onFailure: this.failure.bind(this)
		}).send();
	}


});