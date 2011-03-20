<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?= Settings::get('site_name') ? Settings::get('site_name').' | ' : '' ?>Administration</title>

<meta http-equiv="imagetoolbar" content="no" />
<link rel="shortcut icon" href="<?=base_url()?>themes/admin/images/favicon.ico" type="image/x-icon" >

<!-- Mocha adapted CSS -->
<link rel="stylesheet" href="<?=base_url()?>themes/admin/css/content.css" type="text/css" />
<link rel="stylesheet" href="<?=base_url()?>themes/admin/css/ui.css" type="text/css" />
<link rel="stylesheet" href="<?=base_url()?>themes/admin/css/content-addon.css" type="text/css" />

<!-- Ionize CSS -->
<link rel="stylesheet" href="<?=base_url()?>themes/admin/css/form.css" type="text/css" />
<link rel="stylesheet" href="<?=base_url()?>themes/admin/css/tabs.css" type="text/css" />

<!-- External librairies CSS -->
<link type="text/css" rel="stylesheet" href="<?=base_url()?>themes/admin/javascript/datepicker/css/dashboard/datepicker_dashboard.css" />
<link type="text/css" rel="stylesheet" href="<?=base_url()?>themes/admin/javascript/sortableTable/sortableTable.css" />

<!--[if IE]>
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/excanvas-compressed.js"></script>		
<![endif]-->

<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-1.2.5-core-yc.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-1.2.4.4-more-yc.js"></script>

<!--
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-1.3-core-full-nocompat-yc.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-1.3-more-yc.js"></script>
-->

<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/drag.clone.js"></script>

<!-- Base URL & languages translations available for javascript -->
<script type="text/javascript">
	
	/** 
	 * Global base_url value.
	 * Used by mocha-init and should be used by any javascript class or method which needs to access to resources
	 */
	var base_url = '<?= base_url().Settings::get_lang('current') ?>/';

	/** 
	 * Show help tips.
	 * Used by mocha init-content
	 */
	var show_help_tips = '<?= Settings::get('show_help_tips') ?>';

	/** 
	 * Gets all the Ionize lang items and put them into a Lang hash object
	 * To get an item : Lang.get('php_lang_item_key');
	 */
	<?php $this->load->view('javascript_lang');	?>

</script>

<!--
	mocha.js.php is for development. It is not recommended for production.
	For production it is recommended that you used a compressed version of either
	the output from mocha.js.php or mocha.js. You could also list the
	necessary source files individually here in the header though that will
	create a lot more http requests than a single concatenated file.
		

-->
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/source/Core/Core.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/source/Layout/Layout.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/source/Layout/Dock.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/source/Window/Window.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/source/Window/Modal.js"></script>

<!-- Ionize scripts -->
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/ionize_functions.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/ionizeFormManager.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/ionizeMediaManager.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/ionizeItemManager.js"></script>

<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/ionize.js"></script>

<!-- Log -->
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/log.js"></script>

<!-- External librairies -->
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/swfobject.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/datepicker/datepicker.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/sortableTable/sortableTable.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/tinymce/jscripts/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript" src="<?= base_url()?><?= Theme::get_theme_path() ?>javascript/edit_area/edit_area_compressor.php"></script>

<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/sortingTable/sorting_table.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/sortingTable/paginating_table.js"></script>


<!-- tinyMCE initialization -->

<!-- If users templates, add them to the init object -->
<?php if (is_file($this->config->item('base_path').'/themes/'.Settings::get('theme').'/assets/templates/tinymce_templates.js' )) :?>
	<script type="text/javascript" src="<?=base_url()?>themes/<?=Settings::get('theme')?>/assets/templates/tinymce_templates.js"></script>
<?php else :?>
	<script type="text/javascript">
		var getTinyTemplates = false;
	</script>
<?php endif ;?>

<script type="text/javascript">

	
	/* If user's theme has a tinyMCE.css content CSS file, load it.
	 * else, load the standard tinyMCE content CSS file
	 *
	 */
	<?php $theme_path = base_url().'themes/'.Settings::get('theme').'/'; ?>
	
	<?php if (is_file($this->config->item('base_path').'/themes/'.Settings::get('theme').'/assets/css/tinyMCE.css' )) :?>
		var tinyCSS = '<?= $theme_path.'assets/css/tinyMCE.css' ?>';
	<?php else :?>
		var tinyCSS = '<?= base_url().'themes/admin/css/tinyMCE.css' ?>';
	<?php endif ;?>

	// Base init
	tinyMCE_GZ.init();

	var tinyMCEParam = {
		mode : 'textareas',
		theme : 'advanced',
		language : '<?= Settings::get_lang() ?>',
		entity_encoding : 'raw',
		editor_selector : 'tinyTextarea',
		height:'450',
		width:'100%',
		dialog_type : 'modal',
		extended_valid_elements  : "ion:*, a[href</<ion:*]",
		verify_html : false,
		relative_urls : false,
		auto_cleanup_word : false,
		plugins : 'codemirror,safari,nonbreaking,media,preelementfix,preview,directionality,paste,fullscreen,template,table,<?php if( Settings::get('filemanager') == 'filemanager' ) :?>filemanager,imagemanager<?php else :?>tinybrowser<?php endif ;?>,advimage,advlink',
		flash_menu : 'false',
/*
		apply_source_formatting:true,
		remove_linebreaks: true,
*/
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_resizing : true,
		theme_advanced_resizing_use_cookie : false,
		theme_advanced_path_location : 'bottom',
		theme_advanced_blockformats : 'p,h2,h3,h4,h5,pre,div',
		theme_advanced_disable : 'help',
		theme_advanced_buttons1 : 'fullscreen,preview,undo,redo,removeformat,|,pastetext,pasteword,selectall,|,anchor,link,unlink,image,media,charmap,|,codemirror',
		theme_advanced_buttons2 : 'bold,italic,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,hr,blockquote,nonbreaking,|,template,|,formatselect,styleselect',
		theme_advanced_buttons3 : 'tablecontrols',
		content_css : tinyCSS
		<?php if( Settings::get('filemanager') == 'tinybrowser' ) :?>
		,file_browser_callback : 'tinyBrowser'
		<?php endif ;?>
	};

	// If users templates, add them to the init object
	if (getTinyTemplates != false)
	{
		tinyMCEParam.template_templates = getTinyTemplates('<?=base_url()?>themes/<?=Settings::get('theme')?>/assets/templates/');
	}
	

	tinyMCE.init(tinyMCEParam);

</script>


<!-- FileManager & ImageManager -->
<?php if( Settings::get('filemanager') == 'filemanager' ) :?>

	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/imagemanager/js/mcimagemanager.js"></script>
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/filemanager/js/mcfilemanager.js"></script>

<?php elseif( Settings::get('filemanager') == 'tinybrowser' ) :?>

	<!-- TinyBrowser in standalone mode -->
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/tinybrowser/tb_standalone.js.php"></script>

	<!-- TinyBrowser for TinyMCE -->
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/tinybrowser/tb_tinymce.js.php"></script>

<?php elseif( Settings::get('filemanager') == 'mooFilemanager' ) :?>

	<!-- Mootools Filemanager -->
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Source/FileManager.js"></script>

	<?php if (is_file($this->config->item('base_path').'/themes/admin/javascript/mootools-filemanager/Language/Language.' . Settings::get_lang() . 'js' )) :?>
		<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Language/Language.<?= Settings::get_lang() ?>.js"></script>
	<?php else :?>
		<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Language/Language.en.js"></script>
	<?php endif ;?>	
	
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Source/Additions.js"></script>
	
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Source/Uploader/Fx.ProgressBar.js"></script>
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Source/Uploader/Swiff.Uploader.js"></script>

	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Source/Uploader.js"></script>
	<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mootools-filemanager/Source/Gallery.js"></script>

	<script type="text/javascript">
	/*
		manager1 = new FileManager({
			url: '<?=base_url()?>themes/admin/javascript/mootools-filemanager/manager.php',
			assetBasePath: '<?=base_url()?>themes/admin/javascript/mootools-filemanager/Assets',
			language: 'en',
			uploadAuthData: {session: 'MySessionId'}
		});
		manager1.show();
	*/
	
	</script>


<?php endif ;?>



<!-- UI initialization -->
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/init-columns.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/init-windows.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/init-menu.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/init-forms.js"></script>
<script type="text/javascript" src="<?=base_url()?>themes/admin/javascript/mocha/init-content.js"></script>

<script type="text/javascript">

	// Initialize MUI when the DOM is ready
	window.addEvent('load', function(){ //using load instead of domready for IE8
	
		MUI.myChain = new Chain();
		MUI.myChain.chain(
			function(){MUI.Desktop.initialize();},
			function(){MUI.Dock.initialize();},
			function(){initializeContent();},	
			function(){initializeForms();},	
			function(){initializeMenu();},		
			function(){initializeWindows();},
			function(){initializeColumns();}	
		).callChain();	
	
	});

</script>


<script type="text/javascript">
	/** 
	 * Calendars
	 *
	 */
	var datePicker = new DatePicker('.date', {pickerClass: 'datepicker_dashboard', timePicker:true, format: 'd.m.Y H:i:s', inputOutputFormat:'d.m.Y H:i:s', allowEmpty:true, useFadeInOut:false, positionOffset: {x:-10,y:0}});

</script>

</head>
<body>

<div id="desktop">

	<div id="desktopHeader">

		<div id="desktopTitlebarWrapper">
			<div id="desktopTitlebar">
				
				<h1 class="applicationTitle">ionize 1.1</h1>
				
				<div id="topNav">
					<ul class="menu-right">
						<li><?= lang('ionize_logged_as') ?> : <?= $current_user['screen_name'] ?></li>
						<li><a href="<?= base_url() ?>" target="_blank"><?= lang('ionize_website') ?></a></li>
						<li><a href="<?= base_url() ?>admin/user/logout"><?= lang('ionize_logout') ?></a></li>
						<li>
							<?php foreach(Settings::get('admin_languages') as $lang) :?>
								<a href="<?= base_url().$lang ?>/admin"><img src="<?= base_url() ?>themes/admin/images/world_flags/flag_<?= $lang ?>.gif" /></a>
							<?php endforeach ;?>
						</li>

					</ul>
				</div>

				<div id="desktopNavbar">
					<ul>
						<li><a id="dashboardLink">Dashboard</a></li>
						<li><a class="returnFalse" href=""><?= lang('ionize_menu_content') ?></a>	
							<ul>
								<?php if($this->connect->is('super-admins')) :?>
									<li><a id="menuLink" href=""><?=lang('ionize_menu_menu')?></a></li>
								<?php endif ;?>
								<li><a id="newPageLink" href="<?= site_url('admin/page/create/0') ?>"><?= lang('ionize_menu_page') ?></a></li>
								<li><a id="translationLink" href=""><?= lang('ionize_menu_translation') ?></a></li>
								<li class="divider"><a id="mediaManagerLink" href=""><?= lang('ionize_menu_media_manager') ?></a></li>
								<?php if ($this->connect->is('admins') && Settings::get('use_extend_fields') == '1') :?>
									<li class="divider"><a id="extendfieldsLink" href=""><?= lang('ionize_menu_extend_fields') ?></a></li>
								<?php endif ;?>
							</ul>
						</li>
						<?php if($this->connect->is('editors')) :?>
						<li><a class="returnFalse" href=""><?= lang('ionize_menu_modules') ?></a>
							<ul>
								<!-- Module Admin controllers links -->
								<?php foreach($modules as $uri => $module) :?>
									<?php if($this->connect->is($module['access_group'])) :?>
										<li><a class="modules" id="<?= $uri ?>ModuleLink" href="<?= base_url().Settings::get_lang('current') ?>/admin/module/<?= $uri ?>/<?= $uri ?>/index"><?= $module['name'] ?></a></li>
									<?php endif ;?>								
								<?php endforeach ;?>
								<?php if($this->connect->is('admins')) :?>
									<li class="divider"><a id="modulesLink" href=""><?=lang('ionize_menu_modules_admin')?></a></li>
								<?php endif ;?>
							</ul>
						</li>
						<?php endif ;?>
						<li><a class="returnFalse" href=""><?= lang('ionize_menu_tools') ?></a>
							<ul>
								<li><a id="googleAnalyticsLink" href="https://www.google.com/analytics/reporting/login" target="_blank">Google Analytics</a></li>
							</ul>
						</li>

						<li><a class="returnFalse" href=""><?=lang('ionize_menu_settings')?></a>
							<ul>
								<li><a id="languagesLink" href=""><?=lang('ionize_menu_languages')?></a></li>
								<?php if($this->connect->is('admins')) :?>
									<li><a id="usersLink" href=""><?=lang('ionize_menu_users')?></a></li>
								<?php endif ;?>
								<?php if($this->connect->is('super-admins')) :?>
									<li><a id="themesLink"><?=lang('ionize_menu_theme')?></a></li>
								<?php endif ;?>
								<li class="divider"><a id="settingLink" href=""><?=lang('ionize_menu_site_settings')?></a></li>
								<?php if($this->connect->is('super-admins')) :?>
									<li><a id="technicalSettingLink" href=""><?=lang('ionize_menu_technical_settings')?></a></li>
								<?php endif ;?>
							</ul>
						</li>
						<li><a class="returnFalse" href=""><?= lang('ionize_menu_help') ?></a>
							<ul>
								<li><a href="javascript:MUI.notification('notice', 'Coming soon...')"><?= lang('ionize_menu_documentation') ?></a></li>
								<li class="divider"><a id="aboutLink" href="<?php echo base_url() ;?>themes/admin/views/about.html"><?= lang('ionize_menu_about') ?></a></li>
							</ul>
						</li>
					</ul>	
					<div class="toolbox">
						<div id="spinnerWrapper"><div id="spinner"></div></div>		
					</div>

					
				</div><!-- /desktopNavbar -->
			</div><!-- /desktopTitlebar -->
		</div>
	</div><!-- /desktopHeader -->

	<div id="dockWrapper">
		<div id="dock">
			<div id="dockPlacement"></div>
			<div id="dockAutoHide"></div>
			<div id="dockSort"><div id="dockClear" class="clear"></div></div>
		</div>
	</div>

	<!-- Mocha page content -->
	<div id="pageWrapper"></div>



</div><!-- /desktop -->


<script type="text/javascript">

	/*
	 * Add modules links events
	 */
	$$('.modules').each(function(item, idx) 
	{
		item.addEvent('click', function(e)
		{
			var e = new Event(e).stop();
			
			MUI.updateContent({
				element: $('mainPanel'),
				title: item.get('text'),
				url : item.getProperty('href')
			});
		});
	});


</script>


</body>


</html>

