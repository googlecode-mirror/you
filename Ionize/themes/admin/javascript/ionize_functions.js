/** 
 * Ionize javascript functions
 *
 */


/** 
 * Shows / Hides one block
 * Simple tab manager
 *
 */
var displayBlock = function(group, id, prefix)
{
	if (prefix == null)
	{
		prefix = '';
	}

	if ($('block' + prefix + '-' + id))
	{
		$$(group).setStyle('display', 'none');
	
		$('block' + prefix + '-' + id).setStyle('display', 'block');
	
		element = $('tab' + prefix + '-' + id);
	
		// update the menu item
		element.getParent('ul').getChildren('li').each(function(el){
			el.removeClass('active');
		});
	
		element.addClass('active');
	}
};

/**
 * Show one block and set the corresponding tab active
 *
 * @param	string	Tab ID
 * @param	string	Block ID
 * @param	string	blocks common class
 *
 */
var showBlock = function(tabId, blockId, group)
{
	if ($(tabId))
	{
		$$(group).setStyle('display', 'none');
	
		$(blockId).setStyle('display', 'block');
	
		// update the menu item
		$(tabId).getParent('ul').getChildren('li').each(function(el){
			el.removeClass('active');
		});
	
		$(tabId).addClass('active');
	}
};


/** 
 * Clears one form field
 *
 */
var clearField = function(field) 
{
	if ($(field))
	{
		$(field).value='';
		$(field).focus();
	}
}


/** 
 * TinyMCE editor toggling
 *
 */
toggleTinyMCE = function(id) 
{
	if (!tinyMCE.getInstanceById(id))
	{
	    tinyMCE.execCommand('mceAddControl', false, id);
	}
	else
	{
		tinyMCE.execCommand('mceRemoveControl', false, id);
	}
}


/** 
 * EditArea save callback functions
 * See setting_edit_view.php
 *
 * @param	String		EditArea textarea ID
 * @param	String		View content
 *
 */		
function editAreaSave(id, areaContent)
{
	MUI.showSpinner();
	
	var id = id.replace('edit_','');
	
	var data = 'view=' + id + '&path=' + $('path_' + id).value + '&content=' + areaContent;
	
	new Request.JSON(
	{
		url:base_url + 'admin/setting/save_view',
		data: data,
		onSuccess: function(responseJSON, responseText)
		{
			MUI.hideSpinner();

			// Notification
			MUI.notification(responseJSON.message_type, responseJSON.message);
		},
		onFailure: function(xhr)
		{
			MUI.hideSpinner();

			// Error notification
			MUI.notification('error', xhr.responseJSON);
		
		}
	}).send();
}


/**
 * PNG IE Fix
 */
pngfix = function()
{
	var images = $$('img');
	images.each(function(e)
	{
		var src = e.getProperty('src');
		var height = e.height;
		var width = e.width;
		if (src.toUpperCase().contains('.PNG'))
		{
			var newImage = new Element('span', {
				'styles': {
					'filter': 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'' + src + '\', sizingMethod=\'scale\')',
					'display': 'inline-block',
					'height': height,
					'width': width
				}
			});
			
			newImage.replaces(e);
		}
	});
}
