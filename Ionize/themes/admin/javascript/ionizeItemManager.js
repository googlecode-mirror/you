/** ItemManager
 *	Manage a simple items list of element
 *
 *	Options :
 *
 *		baseUrl:			URL to the website
 *		element:			The element name. Each elemetn ID must be named like : element_ID
 *		itemContainer:		The items container DOM element 
 *
 */

var IonizeItemManager = new Class(
{
	Implements: Options,

    options: {
		element:		'',
		idParent:		'',
		parent:		'',
		container:		false
    },

	initialize: function(options)
	{
		this.setOptions(options);
		
		this.baseUrl =		base_url;

		this.idParent =		this.options.idParent;
		this.parent =		this.options.parent;
		this.element =		this.options.element;

		// Container
		this.container = 	$(this.options.container);
		
		this.sortables = null;

	},

	/**
	 * Makes the containers elements sortable
	 * needs to be explicitely called after an itemManager init.
	 *
	 * handler element class : .drag
	 *
	 * Usage of this function needs that the CI controller has a "save_ordering" method
	 *
	 */
	makeSortable: function()
	{
		if (this.container)
		{
			var list = this.options.list;
			if (!list) list = this.options.container;
		
			// Init the sortable 
			this.sortables = new Sortables(list, {
				constrain: true,
				revert: true,
				handle: '.drag',
				referer: this,
				clone: true,
				opacity: 0.5,
				onComplete: function(item)
				{
					// Hides the current sorted element (correct a Mocha bug on hidding modal window)
					item.removeProperty('style');
					
					// Get the new order					
					var serialized = this.serialize(0, function (element, index) 
					{
						// Get the ID list by replacing 'type_' by '' for each item
						// Example : Each picture item is named 'picture_ID' where 'ID' is the media ID
						var id = element.getProperty('id');
						return id.replace(this.options.referer.element + '_', '');
					});
					
					// Items sorting
					this.options.referer._sortItemList(serialized);
				}			
			});
		
			// Store the first ordering after picture list load
			this.container.store('sortableOrder', this.sortables.serialize(0,function (element, index) 
			{
				var id = element.getProperty('id');									
				return id.replace(this.options.element + '_', '');
			}.bind(this)));
			
	//		this.container.store('sortable', sortables);
		}
	
	},

	
	/**
	 * Add one item to the Sortable list
	 * @param	DOM element		Element to add
	 *
	 */
	addItem: function(el)
	{
		this.sortables.addItems(el);
	},
	
	
	/** 
	 * Items list ordering
	 * called on items sorting complete
	 * calls the XHR server ordering method
	 *
	 * @param	string	Media type. Can be 'picture', 'video', 'music', 'file'
	 * @param	string	new order as a string. coma separated
	 *
	 */
	_sortItemList: function(serialized) 
	{
		var sortableOrder = this.container.retrieve('sortableOrder');

		// If current <> new ordering : Save it ! 
		if (sortableOrder.toString() != serialized.toString() ) 
		{
			// Store the new ordering
			this.container.store('sortableOrder', serialized);

			// Set the request URL
			var rUrl = this.baseUrl + 'admin/' + this.element + '/save_ordering';
			
			// If parent and parent ID are defined, send them to the controller through the URL
			if (this.parent && this.idParent)
			{
				rUrl += '/' + this.parent + '/' + this.idParent
			}

			// Save the new ordering
			var myAjax = new Request.JSON(
			{
				url: rUrl,
				method: 'post',
				data: 'order=' + serialized,
				onSuccess: function(responseJSON, responseText)
				{
					MUI.hideSpinner();

					// Get the update table and do the jobs
					if (responseJSON.update != null && responseJSON.update != '')
					{
						MUI.updateElements(responseJSON.update);
					}

					// User message
					MUI.notification(responseJSON.message_type, responseJSON.message);
				}
			}).post();
		}
	},
	

	/**
	 * Deletes one item
	 * Shows a confirmation modal window before effective delete
	 *
	 * @param	int		Element ID
	 *
	 */
	deleteItem: function(id)
	{
		// Callback definition
		var callback = this._deleteConfirm.bind(this).pass(id);

		// Confirmation modal window
		MUI.confirmation('del' + this.element + id, callback, Lang.get('ionize_confirm_element_delete'));
	},


	/**
	 * Effective item delete
	 * callback function
	 *
	 * @param	int		item ID
	 *
	 */
	_deleteConfirm: function(id)
	{
	
		// Shows the spinner
		MUI.showSpinner();

		// Delete URL
		var url = this.baseUrl + 'admin/' + this.element + '/delete/' + id;
		
		// If parent, include it to URL
		if (this.parent && this.idParent)
		{
			url += '/' + this.parent + '/' + this.idParent
		}
		
		url = MUI.cleanUrl(url);
		
		// JSON Request
		var xhr = new Request.JSON(
		{
			url: this.baseUrl + url,
			method: 'post',
			onSuccess: function(responseJSON, responseText)
			{
				if (responseJSON.id)
				{
					// Remove the element from the sortables
					if (this.sortables != null)	{ this.sortables.removeItems($(this.element + '_' + responseJSON.id)); }
				
					// Remove all HTML elements with the classe corresponding to the element type and ID
					$$('.' + this.element + responseJSON.id).each(function(item, idx) { item.dispose(); });
					
					// Get the update array and do the jobs
					if (responseJSON.update != null && responseJSON.update != '') {	MUI.updateElements(responseJSON.update); }
					
					// As we delete the current edited item, let's return to the home page
					if($('id_' + this.element) && $('id_' + this.element).value == id)
					{
						MUI.updateContent({
							'element': $('mainPanel'),
							'loadMethod': 'xhr',
							'url': this.baseUrl + 'admin/dashboard',
							'title': Lang.get('ionize_title_welcome')
						});
						MUI.initToolbox();
					}
				}
				// User message
				MUI.notification(responseJSON.message_type, responseJSON.message);		
				
				// Hides the spinner
				MUI.hideSpinner();
				
			}.bind(this),
			onFailure: this.failure.bind(this)
		}).send();

	},

	
	/**
	 * Set an item online / offline depending on its current status
	 *
	 * This function pdates visual status elements :
	 *
	 * - status icons : must have the class parameter set to 'online_[itemType]_status[element_id]'
	 * 					Example : <img class="online_article_status23" ... />
	 * - online checkboxes : must have the id paramter set to 'online'
	 *						 Example : <input id="online" type="checkbox" ... />
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
					// Set online / offline status class on A element
					if (responseJSON.status == 1) {
						$$('.' + this.element + responseJSON.id).removeClass('offline').addClass('online');
					}
					else
					{
						$$('.' + this.element + responseJSON.id).removeClass('online').addClass('offline');
					}
				
					// Set the new status on checkedboxes
					var cb = $('online');
					var el = $('id_' + this.element);
					
					if (cb && el && el.value == id)
					{
						if (responseJSON.status == 1) {	cb.setProperty('checked', 'checked'); }
						else { cb.removeProperty('checked'); }
					}
					
					// Get the update table and do the jobs
					if (responseJSON.update != null && responseJSON.update != '')
					{
						MUI.updateElements(responseJSON.update);
					}
					
				}
				// User message
				MUI.notification.delay(50, this, new Array(responseJSON.message_type, responseJSON.message));
				
				// Hides the spinner
				MUI.hideSpinner();
				
			}.bind(this),
			onFailure: this.failure.bind(this)
		}).send();
	},


	/** 
	 * Called in case of request fail
	 */
	failure: function(xhr)
	{
		MUI.notification('error', xhr.responseText );

		// Hide the spinner
		MUI.hideSpinner();
	}

});
