
<div id="sidecolumn" class="close">
	
	<div class="info">

		<dl class="compact">
			<dt class="small"><label><?=lang('ionize_title_php_version')?></label></dt>
			<dd><?= phpversion() ?></dd>
		</dl>
		<dl class="compact">
			<dt class="small"><label><?=lang('ionize_title_db_version')?></label></dt>
			<dd><?=$this->db->platform().' '.$this->db->version();?></dd>
		</dl>

	</div>

	<div id="options">
		
		<!-- Database -->
		<h3 class="toggler"><?=lang('ionize_title_database')?></h3>

		<div class="element">

			<form name="databaseForm" id="databaseForm" method="post" action="<?= base_url() ?>admin/setting/save_database">

				<!-- Driver -->
				<dl>
					<dt class="small">
						<label for="db_driver"><?=lang('ionize_label_db_driver')?></label>
					</dt>
					<dd>
						<select name="db_driver" id="db_driver" class="select">
							<option <?php if ($this->db->platform() == 'mysql'):?>selected="selected"<?php endif;?>  value="mysql">MySQL</option>
							<option <?php if ($this->db->platform() == 'mysqli'):?>selected="selected"<?php endif;?>  value="mysqli">MySQLi</option>
							<option <?php if ($this->db->platform() == 'mssql'):?>selected="selected"<?php endif;?>  value="mssql">MS SQL</option>
							<option <?php if ($this->db->platform() == 'postgre'):?>selected="selected"<?php endif;?>  value="postgre">Postgre SQL</option>
							<option <?php if ($this->db->platform() == 'oci8'):?>selected="selected"<?php endif;?>  value="oci8">Oracle</option>
							<option <?php if ($this->db->platform() == 'sqlite'):?>selected="selected"<?php endif;?>  value="sqlite">SQLite</option>
							<option <?php if ($this->db->platform() == 'odbc'):?>selected="selected"<?php endif;?>  value="odbc">ODBC</option>
						</select>
					</dd>
				</dl>
				
				<!-- Host -->
				<dl>
					<dt class="small">
						<label for="db_host"><?=lang('ionize_label_db_host')?></label>
					</dt>
					<dd>
						<input id="db_host" name="db_host" class="inputtext w140" type="text" value="<?= $db_host ?>" />
					</dd>
				</dl>

				<!-- Database -->
				<dl>
					<dt class="small">
						<label for="db_name"><?=lang('ionize_label_db_name')?></label>
					</dt>
					<dd>
						<input id="db_name" name="db_name" class="inputtext w140" type="text" value="<?= $db_name ?>" />
					</dd>
				</dl>

				<!-- User -->
				<dl>
					<dt class="small">
						<label for="db_user"><?=lang('ionize_label_db_user')?></label>
					</dt>
					<dd>
						<input id="db_user" name="db_user" class="inputtext w140" type="text" value="<?= $db_user ?>" />
					</dd>
				</dl>

				<!-- Password -->
				<dl>
					<dt class="small">
						<label for="db_pass"><?=lang('ionize_label_db_pass')?></label>
					</dt>
					<dd>
						<input id="db_pass" name="db_pass" class="inputtext w140" type="password" value="" />
					</dd>
				</dl>

				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_database" type="submit" class="submit" value="<?= lang('ionize_button_save') ?>" />
					</dd>
				</dl>
				<br/>

			</form>

		</div> <!-- /element -->
			

		<!-- SMTP -->
		<h3 class="toggler"><?=lang('ionize_title_mail_send')?></h3>
		
		<div class="element">

			<form name="smtpForm" id="smtpForm" method="post" action="<?= base_url() ?>admin/setting/save_smtp">
			
				<!-- Website email -->
				<dl>
					<dt class="small">
						<label for="site_email"><?=lang('ionize_label_site_email')?></label>
					</dt>
					<dd>
						<input id="site_email" name="site_email" class="inputtext w140" type="text" value="<?= Settings::get('site_email') ?>" />
					</dd>
				</dl>

				<!-- Mail path -->
				<dl>
					<dt class="small">
						<label for="smtp_protocol"><?=lang('ionize_label_smtp_protocol')?></label>
					</dt>
					<dd>
						<select name="protocol" id="protocol" onchange="javascript:changeEmailDetails();" class="select">
							<option <?php if ($protocol == 'smtp'):?>selected="selected"<?php endif;?> value="smtp">SMTP</option>
							<option <?php if ($protocol == 'mail'):?>selected="selected"<?php endif;?> value="mail">Mail</option>
							<option <?php if ($protocol == 'sendmail'):?>selected="selected"<?php endif;?>  value="sendmail">SendMail</option>
						</select>
					</dd>
				</dl>
				

				<!-- Mail Path -->
				<div id="emailMailDetails" style="display:none;">
					<dl>
						<dt class="small">
							<label for="mailpath"><?=lang('ionize_label_mailpath')?></label>
						</dt>
						<dd>
							<input id="mailpath" name="mailpath" type="text" class="inputtext w140" value="<?= $mailpath ?>" />
						</dd>
					</dl>
				</div>
				
				<div id="emailSMTPDetails">
					<!-- SMTP Host -->
					<dl>
						<dt class="small">
							<label for="smtp_host"><?=lang('ionize_label_smtp_host')?></label>
						</dt>
						<dd>
							<input id="smtp_host" name="smtp_host" type="text" class="inputtext w140" value="<?= $smtp_host ?>" />
						</dd>
					</dl>
					
					<!-- SMTP User -->
					<dl>
						<dt class="small">
							<label for="smtp_user"><?=lang('ionize_label_smtp_user')?></label>
						</dt>
						<dd>
							<input id="smtp_user" name="smtp_user" type="text" class="inputtext w140" value="<?= $smtp_user ?>" />
						</dd>
					</dl>
				
					<!-- SMTP Pass -->
					<dl>
						<dt class="small">
							<label for="smtp_pass"><?=lang('ionize_label_smtp_pass')?></label>
						</dt>
						<dd>
							<input id="smtp_pass" name="smtp_pass" type="password" class="inputtext w140" value="<?= $smtp_pass ?>" />
						</dd>
					</dl>
				
					<!-- SMTP Port -->
					<dl>
						<dt class="small">
							<label for="smtp_port"><?=lang('ionize_label_smtp_port')?></label>
						</dt>
						<dd>
							<input id="smtp_port" name="smtp_port" type="text" class="inputtext w40" value="<?= $smtp_port ?>" />
						</dd>
					</dl>
				</div>
					
				<!-- Charset -->
				<dl>
					<dt class="small">
						<label for="charset"><?=lang('ionize_label_email_charset')?></label>
					</dt>
					<dd>
						<input id="charset" name="charset" type="text" class="inputtext w140" value="<?= $charset ?>" />
					</dd>
				</dl>
			
				<!-- Mailtype -->
				<dl>
					<dt class="small">
						<label for="mailtype"><?=lang('ionize_label_email_mailtype')?></label>
					</dt>
					<dd>
						<select name="mailtype" id="mailtype" class="select">
							<option <?php if ($mailtype == 'text'):?>selected="selected"<?php endif;?> value="text">Text</option>
							<option <?php if ($mailtype == 'html'):?>selected="selected"<?php endif;?> value="html">HTML</option>
						</select>
					</dd>
				</dl>
			
				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_smtp" type="submit" class="submit" value="<?= lang('ionize_button_save') ?>" />
					</dd>
				</dl>
				<br/>
			
			</form>
		</div>
		
			
		<!-- New thumbnail -->
		<h3 class="toggler"><?=lang('ionize_title_thumb_new')?></h3>

		<div class="element">
			
			<form name="thumbForm" id="thumbForm" method="post" action="<?= base_url() ?>admin/setting/save_thumb">

				<!-- Thumb name -->
				<dl>
					<dt class="small">
						<label for="thumb_name_new"><?=lang('ionize_label_thumb_dir')?></label>
					</dt>
					<dd>
						<input id="thumb_name_new" name="thumb_name_new" type="text" class="inputtext w140" value="" />
					</dd>
				</dl>

				<!-- Thumb size ? -->
				<dl>
					<dt class="small">
						<label for="thumb_size_new"><?=lang('ionize_label_thumb_size')?></label>
					</dt>
					<dd>
						<input id="thumb_size_new" name="thumb_size_new" type="text" class="inputtext w140" value="" />
					</dd>
				</dl>

				<!-- Thumb ref size (width or height) ? -->
				<dl>
					<dt class="small">
						<label><?=lang('ionize_label_thumb_sizeref')?></label>
					</dt>
					<dd>
						<input class="inputradiobox" type="radio" name="thumb_sizeref_new" id="thumb_sizeref_new1" value="width" checked="checked" /><label for="thumb_sizeref_new1"><?=lang('ionize_label_thumb_sizeref_width')?></label>
						<input class="inputradiobox" type="radio" name="thumb_sizeref_new" id="thumb_sizeref_new2" value="height" /><label for="thumb_sizeref_new2"><?=lang('ionize_label_thumb_sizeref_height')?></label>
					</dd>
				</dl>

				<!-- Thumb square resize ? -->
				<dl>
					<dt class="small">
						<label for="thumb_square_new"><?=lang('ionize_label_thumb_square')?></label>
					</dt>
					<dd>
						<input class="inputcheckbox" type="checkbox" name="thumb_square_new" id="thumb_square_new" value="true" />
					</dd>
				</dl>

				<!-- Thumb unsharp mask ? -->
				<dl>
					<dt class="small">
						<label for="thumb_unsharp_new"><?=lang('ionize_label_thumb_unsharp')?></label>
					</dt>
					<dd>
						<input class="inputcheckbox" type="checkbox" name="thumb_unsharp_new" id="thumb_unsharp_new" value="true" />
					</dd>
				</dl>

				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_thumb" type="submit" class="submit" value="<?= lang('ionize_button_save') ?>" />
					</dd>
				</dl>
				<br/>

			</form>

		</div> <!-- /element -->



	</div> <!-- /togglers -->

</div> <!-- /sidecolumn -->


<!-- Main Column -->
<div id="maincolumn">


	<form name="settingsForm" id="settingsForm" method="post" action="<?= base_url() ?>admin/setting/save_technical">

		<!-- Google Analytics -->
		<h3 class="toggler1"><?=lang('ionize_title_google_analytics')?></h3>

		<div class="element1">

			<!-- Database -->
			<dl>
				<dt>
					<label for="google_analytics" title="<?=lang('ionize_help_setting_google_analytics')?>"><?=lang('ionize_label_google_analytics')?></label>
				</dt>
				<dd>
					<textarea name="google_analytics" id="google_analytics" class="w360 h160"><?= htmlentities(stripslashes(Settings::get('google_analytics')), ENT_QUOTES, 'utf-8') ?></textarea>
				</dd>
			</dl>
		</div>

		<!-- Media management -->
		<h3 class="toggler1"><?=lang('ionize_title_media_management')?></h3>

		<div class="element1">
			<dl>
				<dt>
					<label for="filemanager"><?=lang('ionize_label_filemanager')?></label>
				</dt>
				<dd>
					<select class="select" name="filemanager">
						<?php foreach($filemanagers as $key=>$f) :?>
							<option value="<?= $f ?>" <?php if(Settings::get('filemanager') == $f) :?> selected="selected" <?php endif ;?>><?= $f ?></option>
						<?php endforeach ;?>
					</select>
				</dd>
			</dl>
	
			<dl>
				<dt>
					<label for="files_path" title="<?=lang('ionize_help_setting_files_path')?>"><?=lang('ionize_label_files_path')?></label>
				</dt>
				<dd>
					<input name="files_path" id="files_path" class="inputtext w240" type="text" value="<?= Settings::get('files_path') ?>"/>
				</dd>
			</dl>
	
			<!-- Supported media extensions, by media type -->
			<dl>
				<dt>
					<label for="media_type_picture" title="<?=lang('ionize_help_setting_media_type_picture')?>"><?=lang('ionize_label_media_type_picture')?></label>
				</dt>
				<dd>
					<input name="media_type_picture" id="media_type_picture" class="inputtext w240" type="text" value="<?= Settings::get('media_type_picture') ?>"/>
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="media_type_music" title="<?=lang('ionize_help_setting_media_type_music')?>"><?=lang('ionize_label_media_type_music')?></label>
				</dt>
				<dd>
					<input name="media_type_music" id="media_type_music" class="inputtext w240" type="text" value="<?= Settings::get('media_type_music') ?>"/>
				</dd>
			</dl>
			<dl>
				<dt>
					<label for="media_type_video" title="<?=lang('ionize_help_setting_media_type_video')?>"><?=lang('ionize_label_media_type_video')?></label>
				</dt>
				<dd>
					<input name="media_type_video" id="media_type_video" class="inputtext w240" type="text" value="<?= Settings::get('media_type_video') ?>"/>
				</dd>
			</dl>
	
			<dl>
				<dt>
					<label for="media_type_file" title="<?=lang('ionize_help_setting_media_type_file')?>"><?=lang('ionize_label_media_type_file')?></label>
				</dt>
				<dd>
					<input name="media_type_file" id="media_type_file" class="inputtext w240" type="text" value="<?= Settings::get('media_type_file') ?>"/>
				</dd>
			</dl>

		</div>

		<!-- Thumbnails -->
		<?php if ( ! empty($thumbs)) :?>
			<h3 class="toggler1"><?=lang('ionize_title_thumbs')?></h3>
			
			<div class="element1">
				<div id="thumbs">
				
				<?php 
					foreach($thumbs as $thumb)
					{
						$settings = explode(",", $thumb['content']);
						$setting = array(
							'dir' =>	substr($thumb['name'], strrpos($thumb['name'], '_') + 1 ),
							'sizeref' => 	$settings[0],
							'size' => 	$settings[1],
							'square' => isset($settings[2]) ? $settings[2] : '0',
							'unsharp' => isset($settings[3]) ? $settings[3] : '0'
						);
						
					?>
					
					<div id="<?=$thumb['id_setting']?>">	
						
						<!-- Dir -->
						<dl>
							<dt>
								<label for="thumb_name_<?=$thumb['id_setting']?>"><?=lang('ionize_label_thumb_dir')?></label>
							</dt>
							<dd>
								<input name="thumb_name_<?=$thumb['id_setting']?>" id="thumb_name_<?=$thumb['id_setting']?>" class="inputtext w140" type="text" value="<?= $setting['dir'] ?>"/>
								<img  title="<?=lang('ionize_label_delete')?>" id="delThumb_<?=$thumb['id_setting']?>" class="inputicon pointer" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />
							</dd>
						</dl>
		
						<!-- Size -->
						<dl>
							<dt>
								<label for="thumb_size_<?=$thumb['id_setting']?>"><?=lang('ionize_label_thumb_size')?></label>
							</dt>
							<dd>
								<input name="thumb_size_<?=$thumb['id_setting']?>" id="thumb_size_<?=$thumb['id_setting']?>" class="inputtext w140" type="text" value="<?= $setting['size'] ?>"/>
							</dd>
						</dl>
		
						<!-- Size Reference -->
						<dl>
							<dt>
								<label><?=lang('ionize_label_thumb_sizeref')?></label>
							</dt>
							<dd>
								<input <?php if ($setting['sizeref'] == 'width'):?>checked="checked"<?php endif;?> class="inputradiobox" type="radio" name="thumb_sizeref_<?=$thumb['id_setting']?>" id="thumb_sizeref_<?=$thumb['id_setting']?>1" value="width" /><label for="thumb_sizeref_<?=$thumb['id_setting']?>1"><?=lang('ionize_label_thumb_sizeref_width')?></label>
								<input <?php if ($setting['sizeref'] == 'height'):?>checked="checked"<?php endif;?> class="inputradiobox" type="radio" name="thumb_sizeref_<?=$thumb['id_setting']?>" id="thumb_sizeref_<?=$thumb['id_setting']?>2" value="height" /><label for="thumb_sizeref_<?=$thumb['id_setting']?>2"><?=lang('ionize_label_thumb_sizeref_height')?></label>
							</dd>
						</dl>
	
						<!-- Square ? -->
						<dl>
							<dt>
								<label for="thumb_square_<?=$thumb['id_setting']?>"><?=lang('ionize_label_thumb_square')?></label>
							</dt>
							<dd>
								<input <?php if ($setting['square'] == 'true'):?>checked="checked"<?php endif;?> class="inputcheckbox" type="checkbox" name="thumb_square_<?=$thumb['id_setting']?>" value="true" />
							</dd>
						</dl>
		
						<!-- Unsharp ? -->
						<dl>
							<dt>
								<label for="thumb_unsharp_<?=$thumb['id_setting']?>"><?=lang('ionize_label_thumb_unsharp')?></label>
							</dt>
							<dd>
								<input <?php if ($setting['unsharp'] == 'true'):?>checked="checked"<?php endif;?> class="inputcheckbox" type="checkbox" name="thumb_unsharp_<?=$thumb['id_setting']?>" value="true" />
								<hr/>
							</dd>
						</dl>
						
					</div>
				
					
					<?php
					}
					?>
		
				</div>
			</div>	

			<!-- Thumbs used by ionize -->
			<h3 class="toggler1"><?=lang('ionize_title_thumbs_system')?></h3>
			
			<div class="element1">
			
				<!-- Picture list thumbs -->
				<dl>
					<dt>
						<label  title="<?=lang('ionize_help_setting_system_thumb_list')?>"><?=lang('ionize_label_thumbs_system')?></label>
					</dt>
					<dd>
					<?php 
						foreach($thumbs as $thumb)
						{
							$dir = substr($thumb['name'], strrpos($thumb['name'], '_') + 1 );
							?>
			
							<input <?php if (Settings::get('system_thumb_list') && Settings::get('system_thumb_list') == $thumb['name']):?>checked="checked"<?php endif;?> class="inputradio" type="radio" name="system_thumb_list" id="system_thumb_list_<?=$dir?>" value="<?=$thumb['name']?>" />
							<label for="system_thumb_list_<?=$dir?>"><?=$dir?></label>
							<br/>
							<?php
						}
					?>
					</dd>
				</dl>
			</div>
				
		<?php endif ;?>	

		<!-- extend fields -->
		<h3 class="toggler1"><?=lang('ionize_title_extend_fields')?></h3>
		
		<div class="element1">
			<dl>
				<dt>
					<label for="use_extend_fields"><?=lang('ionize_label_extend_fields_activate')?></label>
				</dt>
				<dd>
					<input <?php if (Settings::get('use_extend_fields') == '1'):?>checked="checked"<?php endif;?> class="inputcheckbox" type="checkbox" name="use_extend_fields" id="use_extend_fields" value="1" />
				</dd>
			</dl>
		</div>

	</form>

</div> <!-- /maincolumn -->


<script type="text/javascript">
	
	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('setting_technical_toolbox');


	/**
	 * Options Accordion
	 *
	 */
	MUI.initAccordion('.toggler', 'div.element');
	MUI.initAccordion('.toggler1', 'div.element1');

	/**
	 * Init help tips on label
	 *
	 */
	MUI.initLabelHelpLinks('#settingsForm');


	/**
	 * Database form action
	 * see mocha/init-forms.js for more information about this method
	 */
	MochaUI.setFormSubmit('databaseForm', 'submit_database', 'admin/setting/save_database/true', 'mainPanel', 'admin/setting/technical');

	/**
	 * New Thumb form action
	 * see mocha/init-forms.js for more information about this method
	 */
	MochaUI.setFormSubmit('thumbForm', 'submit_thumb', 'admin/setting/save_thumb/true', 'mainPanel', 'admin/setting/technical');

	/**
	 * SMTP form action
	 * see mocha/init-forms.js for more information about this method
	 */
	MochaUI.setFormSubmit('smtpForm', 'submit_smtp', 'admin/setting/save_smtp/true', 'mainPanel', 'admin/setting/technical');


	/**
	 * Adds action to the form submit button
	 * See mocha/init-forms.js for more information about this method
	MochaUI.setFormSubmit('settingsForm', 
							'settingsFormSubmit', 
							'admin/setting/save_technical/true', 
							'mainPanel', 
							'admin/setting/technical' );
	 */


	/** 
	 * Add Confirmation window on thumb delete icons
	 * See mocha/init-windows.js for more information about this method
	 *
	 */
	$('thumbs').getElements('div').each(function(item)
	{
		var id = item.id;
		
		MUI.addConfirmation('confirm' + id, 
							'delThumb_' + id, 
							'admin/setting/delete_thumb/' + id, 
							'ionize_confirm_element_delete'
							);
	});

	
	/**
	 * Show / hides Email details depending on the selected protocol
	 *
	 */
	changeEmailDetails = function()
	{
		if ($('protocol').value == 'mail')
		{
			$('emailSMTPDetails').setStyle('display', 'none');
			$('emailMailDetails').setStyle('display', 'block');
		}
		else
		{
			$('emailSMTPDetails').setStyle('display', 'block');
			$('emailMailDetails').setStyle('display', 'none');		
		}
	}
	changeEmailDetails();


</script>