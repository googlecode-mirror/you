<!-- Article Duplication - Modal window -->

<form name="newArticleForm" id="newArticleForm" action="<?= base_url() ?>admin/article/save_duplicate">

	<!-- Hidden fields -->
	<input id="id_article" name="id_article" type="hidden" value="<?= $id_article ?>" />
	<input id="name" name="name" type="hidden" value="<?= $name ?>" />
	<input type="hidden" id="origin_id_parent" value="<?= $id_page ?>" />

	<!-- Name -->
	<dl>
		<dt class="small">
			<label for="dup_url"><?=lang('ionize_label_url')?></label>
		</dt>
		<dd>
			<input id="dup_url" name="dup_url" class="inputtext required" type="text" value="<?= $name ?>" />
		</dd>
	</dl>
	
	<!-- Menu -->
	<dl>
		<dt class="small">
			<label for="dup_id_menu"><?= lang('ionize_label_menu') ?></label>
		</dt>
		<dd>
			<?= $menus ?>
		</dd>
	</dl>	

	<!-- Parent page -->
	<dl>
		<dt class="small">
			<label for="dup_id_page"><?= lang('ionize_label_parent') ?></label>
		</dt>
		<dd>
			<?= $parent_select ?>
		</dd>
	</dl>	

	<!-- Order in the new page -->
	<dl>
		<dt class="small">
			<label for="ordering"><?= lang('ionize_label_ordering') ?></label>
		</dt>
		<dd>
			<select name="ordering_select" id="ordering_select">
				<option value="first"><?= lang('ionize_label_ordering_first') ?></option>
				<option value="last"><?= lang('ionize_label_ordering_last') ?></option>
			</select>
		</dd>
	</dl>

</form>


<!-- Save / Cancel buttons
	 Must be named bSave[windows_id] where 'window_id' is the used ID for the window opening through MUI.formWindow()
--> 
<div class="buttons">
	<button id="bSaveDuplicateArticle" type="button" class="button yes right"><?= lang('ionize_button_save_close') ?></button>
	<button id="bCancelDuplicateArticle"  type="button" class="button no "><?= lang('ionize_button_cancel') ?></button>
</div>


<script type="text/jaavscript">

	// Update parent select list when menu change
	$('dup_id_menu').addEvent('change', function(e)
	{
		e.stop();
		
		var xhr = new Request.HTML(
		{
			url: base_url + 'admin/page/get_parents_select/' + $('dup_id_menu').value + '/' + '0' + '/' + $('origin_id_parent').value,
			method: 'post',
			update: 'dup_id_page'
		}).send();
	});

</script>
