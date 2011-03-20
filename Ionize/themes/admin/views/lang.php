
<div id="sidecolumn" class="close">

	<div id="options">

		<!-- New language -->
		<h3 class="toggler"><?=lang('ionize_title_add_language')?></h3>

		<div class="element">

			<form name="newLangForm" id="newLangForm" method="post" action="<?= base_url() ?>admin/lang/save">

				<!-- Lang Code -->
				<dl>
					<dt class="small">
						<label for="lang_new"><?=lang('ionize_label_code')?></label>
					</dt>
					<dd>
						<input id="lang_new" name="lang_new" class="inputtext w40" type="text" value="" />
					</dd>
				</dl>

				<!-- Name -->
				<dl>
					<dt class="small">
						<label for="name_new"><?=lang('ionize_label_name')?></label>
					</dt>
					<dd>
						<input id="name_new" name="name_new" class="inputtext w140" type="text" value=""/><br />
					</dd>
				</dl>

				<!-- Online  -->
				<dl>
					<dt class="small">
						<label for="online_new"><?=lang('ionize_label_online')?></label>
					</dt>
					<dd>
						<input id="online_new" name="online_new" class="inputcheckbox" type="checkbox" value="1" />
					</dd>
				</dl>
			
				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_new" type="submit" class="submit" value="<?= lang('ionize_button_save_new_lang') ?>" />
					</dd>
				</dl>
				
			</form>

		</div> <!-- /element -->
		
		
		<!-- Advanced actins with content -->
		<h3 class="toggler"><?=lang('ionize_title_advanced_language')?></h3>

		<div class="element">

			<p><?=lang('ionize_notify_advanced_language')?></p>
	
			<form name="cleanLangForm" id="cleanLangForm" method="post">

				<input id="submit_clean" type="submit" class="submit" value="<?= lang('ionize_button_clean_lang_tables') ?>" />
				<label title="<?= lang('ionize_help_clean_lang_tables') ?>"></label>
				
			</form>
			
			<!--
			<h4>Content duplication</h4>
			
			<form name="copyLangForm" id="copyLangForm" method="post" action="<?= base_url() ?>admin/lang/clean">
			
			
			
			</form>
			
			-->
		
		</div>

	</div> <!-- /options -->

</div> <!-- /sidecolumn -->


<!-- Main Column -->
<div id="maincolumn">

	<!-- No languages -->
	<?php if (!$languages = Settings::get_languages()) :?>

		<p><?= lang('ionize_message_no_languages') ;?></p>

	<!-- Existing languages -->
	<?php else : ?>

		<form name="existingLangForm" id="existingLangForm" method="post" action="<?= base_url() ?>admin/lang/update">


		<input name="current_default_lang" id="current_default_lang" type="hidden" value="<?= Settings::get_lang('default'); ?>"/>


		<h3><?=lang('ionize_title_existing_languages')?></h3>
		

		<!-- Sortable UL -->
		<ul id="langContainer" class="sortable">

			<?php foreach($languages as $lang) :?>

				<?php
					$code = $lang['lang'];
					$name = $lang['name'];
				?>

				<li id="lang_<?= $code ?>" class="sortme ">

					<!-- Drag icon -->
					<div class="drag" style="float:left;height:100px;">
						<img src="<?=base_url()?>themes/admin/images/icon_16_ordering.png" />
					</div>

					<!-- Lang Code -->
					<dl>
						<dt>
							<label for="lang_<?= $code ?>"><?=lang('ionize_label_code')?></label>
						</dt>
						<dd>
							<input name="lang_<?= $code ?>" id="lang_<?=$code?>" class="inputtext" type="text" value="<?= $code ?>"/>
							
							<!-- Delete button -->
							<img title="<?=lang('ionize_button_delete')?>" onclick="javascript:langManager.deleteItem('<?= $code ?>')" class="inputicon pointer" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />
						</dd>
					</dl>

					<!-- Name -->
					<dl>
						<dt>
							<label for="name_<?= $code ?>"><?=lang('ionize_label_name')?></label>
						</dt>
						<dd>
							<input name="name_<?= $code ?>" id="name_<?=$code?>" class="inputtext" type="text" value="<?= $name ?>"/>
						</dd>
					</dl>

					<!-- Online ? -->
					<dl>
						<dt>
							<label for="online_<?= $code ?>"><?=lang('ionize_label_online')?></label>
						</dt>
						<dd>
							<input id="online_<?= $code ?>" name="online_<?= $code ?>" <?php if ($lang['online'] == '1'):?>checked="checked"<?php endif;?> class="inputcheckbox" type="checkbox" value="1" />
						</dd>
					</dl>

					<!-- Default ? -->
					<dl>
						<dt>
							<label for="def_<?= $code ?>"><?=lang('ionize_label_default')?></label>
						</dt>
						<dd>
							<input id="def_<?= $code ?>" <?php if (Settings::get_lang('default') == $code ):?>checked="checked"<?php endif;?> type="radio" name="default_lang" class="inputradio" value="<?= $code ?>" />
						</dd>
					</dl>

				</li>

			<?php endforeach ;?>

			</ul>

		</form>

	<?php endif ;?>

</div>


<script type="text/javascript">
	

	/**
	 * New lang form action
	 * see init-form.js for more information about this method
	 *
	 */
	MUI.setFormSubmit('newLangForm', 'submit_new', 'admin/lang/save');


	/**
	 * Clean Lang tables form action
	 *
	 */
	MUI.setFormSubmit('cleanLangForm', 'submit_clean', 'admin/lang/clean_tables', {message:Lang.get('ionize_confirmation_clean_lang')});

	 
	/**
	 * Copy Lang tables form action
	 * 
	 * @TODO...
	 *
	 */
//	MUI.setFormSubmit('copyLangForm', 'submit_copy', 'admin/lang/copy_lang_content', {message:Lang.get('ionize_confirmation_copy_lang_content')});
	 

	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('lang_toolbox');

				
	MUI.initAccordion('.toggler', 'div.element');
	

	/**
	 * Init help tips on label
	 * see init-content.js
	 *
	 */
	MUI.initLabelHelpLinks('#cleanLangForm');


	/*
	 * Lang itemManager
	 * Use of ItemManager.deleteItem, etc.
	 */
	langManager = new IonizeItemManager(
	{
		element: 	'lang',
		container: 	'langContainer'		
	});
	
	langManager.makeSortable();

	
</script>





