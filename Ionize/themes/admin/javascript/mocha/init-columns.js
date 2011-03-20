/** 
 * Ionize UI Init 
 *
 */

initializeColumns = function() {

	/* 
	 * Main UI options	
	 *
	 */
	
	// Windows Corner radius
	windowOptions = MUI.Windows.windowOptions;
	windowOptions.cornerRadius = 0;
	
	// Windows Shadows
	// windowOptions.shadowBlur = 5;	
	MUI.Window.implement({ options: windowOptions });


	/*
	 * Create Columns
	 *	 
	 */	 
	new MUI.Column({
		id: 'sideColumn',
		placement: 'left',
		sortable: false,
		width: 310,
		resizeLimit: [222, 600]
	});

	new MUI.Column({
		id: 'mainColumn',
		placement: 'main',	
		sortable: false,
		resizeLimit: [100, 500],
		evalScripts: true
	});

	// Add Site structure panel to side column
	new MUI.Panel({
		id: 'structurePanel',
		title: Lang.get('ionize_title_structure'),
		loadMethod: 'xhr',
		contentURL: base_url + 'admin/core/get_structure',
		column: 'sideColumn',
		panelBackground:'#f2f2f2',
		padding: { top: 15, right: 0, bottom: 8, left: 15 },
		headerToolbox: true,
		headerToolboxURL: base_url + 'admin/core/get/toolboxes/structure_toolbox',
		headerToolboxOnload: function(){
			$('addPageButton').addEvent('click', function(e)
			{
				new Event(e).stop();
		
				MUI.updateContent(
				{
					element: $('mainPanel'),
					title: Lang.get('ionize_title_new_page'),
					loadMethod: 'xhr',
					url: base_url + 'admin/page/create/0'
				});
			});
		}
	});

	// Add Info panel to side column
/*
	new MUI.Panel({
		id: 'infoPanel',
		title: 'Debug',
		loadMethod: 'xhr',
		contentURL: base_url + 'admin/core/get_info',
		column: 'sideColumn',
		panelBackground: '#fff',
			padding: { top: 15, right: 15, bottom: 8, left: 15 },
		onContentLoaded: function(c) 
		{
//			log = new Log('debug');
		}		
		
	});
*/

	// Add panels to main column	
	new MUI.Panel({
		id: 'mainPanel',
		title: Lang.get('ionize_title_welcome'),
		loadMethod: 'xhr',
		contentURL: base_url + 'admin/dashboard',
		padding: { top: 15, right: 15, bottom: 8, left: 15 },
		addClass: 'pad-maincolumn',
		column: 'mainColumn',
		collapsible: false,
		panelBackground: '#fff',
		headerToolbox: true,
		headerToolboxURL: base_url + 'admin/core/get/toolboxes/empty_toolbox'
	});

	MUI.myChain.callChain();
}
