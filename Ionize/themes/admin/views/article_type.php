<?php

/**
 * Modal window for Type creation / edition
 *
 */
?>

<form name="typeForm" id="typeForm" action="<?= base_url() ?>admin/article_type/save">

	<!-- Hidden fields -->
	<input id="id_type" name="id_type" type="hidden" value="<?= $id_type ?>" />
	<input id="parent" name="parent" type="hidden" value="<?= $parent ?>" />
	<input id="id_parent" name="id_parent" type="hidden" value="<?= $id_parent ?>" />

	

	<!-- Name -->
	<dl>
		<dt class="small">
			<label for="type"><?=lang('ionize_label_type')?></label>
		</dt>
		<dd>
			<input id="type" name="type" class="inputtext required" type="text" value="<?= $type ?>" />
		</dd>
	</dl>
	

</form>


<!-- Save / Cancel buttons
	 Must be named bSave[windows_id] where 'window_id' is the used ID for the window opening through MUI.formWindow()
--> 
<div class="buttons">
	<button id="bSaveType" type="button" class="button yes right"><?= lang('ionize_button_save_close') ?></button>
	<button id="bCancelType"  type="button" class="button no "><?= lang('ionize_button_cancel') ?></button>
</div>


