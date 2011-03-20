/** MediaManager
 *	Opens the choosen media / file manager and get the transmitted file name
 *
 *	Options :
 *
 *		baseUrl:			URL to the website
 *		parent:				type of the parent. 'article', 'page', etc. Used to update the database table.                 
 *		idParent:			ID of the parent element      
 *		pictureContainer:	The picture container DOM element 
 *		musicContainer:		The MP3 list container DOM element
 *		videoContainer:		The video list container DOM element
 *		button:				DOM opener button name
 *		mode:				'tinyBrowser' : Using the tinyBrowser plugin to browse files
 *							'mcFileManager' : Using MoxeCode MceImageManager / MceFileManager to browse files
 */

var IonizeMediaManager = new Class(
{
	Implements: Options,

    options: {
		parent:			false,
		idParent:		false,
		mode:			'',								// 'mcFileManager', 'tinyBrower', 'ezfilemanager',
		musicArray:		Array('mp3'),					// Array of authorized files extensions
		pictureArray:	Array('jpg', 'gif', 'png', 'jpeg'),
		videoArray:		Array('flv', 'fv4'),
		fileArray:		Array()
    },

	initialize: function(options)
	{
		this.setOptions(options);
		
		this.baseUrl =		this.options.baseUrl;

		this.idParent =		options.idParent;
		this.parent =		options.parent;

		// Containers storing
		this.containers = 	new Hash({
							 'picture' : options.pictureContainer,
							 'music' : options.musicContainer,
							 'video': options.videoContainer,
							 'file': options.fileContainer
							});
		// Filemanager mode
		this.mode =			options.mode;

		// Filemanager opening buttons
		$$(options.fileButton).each(function(item)
		{
			item.addEvent('click', function(e)
			{
				var e = new Event(e).stop();
				this.toggleFileManager();
			}.bind(this));
		}.bind(this));
		
		// image Manager opening buttons
		$$(options.imageButton).each(function(item)
		{
			item.addEvent('click', function(e)
			{
				var e = new Event(e).stop();
				this.toggleImageManager();
			}.bind(this));
		}.bind(this));

	},
	
	/**
	 * Adds one medium to the current parent
	 * Called by callback by the file / image manager
	 * 
	 * @param	string	Complete URL to the media. Slashes ('/') were replaced by ~ to permit CI management
	 *
	 */
	addMedia:function(url) 
	{

		// File extension
		var extension = (url.substr(url.lastIndexOf('.') + 1 )).toLowerCase();

		// Check media type regarding the extension
		var type = false;
		if (this.options.pictureArray.contains(extension)) { type='picture';}
		if (this.options.musicArray.contains(extension)) { type='music';}
		if (this.options.videoArray.contains(extension)) { type='video';}
		if (this.options.fileArray.contains(extension)) { type='file';}

		// Media type not authorized : error message
		if (type == false)
		{
			MUI.notification('error', Lang.get('ionize_message_media_not_authorized'));
		}
		else
		{
			// Complete relative path to the media
			var path =	url.replace(/\//g, "~");

			// Send the media to link
			// To send by get, set URL to : this.baseUrl + 'admin/media/add_media/' + type + '/' + this.parent + '/' + this.idParent + '/' + path
			var xhr = new Request.JSON(
			{
				'url': this.baseUrl + 'admin/media/add_media/' + type + '/' + this.parent + '/' + this.idParent, 
				'method': 'post',
				'data': 'path=' + path,
				'onSuccess': this.successAddMedia.bind(this), 
				'onFailure': this.failure.bind(this)
			}).send();
		}
	},


	/**
	 * called after 'addMedia()' success
	 * calls 'loadMediaList' with the correct media type returned by the XHR call
	 *
	 */
	successAddMedia: function(responseJSON, responseText)
	{
		MUI.notification(responseJSON.message_type, responseJSON.message);

		// Media list reload
		if (responseJSON.type)
		{
			this.loadMediaList(responseJSON.type);
		}
	},


	/**
	 * Loads a media list through XHR regarding its type
	 * called after a medi list loading through 'loadMediaList'
	 *
	 * @param	string	Media type. Can be 'picture', 'music', 'video', 'file'
	 *
	 */
	loadMediaList: function(type)
	{
		// Only loaded if a parent exists
		if (this.idParent)
		{
			var myAjax = new Request.JSON(
			{
				'url' : this.baseUrl + 'admin/media/get_media_list/' + type + '/' + this.parent + '/' + this.idParent,
				'method': 'get',
				'onFailure': this.failure.bind(this),
				'onComplete': this.completeLoadMediaList.bind(this)
			}).send();
		}
	},

	
	/**
	 * Initiliazes the media list regarding to its type
	 * called after a media list loading through 'loadMediaList'
	 *
	 * @param object	JSON response object
	 * 					responseJSON.type : media type. Can be 'picture', 'video', 'music', 'file'
	 * 					responseJSON.content : 
	 *
	 */
	completeLoadMediaList: function(responseJSON, responseText)
	{
		// Hides the spinner
		MUI.hideSpinner();

		// Receiver container
		var container = $(this.containers.get(responseJSON.type));
		
		if (responseJSON && responseJSON.content)
		{
			// Feed the container with responseJSON content		
			container.set('html', responseJSON.content);

			// Init the sortable 
			sortableMedia = new Sortables(container, {
				revert: true,
				handle: '.drag',
				referer: this,
				clone: true,
				opacity: 0.5,
				onComplete: function()
				{
					var serialized = this.serialize(0,function (element, index) 
					{
						// Get the ID list by replacing 'type_' by '' for each item
						// Example : Each picture item is named 'picture_ID' where 'ID' is the media ID
						return element.getProperty('id').replace(responseJSON.type + '_','');
					});
					
					// Items sorting
					this.options.referer.sortItemList(responseJSON.type, serialized);
				}		
			});
		
			// Store the first ordering after picture list load
			container.store('sortableOrder', sortableMedia.serialize(0,function (element, index) {
												return element.getProperty('id').replace(responseJSON.type + '_','');
			}));
		}
		// If no media, feed the content HMTLDomElement with transmitted message
		else
		{
			container.set('html', responseJSON.message);
		}
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
	sortItemList: function(type, serialized) 
	{
		var container = $(this.containers.get(type))
		var sortableOrder = container.retrieve('sortableOrder');

		// If current <> new ordering : Save it ! 
		if (sortableOrder.toString() != serialized.toString() ) 
		{
			// Store the new ordering
			container.store('sortableOrder', serialized);

			// Save the new ordering
			var myAjax = new Request.JSON(
			{
				url: 'admin/media/save_ordering/' + this.parent + '/' + this.idParent,
				method: 'post',
				data: 'order=' + serialized,
				onSuccess: function(responseJSON, responseText)
				{
					MUI.hideSpinner();
					
					MUI.notification(responseJSON.message_type, responseJSON.message);
				}
			}).post();
		}
	},



	/** 
	 * Called when one request fails
	 */
	failure: function(xhr)
	{
		MUI.notification('error', xhr.responseText );

		// Hide the spinner
		MUI.hideSpinner();
	},


	/**
	 * Unlink one media from his parent
	 *
	 * @param	string	Media type
	 * @param	string	Media ID
	 *
	 */
	detachMedia: function(type, id) 
	{
		// Show the spinner
		MUI.showSpinner();
		
		var xhr = new Request.JSON(
		{
			url: this.baseUrl + 'admin/media/detach_media/' + type + '/' + this.parent + '/' + this.idParent + '/' + id,
			method: 'post',
			onSuccess: this.disposeMedia.bind(this),
			onFailure: this.failure.bind(this)
		}).send();
	},


	/**
	 * Unlink all media from a parent depending on the type
	 *
	 * @param	string	Media type. Can be 'picture', 'music', 'video', 'file'
	 *
	 */	
	detachMediaByType: function(type)
	{
		// Show the spinner
		MUI.showSpinner();
		
		var xhr = new Request.JSON(
		{
			url: this.baseUrl + 'admin/media/detach_media_by_type/' + this.parent + '/' + this.idParent + '/' + type,
			method: 'post',
			onSuccess: function(responseJSON, responseText)
			{
				$(this.containers.get(type)).empty();
				
				// Message
				MUI.notification(responseJSON.message_type, responseJSON.message);
				
				// Hides the spinner
				MUI.hideSpinner();
				
			}.bind(this),
			onFailure: this.failure.bind(this)
		}).send();
	
	},

	
	/**
	 * Dispose one HTMLDomElement
	 *
	 * @param	object	JSON XHR request answer
	 * @param	object	Text XHR request answer
	 *
	 */
	disposeMedia: function(responseJSON, responseText)
	{
		// HTMLDomElement to dispose
		var el = responseJSON.type + '_' + responseJSON.id;
		
		if ( responseJSON.id && $(el))
		{
			$(el).dispose();
			
			MUI.notification('success', responseJSON.message);		
		}
		else
		{
			MUI.notification('error', responseJSON.message);
		}
		
		MUI.hideSpinner();

	},


	/** 
	 * Init thumbnails for one picture
	 * to be called on pictures list
	 * @param	string	picture ID
	 *
	 */
	initThumbs:function(id_picture) 
	{
		// Show the spinner
		MUI.showSpinner();

		var myAjax = new Request.JSON(
						{
							url: this.baseUrl + 'admin/media/init_thumbs/' + id_picture,
							method: 'post',
							onSuccess: function(responseJSON, responseText)
							{
								MUI.notification(responseJSON.message_type, responseJSON.message );
								
								if (responseJSON.message_type == 'success')
								{
									this.loadMediaList('picture');
								}
							}.bind(this)
						}
					).send();
	},


	/** 
	 * Init all thumbs for one parent
	 *
	 */
	initThumbsForParent: function()
	{
		// Show the spinner
		MUI.showSpinner();
		
		var myAjax = new Request.JSON(
						{
							url: this.baseUrl + 'admin/media/init_thumbs_for_parent/' + this.parent + '/' + this.idParent,
							method: 'get',
							onSuccess: function(responseJSON, responseText)
							{
								MUI.notification(responseJSON.message_type, responseJSON.message );
								
								if (responseJSON.message_type == 'success')
								{
									this.loadMediaList('picture');
								}
							}.bind(this)
							
						}
					).send();
	},
	
	
	/** 
	 * Opens imageManager or fileManager
	 *
	 */
	toggleFileManager:function() 
	{
		// If no parent exists : don't show the flemanager but an error message	
		if ( ! this.idParent || this.idParent == '')
		{
			MUI.notification('error', Lang.get('ionize_message_please_save_first'));
		}
		else
		{
			switch (this.mode)
			{
				case 'filemanager': 
					mcFileManager.init({
						remove_script_host : true,
						iframe : false
					});
					
					mcFileManager.open('fileManagerForm','hiddenFile', false, this.addMedia.bind(this));
					break;
				
				case 'tinybrowser':
					tinyBrowserPopUp('file','hiddenFile');
					break;
				
				case 'ezfilemanager':
					
					var url = this.baseUrl + 'themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/ezfilemanager/ezfilemanager.php?type=file&sa=1';
					var xPos = (window.screen.availWidth/2) - (w/2);
					var yPos = 60;
					var config = 'width=750, height=450, left='+xPos+', top='+yPos+', toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no';
					var w = window.open(url, 'filemanager', config);
					w.focus();

					
					
					// openPopup(this.baseUrl + 'javascript/tinymce/jscripts/tiny_mce/plugins/ezfilemanager/ezfilemanager.php?type=file&sa=1','filemanager',750,450);
					break;
				
				default : 
					MUI.notification('error', 'No mode set for mediaManager');
			}
		}
	},

	/** 
	 * Opens the image Manager
	 */
	toggleImageManager:function() 
	{
		// If no parent exists : don't show the flemanager but an error message	
		if ( ! this.idParent || this.idParent == '')
		{
			MUI.notification('error', Lang.get('ionize_message_please_save_first'));
		}
		else
		{
			switch (this.mode)
			{
				case 'filemanager': 
					mcImageManager.init({
						remove_script_host : false,
						iframe : false
					});
					mcImageManager.open('fileManagerForm','hiddenFile', false, this.addMedia.bind(this));
					break;
				
				case 'tinybrowser':
					
					/** A new test is added to "js/tinyBrowser.js.php" to get the url
						Call of mediaManager.addMedia() :
	
						elseif($mainpage && $_GET['feid'] != '' && $tinybrowser['integration'] == 'tinymce')
						{?>
						function selectURL(url) {
							opener.mediaManager.addMedia(url);
							self.close();
						}
						<?php
						}
					*/
					tinyBrowserPopUp('image','imageHidden');
					break;
	
				case 'ezfilemanager':
					
					var url = this.baseUrl + 'themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/ezfilemanager/ezfilemanager.php?type=file&sa=1';
					var xPos = (window.screen.availWidth/2) - (w/2);
					var yPos = 60;
					var config = 'width=750, height=450, left='+xPos+', top='+yPos+', toolbar=no, menubar=no, scrollbars=yes, resizable=yes, location=no, directories=no, status=no';
					var w = window.open(url, 'filemanager', config);
					w.focus();
					break;
				
				default : 
					MUI.notification('error', 'No mode set for mediaManager');
			}
		}
	}

});
