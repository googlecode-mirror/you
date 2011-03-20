
<form name="settingsForm" id="settingsForm" method="post" action="<?= base_url() ?>admin/setting/save">


<div id="sidecolumn" class="close">

	<!- Informations -->
	<div class="info">

		<dl class="compact">
			<dt class="small"><label><?=lang('ionize_label_file_uploads')?></label></dt>
			<dd><img src="<?= base_url() ?>themes/admin/images/icon_16_<?php if(ini_get('file_uploads') == true) :?>ok<?php else :?>nok<?php endif ;?>.png" /></dd>
		</dl>
		<dl class="compact">
			<dt class="small"><label><?=lang('ionize_label_max_upload_size')?></label></dt>
			<dd><?= ini_get('upload_max_filesize') ?></dd>
		</dl>

	</div>

	<div id="options">
		
		<!-- Database -->
		<h3 class="toggler"><?=lang('ionize_title_admin_ui_options')?></h3>

		<div class="element">

			<!-- Show help tips in admin ? -->
			<dl>
				<dt class="small">
					<label for="show_help_tips"><?=lang('ionize_label_show_help_tips')?></label>
				</dt>
				<dd>
					<input class="inputcheckbox" type="checkbox" name="show_help_tips" id="show_help_tips" <?php if (Settings::get('show_help_tips') == '1'):?> checked="checked" <?php endif;?> value="1" />
				</dd>
			</dl>

		</div> <!-- /element -->
	</div>

</div> <!-- /sidecolumn -->


<!-- Main Column -->
<div id="maincolumn">


	<!-- Title -->
	<dl>
		<dt>
			<label for="site_title"><?=lang('ionize_label_site_title')?></label>
		</dt>
		<dd>
			<input name="site_title" id="site_title" class="inputtext w240" type="text" value="<?=Settings::get('site_title') ?>"/>
		</dd>
	</dl>

	
	<!-- Meta keywords & Meta description -->
	<fieldset id="blocks">

		<!-- Languages tabs -->
		<div class="tab">
			<ul class="tab-content">
				<?php foreach(Settings::get_languages() as $language) :?>
					<li id="tab-<?=$language['lang']?>"><a><span><?=$language['name']?></span></a></li>
				<?php endforeach ;?>
			</ul>
		</div>

		<!-- Tabs content blocks -->

		<?php foreach(Settings::get_languages() as $language) :?>
			
			<div id="block-<?=$language['lang']?>" class="block data">
			
				<dl>
					<dt>
						<label for="meta_description_<?=$language['lang']?>"><?=lang('ionize_label_meta_description')?></label>
					</dt>
					<dd>
						<textarea name="meta_description_<?=$language['lang']?>" id="meta_description_<?=$language['lang']?>" class="w280 h60"><?=Settings::get('meta_description', $language['lang']) ?></textarea>
					</dd>
				</dl>

				<dl>
					<dt>
						<label for="meta_keywords_<?=$language['lang']?>"><?=lang('ionize_label_meta_keywords')?></label>
					</dt>
					<dd>
						<textarea name="meta_keywords_<?=$language['lang']?>" id="meta_keywords_<?=$language['lang']?>" class="w280 h60"><?=Settings::get('meta_keywords', $language['lang']) ?></textarea>
					</dd>
				</dl>

			</div>

		<?php endforeach ;?>

	</fieldset>

</div> <!-- /maincolumn -->

</form>

<script type="text/javascript">
	
	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('setting_toolbox');

	/**
	 * Options Accordion
	 *
	 */
	MUI.initAccordion('.toggler', 'div.element');

	// Article data Tab Events
	<?php foreach(Settings::get_languages() as $language) :?>
		$('tab-<?=$language["lang"]?>').addEvent('click', function(){ displayBlock('.data', '<?=$language["lang"]?>'); });
	<?php endforeach ;?>
	
	// Display the first language tab
	<?php
		$languages = Settings::get_languages();
		$l = $languages[0];
	?>
	displayBlock('.data', '<?=$l["lang"]?>');

</script>