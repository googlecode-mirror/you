<div class="toolbox divider nobr" id="tArticleFormSubmit">
	<input id="articleFormSubmit" type="button" class="submit" value="<?= lang('ionize_button_save_article') ?>" />
</div>

<div class="toolbox divider">
	<span class="iconWrapper" id="articleDuplicateButton"><img src="<?=base_url()?><?=Theme::get_theme_path()?>images/icon_16_copy_article.gif" width="16" height="16" alt="<?= lang('ionize_button_duplicate_article') ?>" title="<?= lang('ionize_button_duplicate_article') ?>" /></span>
</div>

<div class="toolbox divider">
	<input type="button" class="toolbar-button" id="sidecolumnSwitcher" value="<?= lang('ionize_label_hide_options') ?>" />
</div>

<script type="text/javascript">

	/**
	 * Form save action
	 * see init.js for more information about this method
	 *
	 */
	MUI.setFormSubmit('articleForm', 'articleFormSubmit', 'admin/article/save');
	
	/**
	 * Article duplication button
	 * Next to the save button...
	 *
	 */
	var id = $('id_article').value;
	if ( ! id)
	{
		$('articleDuplicateButton').hide();
		$('tArticleFormSubmit').removeClass('divider');
	}
	else
	{
		$('articleDuplicateButton').addEvent('click', function(e)
		{
			var id_page = $('id_page').value;
			var name = $('name').value;
			
			MUI.formWindow('DuplicateArticle', 'newArticleForm', 'ionize_title_duplicate_article', 'admin/article/duplicate/' + id + '/' + id_page + '/' + name, {width:450, height:135});
		});
	}
		
	/**
	 * Options show / hide button
	 *
	 */
	MUI.initSideColumn();
	
	/**
	 * Save with CTRL+s
	 *
	 */
	MUI.addFormSaveEvent('articleFormSubmit');

</script>
