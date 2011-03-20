
<!-- Category edit view - Modal window -->

<form name="categoryForm" id="categoryForm" action="<?= base_url() ?>admin/category/save">

	<!-- Hidden fields -->
	<input id="id_category" name="id_category" type="hidden" value="<?= $id_category ?>" />
	<input id="parent" name="parent" type="hidden" value="<?= $parent ?>" />
	<input id="id_parent" name="id_parent" type="hidden" value="<?= $id_parent ?>" />
	<input id="ordering" name="ordering" type="hidden" value="<?= $ordering ?>" />


	<!-- Name -->
	<dl>
		<dt class="small">
			<label for="name"><?=lang('ionize_label_name')?></label>
		</dt>
		<dd>
			<input id="name" name="name" class="inputtext required" type="text" value="<?= $name ?>" />
		</dd>
	</dl>
	
	<fieldset id="blocks">

		<!-- Tabs -->
		<div class="tab">
			<ul class="tab-content">
	
				<?php foreach(Settings::get_languages() as $l) :?>
					<li id="tabcategory-<?= $l['lang'] ?>"><a><span><?= ucfirst($l['name']) ?></span></a></li>
				<?php endforeach ;?>

			</ul>
		</div>

		<!-- Text block -->
		<?php foreach(Settings::get_languages() as $l) :?>
			
			<?php $lang = $l['lang']; ?>

			<div id="blockcategory-<?= $lang ?>" class="block category">

				<!-- title -->
				<dl>
					<dt class="small">
						<label for="title"><?= lang('ionize_label_title') ?></label>
					</dt>
					<dd>
						<input id="title_<?= $lang ?>" name="title_<?= $lang ?>" class="inputtext w180" type="text" value="<?= ${$lang}['title'] ?>"/>
					</dd>
				</dl>

				<!-- description -->
				<dl>
					<dt class="small">
						<label for="description"><?= lang('ionize_label_description') ?></label>
					</dt>
					<dd>
						<input id="description_<?= $lang ?>" name="description_<?= $lang ?>" class="inputtext w180" type="text" value="<?= ${$lang}['description'] ?>"/>
					</dd>
				</dl>
			
			</div>
		
		<?php endforeach ;?>
		
	</fieldset>

</form>


<!-- Save / Cancel buttons
	 Must be named bSave[windows_id] where 'window_id' is the used ID for the window opening through MUI.formWindow()
--> 
<div class="buttons">
	<button id="bSaveCategory" type="button" class="button yes right"><?= lang('ionize_button_save_close') ?></button>
	<button id="bCancelCategory"  type="button" class="button no "><?= lang('ionize_button_cancel') ?></button>
</div>

<script type="text/javascript">

	/** 
	 * Show current tabs
	 */
	displayBlock('.category', '<?= Settings::get_lang('first') ?>', 'category');
	
	
	/** 
	 * Add events to tabs
	 * - Lang Tab Events 
	 * - Options Tab Events
	 * - Wysiwyg buttons
	 */
	<?php foreach(Settings::get_languages() as $l) :?>

		$('tabcategory-<?= $l["lang"] ?>').addEvent('click', function()
		{ 
			displayBlock('.category', '<?= $l["lang"] ?>', 'category'); 
		});

	<?php endforeach ;?>

</script>

