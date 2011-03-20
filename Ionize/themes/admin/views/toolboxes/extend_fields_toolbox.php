
<div class="toolbox divider">
	<input type="button" class="toolbar-button plus" id="addextendfieldMedia" value="<?= lang('ionize_title_extend_new_media_extend') ?>" />
</div>

<div class="toolbox divider">
	<input type="button" class="toolbar-button plus" id="addextendfieldArticle" value="<?= lang('ionize_title_extend_new_article_extend') ?>" />
</div>

<div class="toolbox divider">
	<input type="button" class="toolbar-button plus" id="addextendfieldPage" value="<?= lang('ionize_title_extend_new_page_extend') ?>" />
</div>

<!--
<div class="toolbox divider">
	<input type="button" class="toolbar-button plus" id="addextendfield" value="<?= lang('ionize_title_extend_field_new') ?>" />
</div>
-->

<script type="text/javascript">

	/**
	 * Buttons events
	 *
	 */
	$('addextendfieldMedia').addEvent('click', function(e)
	{
		MUI.formWindow('extendfield', 'extendfieldForm', 'ionize_title_extend_fields', 'admin/extend_field/get_form/media', {width:400, height:330});
	});
	
	$('addextendfieldArticle').addEvent('click', function(e)
	{
		MUI.formWindow('extendfield', 'extendfieldForm', 'ionize_title_extend_fields', 'admin/extend_field/get_form/article', {width:400, height:330});
	});
	
	$('addextendfieldPage').addEvent('click', function(e)
	{
		MUI.formWindow('extendfield', 'extendfieldForm', 'ionize_title_extend_fields', 'admin/extend_field/get_form/page', {width:400, height:330});
	});
/*	
	$('addextendfieldSetting').addEvent('click', function(e)
	{
		MUI.formWindow('extendfield', 'extendfieldForm', 'ionize_title_extend_fields', 'admin/extend_field/get_form/settings', {width:400, height:330});
	});
*/	

</script>
