initializeForms = function() 
{

	/**
	 * Returns the Ionize form object
	 *
	 * @param	string		URL to send the form data. With or without the base URL prefix. Will be cleaned.
	 * @param	mixed		Form data
	 */
	MUI.getFormObject = function(url, data)
	{
		if (!data) {
			data = '';
		}

		// Cleans URLs
		url = MUI.cleanUrl(url);

		return {
			url: base_url + url, 
			method: 'post',
			loadMethod: 'xhr',
			data: data,
			onRequest: function()
			{
				MUI.showSpinner();
			},
			onFailure: function(xhr) 
			{
				MUI.hideSpinner();

				// Error notification
				MUI.notification('error', xhr.responseJSON);
			},
			onSuccess: function(responseJSON, responseText)
			{
				MUI.hideSpinner();
				
				// Update the elements transmitted through JSON
				if (responseJSON.update)
				{
					// Updates all the elements in the update array
					// look at init-content.js for more details
					MUI.updateElements(responseJSON.update);
				}

				// JS Callback
				if (responseJSON.callback)
				{
					var cb = (responseJSON.callback.fn).split(".");
					var func = null;
					var obj = null;
					
					if (cb.length > 1)
					{
						obj = window[cb[0]];
						func = obj[cb[1]];
					}
					else
					{
						func = window[cb];
					}
					func.delay(100, obj, responseJSON.callback.args);
				}
	
				// Success notification
//				MUI.notification(responseJSON.message_type, responseJSON.message);
				MUI.notification.delay(50, MUI, new Array(responseJSON.message_type, responseJSON.message));
	
			}
		};
	}

	/**
	 * Get the associated form object and send it directly
	 *
	 * @param	string		URL to send the form data
	 * @param	string		Element to update
	 * @param	string		Element update URL
	 */
	MUI.sendForm = function(url)
	{
		new Request.JSON(MUI.getFormObject(url)).send();
	}

	/**
	 * Get the associated form object and send attached data directly
	 *
	 * @param	string		URL to send the form data
	 * @param	string		Element to update
	 * @param	string		Element update URL
	 */
	MUI.sendData = function(url, data)
	{
		new Request.JSON(MUI.getFormObject(url, data)).send();
	}


	/**
	 * Set an XHR action to a form and add click event to the attached element
	 *
	 * @param	string	form name
	 * @param	string	element on wich attach the action (ID)
	 * @param	string	action URL (with or without the base URL prefix)
	 * @param	string	action URL (with or without the base URL prefix)
	 *
	 */
	MUI.setFormSubmit = function(form, button, url, confirm)
	{
		// Add the form submit event wih a confirmation window
		if ($(button) && ($type(confirm) == 'object'))
		{
			var func = function()
			{
				MUI.showSpinner();

				var options = MUI.getFormObject(url, $(form));
				
				var r = new Request.JSON(options);
				
				r.send();
			};
		
			// Form submit or button event
			$(button).addEvent('click', function(e)
			{
				new Event(e).stop();
				
				MUI.confirmation('conf' + button.id, func, confirm.message);
			});
		}
		// Add athe form submit button event without confirmation
		else if ($(button))
		{
			// Form submit or button event
			$(button).addEvent('click', function(e)
			{
				new Event(e).stop();
				
				// Show spinner
				MUI.showSpinner();
				
				// tinyMCE trigerSave
				// mandatory for text save. See how to externalize without make it too complex.
				tinyMCE.triggerSave();
				
				// Get the form
				var options = MUI.getFormObject(url, $(form));
				
				var r = new Request.JSON(options);
				
				r.send();
			});
		}
	}


	/**
	 * CTRL+s or Meta+s save event
	 *
	 */
	MUI.addFormSaveEvent = function(button)
	{
		if ($(button))
		{
			// Remove all existing Ctrl+S Save Event
			$(document).removeEvents('keydown');
			
			// Add new keydown 
			$(document).addEvent('keydown', function(event)
			{
				if((event.control || event.meta) && event.key == 's')
				{
					event.stop();
					if ($(button))
					{
						$(button).fireEvent('click', event);
					}
				}
			});
		}
	}

	
	MUI.myChain.callChain();

}
