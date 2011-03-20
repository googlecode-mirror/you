<div class="toolbox divider nobr">
	<input id="translationFormSubmit" type="button" class="submit" value="<?= lang('ionize_button_save') ?>" />
</div>

<div class="toolbox divider">
	<input type="button" class="toolbar-button plus" id="btnExpand" value="<?= lang('ionize_label_expand_all') ?>" />
</div>

<script type="text/javascript">

	/**
	 * Form save action
	 * see init.js for more information about this method
	 *
	 */
	MUI.setFormSubmit('translationForm', 'translationFormSubmit', 'admin/translation/save');

	/**
	 * Expand / Collapse button
	 *
	 */
	$('btnExpand').store('status', 'collapse');
	$('btnExpand').setStyle('width', '90px');
	
	$('btnExpand').addEvent('click', function() 
	{
		if (this.retrieve('status') == 'collapse')
		{
			$$('#block .toggler').each(function(el){
				el.fx.show();
				el.addClass('expand');
				el.getParent().addClass('highlight');
			});
			this.value = Lang.get('ionize_label_collapse_all');
			this.addClass('minus').removeClass('plus').store('status', 'expand');
		}
		else
		{
			$$('#block .toggler').each(function(el){
				el.fx.hide();
				el.removeClass('expand');
				el.getParent().removeClass('highlight');
			});
			this.value = Lang.get('ionize_label_expand_all');
			this.addClass('plus').removeClass('minus').store('status', 'collapse');
		}
	});


	/**
	 * Save with CTRL+s
	 *
	 */
	MUI.addFormSaveEvent('translationFormSubmit');

</script>
