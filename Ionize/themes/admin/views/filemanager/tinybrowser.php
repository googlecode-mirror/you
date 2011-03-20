
<?php

// Path to TinyBrowser
$path_to_filemanager = $this->config->item('base_path') . '/themes/' . Settings::get('theme_admin') . '/javascript/tinymce/jscripts/tiny_mce/plugins/tinybrowser/';

// URL to TinyBrowser
$url_to_filemanager = base_url() . '/themes/' . Settings::get('theme_admin') . '/javascript/tinymce/jscripts/tiny_mce/plugins/tinybrowser/';

// Config file
require_once($path_to_filemanager . 'config_tinybrowser.php');

?>

<p style="padding:10px;">
	<a id="imageManagerLink" class="pl5" href=""><img src="<?= $url_to_filemanager ?>img/imagemanager.png" /> <?= lang('ionize_image_manager') ?></a>
	<a id="fileManagerLink" class="pl5" href=""><img src="<?= $url_to_filemanager ?>img/filemanager.png" /> <?= lang('ionize_file_manager') ?></a>
</p>


<form name="fileManagerForm">

	<div style="overflow:auto;">
		<iframe id="filemanager_iframe" src="<?=$url_to_filemanager?>tinybrowser.php?type=<?= $mode ?>&feid=hiddenFile&standalone=true" style="width: 100%; height: 500px; border:none;padding-bottom:20px;"></iframe>
	</div>
	
	<input type="hidden" id="hiddenFile" />

</form>


<script type="text/javascript">

	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox();


	// link on imageManager Icon
	$('imageManagerLink').addEvent('click', function(e){
		new Event(e).stop();
		MUI.updateContent({
			element: $('mainPanel'),
			title: Lang.get('ionize_menu_media_manager'),
			url : base_url + 'admin/media/get_media_manager/image',
			padding: {top:0, left:0, right:0}
		});
	});

	// link on fileManager Icon
	$('fileManagerLink').addEvent('click', function(e){
		new Event(e).stop();
		MUI.updateContent({
			element: $('mainPanel'),
			title: Lang.get('ionize_menu_media_manager'),
			url : base_url + 'admin/media/get_media_manager',
			padding: {top:0, left:0, right:0}
		});
	});



</script>

