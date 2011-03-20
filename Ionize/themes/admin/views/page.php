
<form name="pageForm" id="pageForm" method="post" action="<?=site_url('admin/page/save')?>">

	<input type="hidden" name="element" id="element" value="page" />
	<input type="hidden" name="action" id="action" value="save" />
	<input type="hidden" name="id_menu" value="<?= $id_menu ?>" />
	<input type="hidden" name="created" value="<?= $created ?>" />
	<input type="hidden" name="id_page" id="id_page" value="<?= $id_page ?>" />
	<input type="hidden" name="name" id="name" value="<?= $name ?>" />
	<input type="hidden" id="origin_id_parent" value="<?= $id_parent ?>" />
	
	<div id="sidecolumn" class="close">

		<!-- Main informations -->
		<?php if ($id_page != '') :?>
			
			<div class="info">
			
				<dl class="compact">
					<dt class="small"><label><?= lang('ionize_label_status') ?></label></dt>
					<dd class="icon">
						<a class="page<?= $id_page ?> <?=($online == '1') ? 'online' : 'offline' ;?>" onclick="javascript:pageManager.switchOnline('<?= $id_page ?>')"></a>
					</dd>
				</dl>
		
				<dl class="compact">
					<dt class="small"><label><?= lang('ionize_label_created') ?></label></dt>
					<dd><?= getFrenchDatetime($created) ?></dd>
				</dl>
		
				<?php if (getFrenchDatetime($updated) != '') :?>
					<dl class="compact">
						<dt class="small"><label><?= lang('ionize_label_updated') ?></label></dt>
						<dd><?= getFrenchDatetime($updated) ?></dd>
					</dl>
				<?php endif ;?>

				<!-- Internal / External link Info -->
				<dl class="compact" id="link_info"></dl>

			</div>
			
		<?php endif ;?>


		<div id="options">

			<!-- Options -->
			<h3 class="toggler"><?= lang('ionize_title_options') ?></h3>
		
			<div class="element">

				<!-- Menu -->
				<dl>
					<dt class="small">
						<label for="id_menu"><?= lang('ionize_label_menu') ?></label>
					</dt>
					<dd>
						<?= $menus ?>
					</dd>
				</dl>	

				<!-- Parent -->
				<dl>
					<dt class="small">
						<label for="id_parent"><?= lang('ionize_label_parent') ?></label>
					</dt>
					<dd>
						
						<?= $parents ?>
					</dd>
				</dl>	
			
				<!-- Online / Offline -->
				<dl>
					<dt class="small">
						<label for="online"><?= lang('ionize_label_online') ?></label>
					</dt>
					<dd>
						<div>
							<input id="online" <?php if ($online == 1):?> checked="checked" <?php endif;?> name="online" class="inputcheckbox" type="checkbox" value="1"/>
						</div>
					</dd>
				</dl>

				<!-- Appears as menu item in menu ? -->
				<dl>
					<dt class="small">
						<label for="appears" title="<?= lang('ionize_help_appears') ?>"><?= lang('ionize_label_appears') ?></label>
					</dt>
					<dd>
						<input id="appears" name="appears" type="checkbox" class="inputcheckbox" <?php if ($appears == 1):?> checked="checked" <?php endif;?> value="1" />
					</dd>
				</dl>

				<!-- Template -->
				<?php if (isset($views)) :?>
					<dl>
						<dt class="small">
							<label for="view"><?= lang('ionize_label_template') ?></label>
						</dt>
						<dd>
							<?= $views ?>
						</dd>
					</dl>
				<?php endif ;?>
				
				<!-- Article List Template -->
				<?php if (isset($article_list_views)) :?>
					<dl>
						<dt class="small">
							<label for="article_list_view" title="<?= lang('ionize_help_article_list_template') ?>"><?= lang('ionize_label_article_list_template') ?></label>
						</dt>
						<dd>
							<?= $article_list_views ?>
						</dd>
					</dl>
				<?php endif ;?>
				
				<!-- Article Template -->
				<?php if (isset($article_views)) :?>
					<dl>
						<dt class="small">
							<label for="article_view"><?= lang('ionize_label_article_template') ?></label>
						</dt>
						<dd>
							<?= $article_views ?>
						</dd>
					</dl>
				<?php endif ;?>
				

				<!-- Internal / External link -->
				<dl>
					<dt class="small">
						<label for="link" title="<?= lang('ionize_help_page_link') ?>"><?= lang('ionize_label_link') ?></label>
						<br/>
						
					</dt>
					<dd>
						<input type="hidden" id="link_type" name="link_type" value="<?= $link_type ?>" />
						<input type="hidden" id="link_id" name="link_id" value="<?= $link_id ?>" />
						
						<textarea id="link" name="link" class="inputtext w140 h40 droppable"><?= $link ?></textarea>
						<br />
						
						<a id="link_remove"><?= lang('ionize_label_remove_link') ?></a><br/>
					</dd>
				</dl>

			</div>
			
			<!-- Advanced Options -->
			<h3 class="toggler"><?= lang('ionize_title_advanced') ?></h3>
		
			<div class="element">
				
				<!-- Pagination -->
				<dl>
					<dt class="small">
						<label for="pagination" title="<?= lang('ionize_help_pagination') ?>"><?= lang('ionize_label_pagination_nb') ?></label>
					</dt>
					<dd>
						<input id="pagination" name="pagination" type="text" class="inputtext w40" value="<?= $pagination ?>" />
					</dd>
				</dl>

				<!-- Home page -->
				<dl>
					<dt class="small">
						<label for="home" title="<?= lang('ionize_help_home_page') ?>"><?= lang('ionize_label_home_page') ?></label>
					</dt>
					<dd>
						<input id="home" name="home" type="checkbox" class="inputcheckbox" <?php if ($home == 1):?> checked="checked" <?php endif;?> value="1" />
					</dd>
				</dl>


			</div>

			<!-- Dates -->
			<h3 class="toggler"><?= lang('ionize_title_dates') ?></h3>
			
			<div class="element">
				<dl>
					<dt class="small">
						<label for="publish_on" title="<?= lang('ionize_help_publish_on') ?>"><?= lang('ionize_label_publish_on') ?></label>
					</dt>
					<dd>
						<input id="publish_on" name="publish_on" type="text" class="inputtext w120 date" value="<?= getFrenchDatetime($publish_on) ?>" />
					</dd>
				</dl>
			
				<dl class="last">
					<dt class="small">
						<label for="publish_off" title="<?= lang('ionize_help_publish_off') ?>"><?= lang('ionize_label_publish_off') ?></label>
					</dt>
					<dd>
						<input id="publish_off" name="publish_off" type="text" class="inputtext w120 date"  value="<?= getFrenchDatetime($publish_off) ?>" />
					</dd>
				</dl>
			
			</div>


			<!-- Metas -->
			<h3 class="toggler"><?= lang('ionize_title_metas') ?></h3>
			
			<div class="element">
				
				<!-- Meta_Description -->
				<dl>
					<dt class="small">
						<label title="<?= lang('ionize_help_page_meta') ?>"><?= lang('ionize_label_meta_description') ?></label>
					</dt>
					<dd>
						<div class="tab small">
							<ul class="tab-content small">
								<?php foreach(Settings::get_languages() as $language) :?>
									<li id="tab-<?= $language['lang'] ?>-description"><a><span><?= ucfirst(substr($language['name'],0,3)) ?></span></a></li>
								<?php endforeach ;?>
							</ul>
						</div>
						
						<?php foreach(Settings::get_languages() as $language) :?>
							<div id="block-<?= $language['lang'] ?>-description" class="block description small">
								<textarea id="meta_description_<?= $language['lang'] ?>" name="meta_description_<?= $language['lang'] ?>" class="w140 h80"><?= ${$language['lang']}['meta_description'] ?></textarea>
							</div>
						<?php endforeach ;?>
						
					</dd>
				</dl>
			
				<!-- Meta_Keywords -->
				<dl>
					<dt class="small">
						<label title="<?= lang('ionize_help_page_meta') ?>"><?= lang('ionize_label_meta_keywords') ?></label>
					</dt>
					<dd>
						<div class="tab small">
							<ul class="tab-content small">
								<?php foreach(Settings::get_languages() as $language) :?>
									<li id="tab-<?= $language['lang'] ?>-keywords"><a><span><?= ucfirst(substr($language['name'],0,3)) ?></span></a></li>
								<?php endforeach ;?>
							</ul>
						</div>
						
						<?php foreach(Settings::get_languages() as $language) :?>
							<div id="block-<?= $language['lang'] ?>-keywords" class="block keywords small">
								<textarea id="meta_keywords_<?= $language['lang'] ?>" name="meta_keywords_<?= $language['lang'] ?>" class="w140 h40"><?= ${$language['lang']}['meta_keywords'] ?></textarea>
							</div>
						<?php endforeach ;?>
						
					</dd>
				</dl>
			
			</div>

	

			<!-- Access authorization -->
			<h3 class="toggler"><?= lang('ionize_title_authorization') ?></h3>
			
			<div class="element">
			
				<dl class="last">
					<dt class="small">
						<label for="template"><?= lang('ionize_label_groups') ?></label>
					</dt>
					<dd>
						<div id="groups">
							<?= $groups ?>
						</div>
					</dd>
				</dl>
			
			</div>


			<!-- Other info : Permanenet URL, etc. -->
			<h3 class="toggler"><?= lang('ionize_title_informations') ?></h3>
			
			<div class="element">

				<?php if ($id_page != '') :?>
				<dl class="compact">
					<dt class="small"><label for="permanent_url"><?= lang('ionize_label_permanent_url') ?></label></dt>
					<dd>
						<div class="tab small">
							<ul class="tab-content small">
								<?php foreach(Settings::get_languages() as $language) :?>
									<li id="tab-<?= $language['lang'] ?>-permanent_url"><a><span><?= ucfirst(substr($language['name'],0,3)) ?></span></a></li>
								<?php endforeach ;?>
							</ul>
						</div>
						
						<?php foreach(Settings::get_languages() as $language) :?>
						
							<?php
							
							$lang = (count(Settings::get_online_languages()) > 1) ? $language['lang'].'/' : '';
							
							?>
						
							<div id="block-<?= $language['lang'] ?>-permanent_url" class="block permanent_url small">
								<textarea id="permanent_url_<?= $language['lang'] ?>" class="w140 h80" onclick="javascript:this.select();" readonly="readonly"><?= base_url().$lang ?><?= ${$language['lang']}['url'] ?></textarea>
							</div>
						<?php endforeach ;?>
						
					</dd>
				</dl>

				<?php endif ;?>
			
			</div>
			
			
		</div>	<!-- /options -->
	
	</div> <!-- /sidecolumn -->



	<div id="maincolumn">
		
		<fieldset>
				
			<!-- extend fields goes here... -->
			<?php if (Settings::get('use_extend_fields') == '1') :?>
				<?php foreach($extend_fields as $extend_field) :?>
				
					<?php if ($extend_field['translated'] != '1') :?>
					
						<dl>
							<dt>
								<label for="cf_<?= $extend_field['id_extend_field'] ?>" title="<?= $extend_field['description'] ?>"><?= $extend_field['label'] ?></label>
							</dt>
							<dd>
								<?php
									$extend_field['content'] = (!empty($extend_field['content'])) ? $extend_field['content'] : $extend_field['default_value'];
								?>
							
								<?php if ($extend_field['type'] == '1') :?>
									<input id="cf_<?= $extend_field['id_extend_field'] ?>" class="inputtext w340" type="text" name="cf_<?= $extend_field['id_extend_field'] ?>" value="<?= $extend_field['content']  ?>" />
								<?php endif ;?>
								
								<?php if ($extend_field['type'] == '2' OR $extend_field['type'] == '3') :?>
									<textarea id="cf_<?= $extend_field['id_extend_field'] ?>" class="<?php if($extend_field['type'] == '3'):?> tinyTextarea <?php endif ;?> inputtext w340 h80" name="cf_<?= $extend_field['id_extend_field'] ?>"><?= $extend_field['content'] ?></textarea>
								<?php endif ;?>

								<!-- Checkbox -->
								<?php if ($extend_field['type'] == '4') :?>
									
									<?php
										$pos = 		explode("\n", $extend_field['value']);
										$saved = 	explode(',', $extend_field['content']);
									?>
									<?php
										$i = 0; 
										foreach($pos as $values)
										{
											$vl = explode(':', $values);
											$key = $vl[0];
											$value = (!empty($vl[1])) ? $vl[1] : $vl[0];

											?>
											<input type="checkbox" id= "cf_<?= $extend_field['id_extend_field'].$i ?>" name="cf_<?= $extend_field['id_extend_field'] ?>[]" value="<?= $key ?>" <?php if (in_array($key, $saved)) :?>checked="checked" <?php endif ;?>><label for="cf_<?= $extend_field['id_extend_field'] . $i ?>"><?= $value ?></label></input><br/>
											<?php
											$i++;
										}
									?>
								<?php endif ;?>
								
								<!-- Radio -->
								<?php if ($extend_field['type'] == '5') :?>
									
									<?php
										$pos = explode("\n", $extend_field['value']);
									?>
									<?php
										$i = 0; 
										foreach($pos as $values)
										{
											$vl = explode(':', $values);
											$key = $vl[0];
											$value = (!empty($vl[1])) ? $vl[1] : $vl[0];

											?>
											<input type="radio" id= "cf_<?= $extend_field['id_extend_field'].$i ?>" name="cf_<?= $extend_field['id_extend_field'] ?>" value="<?= $key ?>" <?php if ($extend_field['content'] == $key) :?> checked="checked" <?php endif ;?>><label for="cf_<?= $extend_field['id_extend_field'] . $i ?>"><?= $value ?></label></input><br/>
											<?php
											$i++;
										}
									?>
								<?php endif ;?>
								
								<!-- Selectbox -->
								<?php if ($extend_field['type'] == '6' && !empty($extend_field['value'])) :?>
									
									<?php									
										$pos = explode("\n", $extend_field['value']);
										$saved = 	explode(',', $extend_field['content']);
									?>
									<select name="cf_<?= $extend_field['id_extend_field']?>">
									<?php
										$i = 0; 
										foreach($pos as $values)
										{
											$vl = explode(':', $values);
											$key = $vl[0];
											$value = (!empty($vl[1])) ? $vl[1] : $vl[0];
											?>
											<option value="<?= $key ?>" <?php if (in_array($key, $saved)) :?> selected="selected" <?php endif ;?>><?= $value ?></option>
											<?php
											$i++;
										}
									?>
									</select>
								<?php endif ;?>

								<!-- Date & Time -->
								<?php if ($extend_field['type'] == '7') :?>
								
									<input id="cf_<?= $extend_field['id_extend_field'] ?>" class="inputtext w120 date" type="text" name="cf_<?= $extend_field['id_extend_field'] ?>" value="<?= $extend_field['content']  ?>" />
									
								<?php endif ;?>
								
							</dd>
						</dl>	
							
					<?php endif ;?>
				<?php endforeach ;?>
			<?php endif ;?>


		</fieldset>


		<fieldset id="blocks">
	
			<!-- Tabs -->
			<div class="tab">
				<ul class="tab-content">
					
					<?php foreach(Settings::get_languages() as $language) :?>
						<li id="tab-<?= $language['lang'] ?>"><a><span><?= ucfirst($language['name']) ?></span></a></li>
					<?php endforeach ;?>

					<li id="tab-files" class="right"><a><span><?= lang('ionize_label_files') ?></span></a></li>
					<li id="tab-music" class="right"><a><span><?= lang('ionize_label_music') ?></span></a></li>
					<li id="tab-videos" class="right"><a><span><?= lang('ionize_label_videos') ?></span></a></li>
					<li id="tab-pictures" class="right"><a><span><?= lang('ionize_label_pictures') ?></span></a></li>

				</ul>
			</div>
	
			<!-- Text block -->
			<?php foreach(Settings::get_languages() as $language) :?>
		
				<?php $lang = $language['lang']; ?>
				
				<div id="block-<?= $lang ?>" class="block data">
		
					<!-- title -->
					<dl class="first">
						<dt>
							<label for="title_<?= $lang ?>"><?= lang('ionize_label_title') ?></label>
						</dt>
						<dd>
							<input id="title_<?= $lang ?>" name="title_<?= $lang ?>" class="inputtext w340" type="text" value="<?= ${$lang}['title'] ?>"/>
						</dd>
					</dl>

					<!-- URL -->
					<dl>
						<dt>
							<label for="url_<?= $lang ?>" ><?= lang('ionize_label_url') ?></label>
						</dt>
						<dd>
							<input id="url_<?= $lang ?>" name="url_<?= $lang ?>" class="inputtext w340" type="text" value="<?= ${$lang}['url'] ?>"/>
							<?php if(!empty($id_page)) :?>
								<a href="<?= base_url()?><?= ${$lang}['url'] ?>" target="_blank" title="<?= lang('ionize_label_see_online') ?>"><img src="<?= base_url()?><?= Theme::get_theme_path() ?>images/icon_16_right.png" /></a>
							<?php endif; ?>
						</dd>
					</dl>

					<!-- Meta title : used for browser window title -->
					<dl>
						<dt>
							<label for="meta_title_<?= $lang ?>" title="<?= lang('ionize_help_page_window_title') ?>"><?= lang('ionize_label_meta_title') ?></label>
						</dt>
						<dd>
							<input id="meta_title_<?= $lang ?>" name="meta_title_<?= $lang ?>" class="inputtext w340" type="text" value="<?= ${$lang}['meta_title'] ?>"/>
						</dd>
					</dl>

			
					<!-- sub title -->
					<dl>
						<dt>
							<label for="subtitle_<?= $lang ?>"><?= lang('ionize_label_subtitle') ?></label>
						</dt>
						<dd>
							<input id="subtitle_<?= $lang ?>" name="subtitle_<?= $lang ?>" class="inputtext w340" type="text" value="<?= ${$lang}['subtitle'] ?>"/>
						</dd>
					</dl>
			

					<!-- Online -->
					<?php if(count(Settings::get_languages()) > 1) :?>

						<dl>
							<dt>
								<label for="online_<?= $lang ?>" title="<?= lang('ionize_help_online_lang') ?>"><?= lang('ionize_label_online') ?></label>
							</dt>
							<dd>
								<input id="online_<?= $lang ?>" <?php if (${$lang}['online'] == 1):?> checked="checked" <?php endif;?> name="online_<?= $lang ?>" class="inputcheckbox" type="checkbox" value="1"/>
							</dd>
						</dl>
					
					<?php else :?>
					
						<input id="online_<?= $lang ?>" name="online_<?= $lang ?>" type="hidden" value="1"/>
					
					<?php endif ;?>



					<!-- extend fields goes here... -->
					<?php if (Settings::get('use_extend_fields') == '1') :?>
						<?php foreach($extend_fields as $extend_field) :?>
							<?php if ($extend_field['translated'] == '1') :?>
							
								<dl>
									<dt>
										<label for="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>" title="<?= $extend_field['description'] ?>"><?= $extend_field['label'] ?></label>
									</dt>
									<dd>
										<?php
											$extend_field[$lang]['content'] = (!empty($extend_field[$lang]['content'])) ? $extend_field[$lang]['content'] : $extend_field['default_value'];
										?>

										<?php if ($extend_field['type'] == '1') :?>
											<input id="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>" class="inputtext w340" type="text" name="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>" value="<?= $extend_field[$lang]['content'] ?>" />
										<?php endif ;?>
										
										<?php if ($extend_field['type'] == '2' || $extend_field['type'] == '3') :?>
											<textarea id="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>" class="inputtext w340 h80 <?php if($extend_field['type'] == '3'):?> tinyTextarea <?php endif ;?>" name="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>"><?= $extend_field[$lang]['content'] ?></textarea>
										<?php endif ;?>

										<!-- Checkbox -->
										<?php if ($extend_field['type'] == '4') :?>
											
											<?php
												$pos = 		explode("\n", $extend_field['value']);
												$saved = 	explode(',', $extend_field[$lang]['content']);
											?>

											<?php
												$i = 0; 
												foreach($pos as $values)
												{
													$vl = explode(':', $values);
													$key = $vl[0];
													$value = (!empty($vl[1])) ? $vl[1] : $vl[0];
		
													?>
													<input type="checkbox" id= "cf_<?= $extend_field['id_extend_field'].$i ?>_<?= $lang ?>" name="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>[]" value="<?= $key ?>" <?php if (in_array($key, $saved)) :?>checked="checked" <?php endif ;?>><label for="cf_<?= $extend_field['id_extend_field'] . $i ?>_<?= $lang ?>"><?= $value ?></label></input><br/>
													<?php
													$i++;
												}
											?>
										<?php endif ;?>
										
										<!-- Radio -->
										<?php if ($extend_field['type'] == '5') :?>
											
											<?php
												$pos = explode("\n", $extend_field['value']);
											?>
											<?php
												$i = 0; 
												foreach($pos as $values)
												{
													$vl = explode(':', $values);
													$key = $vl[0];
													$value = (!empty($vl[1])) ? $vl[1] : $vl[0];
		
													?>
													<input type="radio" id= "cf_<?= $extend_field['id_extend_field'].$i ?>_<?= $lang ?>" name="cf_<?= $extend_field['id_extend_field'] ?>_<?= $lang ?>" value="<?= $key ?>" <?php if ($extend_field[$lang]['content'] == $key) :?> checked="checked" <?php endif ;?>><label for="cf_<?= $extend_field['id_extend_field'] . $i ?>_<?= $lang ?>"><?= $value ?></label></input><br/>
													<?php
													$i++;
												}
											?>
										<?php endif ;?>
										
										<!-- Selectbox -->
										<?php if ($extend_field['type'] == '6' && !empty($extend_field['value'])) :?>
											
											<?php									
												$pos = explode("\n", $extend_field['value']);
												$saved = 	explode(',', $extend_field[$lang]['content']);
											?>
											<select name="cf_<?= $extend_field['id_extend_field']?>_<?= $lang ?>">
											<?php
												$i = 0; 
												foreach($pos as $values)
												{
													$vl = explode(':', $values);
													$key = $vl[0];
													$value = (!empty($vl[1])) ? $vl[1] : $vl[0];
													?>
													<option value="<?= $key ?>" <?php if (in_array($key, $saved)) :?> selected="selected" <?php endif ;?>><?= $value ?></option>
													<?php
													$i++;
												}
											?>
											</select>
										<?php endif ;?>

									</dd>
								</dl>	
									
							<?php endif ;?>
						<?php endforeach ;?>
					<?php endif ;?>

				</div>
				
			<?php endforeach ;?>


			<!-- Files -->
			<div id="block-files" class="block data">
			
				<p>
					<a class="filemanager right"><img src="<?=base_url()?>themes/admin/images/icon_16_plus.png" /> <?= lang('ionize_label_attach_media') ?></a>
					<a class="right pr5" href="javascript:mediaManager.loadMediaList('file')"><img src="<?=base_url()?>themes/admin/images/icon_16_files.png" /> <?= lang('ionize_label_reload_media_list') ?></a>
					<a class="pr5" href="javascript:mediaManager.detachMediaByType('file')"><img src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />  <?= lang('ionize_label_detach_all_files') ?></a>
				</p>
				
				<ul id="fileContainer">
					<span><?= lang('ionize_message_no_file') ?></span>
				</ul>

			</div>

			<!-- Music -->
			<div id="block-music" class="block data">
				
				<p>
					<a class="filemanager right"><img src="<?=base_url()?>themes/admin/images/icon_16_plus.png" /> <?= lang('ionize_label_attach_media') ?></a>
					<a class="right pr5" href="javascript:mediaManager.loadMediaList('music')"><img src="<?=base_url()?>themes/admin/images/icon_16_music.png" /> <?= lang('ionize_label_reload_media_list') ?></a>
					<a class="pr5" href="javascript:mediaManager.detachMediaByType('music')"><img src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />  <?= lang('ionize_label_detach_all_musics') ?></a>
				</p>
				
				<ul id="musicContainer">
					<span><?= lang('ionize_message_no_music') ?></span>
				</ul>

			</div>

			<!-- Videos -->
			<div id="block-videos" class="block data">
			
				<p>
					<a class="filemanager right"><img src="<?=base_url()?>themes/admin/images/icon_16_plus.png" /> <?= lang('ionize_label_attach_media') ?></a>
					<a class="right pr5" href="javascript:mediaManager.loadMediaList('video')"><img src="<?=base_url()?>themes/admin/images/icon_16_video.png" /> <?= lang('ionize_label_reload_media_list') ?></a>
					<a class="pr5" href="javascript:mediaManager.detachMediaByType('video')"><img src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />  <?= lang('ionize_label_detach_all_videos') ?></a>
				</p>

				<ul id="videoContainer">
					<span><?= lang('ionize_message_no_video') ?></span>
				</ul>

			</div>

			<!-- Pictures -->
			<div id="block-pictures" class="block data">
			
				<p>
					<a class="imagemanager right"><img src="<?=base_url()?>themes/admin/images/icon_16_plus.png" /> <?= lang('ionize_label_attach_media') ?></a>
					<a class="right pr5" href="javascript:mediaManager.loadMediaList('picture')"><img src="<?=base_url()?>themes/admin/images/icon_16_imagelist.png" /> <?= lang('ionize_label_reload_media_list') ?></a>
					<a class="pr5" href="javascript:mediaManager.detachMediaByType('picture')"><img src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />  <?= lang('ionize_label_detach_all_pictures') ?></a>
					<a href="javascript:mediaManager.initThumbsForParent()"><img src="<?=base_url()?>themes/admin/images/icon_16_refresh.png" /> <?= lang('ionize_label_init_all_thumbs') ?></a>
				</p>
			
				<ul id="pictureContainer">
					<span><?= lang('ionize_message_no_picture') ?></span>
				</ul>

			</div>

		</fieldset>
		
		
		<!-- Articles -->
		<?php if($id_page) :?>

			<fieldset id="articles">
			
				<!-- Tabs -->
				<div class="tab">
					<ul class="tab-content">
	
						<li id="tab-articles"><a><span><?= lang('ionize_label_articles') ?></span></a></li>
	
					</ul>
				</div>

				<!-- Articles list -->
				<div id="block-articles" class="block articles">
				
					<p>
						<a id="articleCreate" class="right" href="<?=base_url()?>admin/article/create/<?= $id_page ?>"><img src="<?=base_url()?>themes/admin/images/icon_16_add_article.png" /> <?= lang('ionize_label_add_article') ?></a>
					</p>
					
					<br />
				
					<ul id="articleContainer" >
					
						<?php foreach ($articles as $article) :?>
						
							<?php
							$title = ($article['title'] != '') ? $article['title'] : $article['name'];
							?>
	
							<li class="sortme article<?= $article['id_article'] ?>" id="article_<?= $article['id_article'] ?>">
								<img class="icon right" onclick="javascript:articleManager.deleteItem('<?= $article['id_article'] ?>');" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" alt="<?= lang('ionize_label_delete') ?>" />
								<a class="icon right pr5 article<?= $article['id_article'] ?> <?= (!$article['online']) ? 'offline' : 'online' ;?>" onclick="javascript:articleManager.switchOnline('<?= $article['id_article'] ?>');" ></a>
								<a class="right pr15" href="javascript:MUI.updateContent({'element': $('mainPanel'),'loadMethod': 'xhr','url': '<?=base_url()?>admin/article/edit/<?= $article['id_article'] ?>','title': Lang.get('ionize_title_edit_article') + ' : ' + '<?= $title ?>'});"><?= $article['name'] ?></a>
								<img class="icon left drag" src="<?= base_url() ?>themes/admin/images/icon_16_ordering.png" />
								<a class="left pl5" href="javascript:MUI.updateContent({'element': $('mainPanel'),'loadMethod': 'xhr','url': '<?=base_url()?>admin/article/edit/<?= $article['id_article'] ?>','title': Lang.get('ionize_title_edit_article') + ' : ' + '<?= $title ?>'});" title="<?= lang('ionize_label_edit') ?>"><?= $title ?></a>
							</li>
						
						<?php endforeach ;?>
					
					</ul>
				
				</div>
		
			</fieldset>
	
		<?php endif ;?>
	
	</div>

</form>


<!-- File Manager Form
	 Mandatory for the filemanager
-->
<form name="fileManagerForm" id="fileManagerForm">
	<input type="hidden" name="hiddenFile" />
</form>


<script type="text/javascript">


	/**
	 * Options Accordion
	 *
	 */
	MUI.initAccordion('.toggler', 'div.element');


	/** 
	 * TinyMCE control add on first language only
	 */
	if ($$('.tinyTextarea'))
	{
		tinyMCE.init(tinyMCEParam);
	}

	/**
	 * Init help tips on label
	 *
	 */
	MUI.initLabelHelpLinks('#pageForm');


	// Form Manager
	formManager = new IonizeFormManager({baseUrl: base_url, form: 'pageForm'});


	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('page_toolbox');


	// Remove link event
	if ($('link_remove'))
	{
		$('link_remove').addEvent('click', function()
		{
			ION.removeLink();
		});
	}
	
	// Add edit link to the link (if internal)
	<?php if ($link != '') :?>
	
		ION.updateLinkInfo('<?php echo($link_type); ?>', '<?php echo($link_id); ?>', '<?php echo($link); ?>');
	
	<?php endif ;?>


	// Update parent select list when menu change
	$('id_menu').addEvent('change', function(e)
	{
		e.stop();
		
		var xhr = new Request.HTML(
		{
			url: base_url + 'admin/page/get_parents_select/' + $('id_menu').value + '/' + $('origin_id_parent').value,
			method: 'post',
			update: 'id_parent'
		}).send();
	});


	/** 
	 * Calendars
	 *
	 */
	datePicker.attach();
	

	/** 
	 * Show current tabs
	 */
 	displayBlock('.data', '<?= Settings::get_lang('first') ?>');
	displayBlock('.description', '<?= Settings::get_lang('first') ?>' + '-description');
	displayBlock('.keywords', '<?= Settings::get_lang('first') ?>' + '-keywords');
	displayBlock('.permanent_url', '<?= Settings::get_lang('first') ?>' + '-permanent_url');
	if ($('tab-articles'))
	{
		displayBlock('.articles', 'articles');
	}
	
	/** 
	 * Add events to tabs
	 * - Lang Tab Events 
	 * - Options Tab Events
	 * - Wysiwyg buttons
	 */
	<?php foreach(Settings::get_languages() as $lang) :?>
		$('tab-<?= $lang["lang"] ?>').addEvent('click', function(){ displayBlock('.data', '<?= $lang["lang"] ?>'); });
		$('tab-<?= $lang["lang"] ?>-description').addEvent('click', function(){ displayBlock('.description', '<?= $lang["lang"] ?>-description'); });
		$('tab-<?= $lang["lang"] ?>-keywords').addEvent('click', function(){ displayBlock('.keywords', '<?= $lang["lang"] ?>-keywords'); });
		
		if ($('tab-<?= $lang["lang"] ?>-permanent_url'))
		{
			$('tab-<?= $lang["lang"] ?>-permanent_url').addEvent('click', function(){ displayBlock('.permanent_url', '<?= $lang["lang"] ?>-permanent_url'); });
		}
	<?php endforeach ;?>
	

	/** 
	 * MediaManager
	 * The Media Manager manage pictures, music, videos, and other files add / remove / sorting
	 *
	 */
	var mediaManager = new IonizeMediaManager(
	{
		baseUrl:'<?= base_url() ?>',
		parent:'page', 
		idParent:'<?= $id_page ?>', 
		pictureContainer:'pictureContainer', 
		musicContainer:'musicContainer', 
		videoContainer:'videoContainer',
		fileContainer:'fileContainer',
		imageButton:'.imagemanager',
		fileButton:'.filemanager',
		wait:'waitPicture',
		mode:'<?= Settings::get('filemanager') ?>',
		pictureArray:Array('<?= str_replace(',', "','", Settings::get('media_type_picture')) ?>'),
		musicArray:Array('<?= str_replace(',', "','", Settings::get('media_type_music')) ?>'),
		videoArray:Array('<?= str_replace(',', "','", Settings::get('media_type_video')) ?>'),
		fileArray:Array('<?= str_replace(',', "','", Settings::get('media_type_file')) ?>')
	});


	/** 
	 * Media tabs events
	 */
	$('tab-files').addEvent('click', function(){ 
		displayBlock('.data', 'files'); 
		if ( ! this.retrieve('loaded')) { mediaManager.loadMediaList('file'); this.store('loaded', true);}
	});
	$('tab-music').addEvent('click', function(){ 
		displayBlock('.data', 'music'); 
		if ( ! this.retrieve('loaded')) { mediaManager.loadMediaList('music'); this.store('loaded', true);}
	});
	$('tab-videos').addEvent('click', function(){ 
		displayBlock('.data', 'videos'); 
		if ( ! this.retrieve('loaded')) { mediaManager.loadMediaList('video'); this.store('loaded', true);}
	});
	
	$('tab-pictures').addEvent('click', function() {
		displayBlock('.data', 'pictures');
		if ( ! this.retrieve('loaded')) { mediaManager.loadMediaList('picture'); this.store('loaded', true);}
	});
	
	
	
	if ($('tab-articles'))
	{
		/**
		 * Article create button link
		 */
		var item = $('articleCreate');
		if (item != null)
		{
			var url = item.getProperty('href');
	
			item.addEvent('click', function(e)
			{
				var e = new Event(e).stop();
				
				MUI.updateContent({
					'element': $('mainPanel'),
					'loadMethod': 'xhr',
					'url': url,
					'title': Lang.get('ionize_title_create_article')
				});
			});
		}
	
		
		/**
		 * Article list itemManager
		 *
		 */
		articleManager = new IonizeItemManager(
		{
			parent: 	'page',
			idParent: 	'<?= $id_page ?>',
			element: 	'article',
			container: 	'articleContainer'
		});
		
		articleManager.makeSortable();
	}


	/**
	 * Page itemManager
	 *
	 */
	pageManager = new IonizeItemManager(
	{
		element: 	'page'
	});

</script>