initializeMenu = function(){

	// Default padding
	var default_padding= { top: 12, right: 15, bottom: 8, left: 15 };

	// Dashboard link...
	if ($('dashboardLink')){ 
		$('dashboardLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_title_welcome'),
				url : base_url + 'admin/dashboard'		
			});
		});
	}

	// Content : Manage Menu ...
	if ($('menuLink')){ 
		$('menuLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_title_menu'),
				url : base_url + 'admin/menu'		
			});
		});
	}

	// Content : New Page...
	if ($('newPageLink')){ 
		$('newPageLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_title_new_page'),
				url : base_url + 'admin/page/create/0'		
			});
		});
	}

	// Translations
	if ($('translationLink')){ 
		$('translationLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_title_translation'),
				url : base_url + 'admin/translation/'
			});
		});
	}

	// Content : Media manager
	if ($('mediaManagerLink')){ 
		$('mediaManagerLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_menu_media_manager'),
				url : base_url + 'admin/media/get_media_manager',
				padding: {top:0, left:0, right:0}
			});
		});
	}

	// Content : Extended fields
	if ($('extendfieldsLink')){ 
		$('extendfieldsLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_menu_extend_fields'),
				url : base_url + 'admin/extend_field/index'
			});
		});
	}


	// Modules : List
	if ($('modulesLink')){ 
		$('modulesLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_title_modules'),
				url : base_url + 'admin/modules/'
			});
		});
	}
	
	// Themes
	if ($('themesLink')){ 
		$('themesLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_title_theme'),
				url : base_url + 'admin/setting/themes/'
			});
		});
	}
	
	// Settings : Global Website settings
	if ($('settingLink')){ 
		$('settingLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_menu_site_settings_global'),
				url : base_url + 'admin/setting'
			});
		});
	}

	// Settings : Technical settings
	if ($('technicalSettingLink')){ 
		$('technicalSettingLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_menu_site_settings_technical'),
				url : base_url + 'admin/setting/technical'
			});
		});
	}

	// Settings : Languages...
	if ($('languagesLink')){ 
		$('languagesLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_menu_languages'),
				url : base_url + 'admin/lang'
			});
		});
	}

	// Settings : Users...
	if ($('usersLink')){ 
		$('usersLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.updateContent({
				element: $('mainPanel'),
				title: Lang.get('ionize_menu_users'),
				url : base_url + 'admin/users'
			});
		});
	}

	// About
	MUI.aboutWindow = function() {
		new MUI.Modal({
			id: 'about',
			title: 'MUI',			
			contentURL: base_url + 'admin/desktop/get/about',
			type: 'modal2',
			width: 360,
			height: 210,
			y:200,
			padding: { top: 70, right: 12, bottom: 10, left: 22 },
			scrollbars: false
		});
	}
	if ($('aboutLink')) {
		$('aboutLink').addEvent('click', function(e){
			new Event(e).stop();
			MUI.aboutWindow();
		});
	}


	// Deactivate menu header links
	$$('a.returnFalse').each(function(el){
		el.addEvent('click', function(e){
			new Event(e).stop();
		});
	});

	MUI.myChain.callChain();

}
