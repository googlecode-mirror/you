
var CONTROL_KEY = 1;
var SHIFT_KEY = 2;
var CONTROL_SHIFT_KEY = 3;


var IonizeFormManager = new Class(
{
	
	Implements: Options,

	options: {
		form:		'',
		saveEvent: false
	},

	initialize: function(options){
	
		this.setOptions(options);

		this.baseUrl =	this.options.baseUrl;	// Base URL for XHR requests
		
		// Add CTRL+S save Event
		// TODO : Re engineer.
		/*
		if (options.saveEvent == true)
		{
			this.addSaveEvent(options.form);
		}
		*/

		this.currentField =		'';					// Current working on field (for use by oncomplete functions)
	},

	isExisting: function(table, field, url_title)
	{
		var value = $(field).value;

		if (value != '')
		{
			this.currentField = field;

			calledUrl = this.baseUrl + 'admin/control/isExisting/' + table + '/' + field + '/' + value + '/' + url_title;
			
			var myAjax = new Request.HTML({
								url:calledUrl,
								onSuccess:this.isExistingComplete.bind(this)
							 }).get();
		}
	},

	isExistingComplete : function(responseTree, responseElements, responseHTML, responseJavaScript)
	{
		if (responseHTML == 'yes')
		{
			$(this.currentField + '_exist').setStyle('display', 'block');
			$(this.currentField).focus();
		}
		else
		{
			$(this.currentField + '_exist').setStyle('display', 'none');
		}
	},
	
	createUrl: function(src, destName)
	{
		dest = $(destName);
		
		text = src.value.toLowerCase();
		
		text = text.replace(/ /g, '-');
		text = text.replace(/\//g, '');
		text = text.replace(/\\/g, '');
		text = text.replace(/\(/g, '');
		text = text.replace(/\)/g, '');
		text = text.replace(/,/g, '');
		text = text.replace(/\./g, '');
		text = text.replace(/;/g, '');
		text = text.replace(/!/g, '');
		text = text.replace(/\?/g, '');
		text = text.replace(/%/g, '');
		text = text.replace(/$/g, '');
		text = text.replace(/"/g, '');
		text = text.replace(/'/g, '');
		text = text.replace(/&/g, '-');
		text = text.replace(/:/g, '-');
		text = text.replace(/\*/g, '');
		text = text.replace(/à/g, 'a');
		text = text.replace(/ä/g, 'a');
		text = text.replace(/â/g, 'a');
		text = text.replace(/é/g, 'e');
		text = text.replace(/è/g, 'e');
		text = text.replace(/ë/g, 'e');
		text = text.replace(/ê/g, 'e');
		text = text.replace(/ï/g, 'i');
		text = text.replace(/î/g, 'i');
		text = text.replace(/ì/g, 'i');
		text = text.replace(/ô/g, 'o');
		text = text.replace(/ö/g, 'o');
		text = text.replace(/ò/g, 'o');
		text = text.replace(/ü/g, 'u');
		text = text.replace(/û/g, 'u');
		text = text.replace(/ù/g, 'u');
		text = text.replace(/µ/g, 'u');
		text = text.replace(/»/g, '');
		text = text.replace(/«/g, '');

		dest.value = text;
	},
	
	toLowerCase: function(src, destName)
	{
		dest = $(destName);
		
		text = src.value.toLowerCase();
	
		dest.value = text;
	},
	
	
	limitToNumeric: function(src, destName)
	{
		var ValidChars = "0123456789.";
		var IsNumber=true;
		var n;
		dest = $(destName);
	
		var sText = src.value;
		var dText = '';
	
		for (i = 0; i < sText.length && IsNumber == true; i++) 
		{ 
			n = sText.charAt(i); 
			if (ValidChars.indexOf(n) == -1) 
			{
				IsNumber = false;
			}
			else
			{
				dText += n;
			}
		}
		dest.value = dText;
	
	}
	
});