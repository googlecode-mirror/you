
<div id="sidecolumn" class="close">
	
	<!- Informations -->
	<div class="info">

		<dl class="compact">
			<dt class="small"><label><?=lang('ionize_label_current_theme')?></label></dt>
			<dd><?= Settings::get('theme');?></dd>
		</dl>

	</div>


	<div id="options">
		
		<!-- Themes -->
		<h3 class="toggler"><?=lang('ionize_title_themes')?></h3>

		<div class="element">

			<form name="themesForm" id="themesForm" method="post" action="<?= base_url() ?>admin/setting/save_themes">

				<!-- Theme -->
				<dl>
					<dt class="small">
						<label for="theme"><?=lang('ionize_label_theme')?></label>
					</dt>
					<dd>
						<select class="select" name="theme">
							<?php foreach($themes as $theme) :?>
								<option value="<?= $theme ?>" <?php if($theme == Settings::get('theme') ) :?>selected="selected"<?php endif; ?>><?= $theme ?></option>
							<?php endforeach ;?>
						</select>
					</dd>
				</dl>

				<!-- Theme Admin -->
				<dl>
					<dt class="small">
						<label for="theme_admin"><?=lang('ionize_label_theme_admin')?></label>
					</dt>
					<dd>
						<select class="select" name="theme_admin">
							<?php foreach($themes_admin as $theme) :?>
								<option value="<?= $theme ?>" <?php if($theme == Settings::get('theme_admin') ) :?>selected="selected"<?php endif; ?>><?= $theme ?></option>
							<?php endforeach ;?>
						</select>
					</dd>
				</dl>

				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="themesFormSubmit" type="submit" class="submit" value="<?= lang('ionize_button_save_themes') ?>" />
					</dd>
				</dl>
				<br/>

			</form>

		</div> <!-- /element -->
				
			
	</div> <!-- /togglers -->

</div> <!-- /sidecolumn -->

	

<!-- Main Column -->
<div id="maincolumn">

		
	<!-- Views list -->
	<h3 ><?=lang('ionize_title_views_list')?> : <?= Settings::get('theme') ?></h3>

	<!--<div class="element">-->

		<form name="viewsForm" id="viewsForm" method="post" action="<?= base_url() ?>admin/setting/save_views">

		<div id="viewsTableContainer">

			<!-- Views table list -->
			<table class="list" id="viewsTable">

				<thead>
					<tr>
						<th axis="string"><?= lang('ionize_label_view_filename') ?></th>
						<th axis="string"><?= lang('ionize_label_view_folder') ?></th>
						<th axis="string"><?= lang('ionize_label_view_name') ?></th>
						<th axis="string"><?= lang('ionize_label_view_type') ?></th>				
					</tr>
				</thead>

				<tbody>
				
				<?php foreach($files as $file) :?>
					
					<?php
						$rel = $file->path . $file->name;
					?>
					
					<tr>
						<td><a class="viewEdit" rel="<?= $rel ?>"><?= $file->name ?></a></td>
						<td><?= $file->path ?> </td>
						<td>
							<input type="text" class="inputtext w100" name="viewdefinition_<?= $rel ?>" value="<?= $file->definition ?>" />
						</td>	
						<td>
							<select class="select" name="viewtype_<?= $rel ?>">
								<option value=""><?= lang('ionize_select_no_type') ?></option>
								<option <?php if($file->type == 'page') :?> selected="selected" <?php endif ;?> value="page">Page</option>
								<option <?php if($file->type == 'article') :?> selected="selected" <?php endif ;?> value="article">Article</option>
							</select>
						</td>
					</tr>

				<?php endforeach ;?>
				
				</tbody>

			</table>

		</div>

	</form>
	
	<br />
	<br />
	
	<!--</div>-->
	

</div> <!-- /maincolumn -->


<script type="text/javascript">
	
	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('setting_theme_toolbox');


	/**
	 * Options Accordion
	 *
	 */
	MUI.initAccordion('.toggler', 'div.element');


	/**
	 * Views Edit links
	 *
	 */
	$$('.viewEdit').each(function(item)
	{
		var rel = item.getProperty('rel');
		
		var id = rel.replace(/\//gi, '');
		
		
		item.addEvent('click', function(e)
		{
			var e = new Event(e).stop();

			MochaUI.dataWindow(
				'viewEdit' + id, 
				Lang.get('ionize_title_view_edit') + ' : ' + rel, 
				'admin/setting/edit_view/' + rel, 
				{
					width:800, 
					height:450,
					onClose: function(e){
						editAreaLoader.delete_instance('edit_' + id);
					},
					onContentLoaded: function(e) {
						editAreaLoader.init({
							id: 'edit_' + id,
							font_size: 9,
							start_highlight: true,
							show_line_colors: true,
							allow_toggle: false,
							plugins: 'charmap',
					//		toolbar: 'save,|, go_to_line, |, undo, redo, |, charmap, change_smooth_selection, highlight, reset_highlight, word_wrap',
							toolbar: 'save,|, go_to_line, |, undo, redo, |, charmap, syntax_selection, change_smooth_selection, highlight, reset_highlight, word_wrap',
							word_wrap: true,
							language: '<?= Settings::get_lang()?>',
							syntax: 'html',
							save_callback: 'ION.editAreaSave'
						});
					}
					/*
					 * TODO... If you read this, do it ! ;-)
					 *
					 */
					/*
					,
					onResize: function(e)
					{
						var iFrame = new IFrame('frame_edit_test').contentDocument.getElement('body').get('html');

						var el = doc.getElementById('result');
						var iFrame = el.getComputedSize();
					
						MUI.notification('', size.height);
					}
					*/
				}
			);
		
		});
	});


	/**
	 * Database form action
	 * see mocha/init-forms.js for more information about this method
	 */
	MochaUI.setFormSubmit('themesForm', 'themesFormSubmit', 'admin/setting/save_themes');




</script>