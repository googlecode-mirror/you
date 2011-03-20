
<div id="maincolumn">


	<form name="translationForm" id="translationForm" method="post" action="<?= base_url() ?>admin/translation/save">


		<fieldset id="blocks">

			<?php
				$nbLang = count(Settings::get_languages());
				$width = (100 / $nbLang) - 2;
			?>
			
			<div id="block" class="block data">

				<?php foreach($views_terms['term'] as $term) :?>
				
					<div class="term">
					
						<label class="toggler" for="<?= $term ?>"><?= $term ?></label>
						
						<div class="translation" id="el-<?= $term ?>" style="margin-left:20px;">
						
							<?php foreach(Settings::get_languages() as $language) :?>
						
								<?php $lang = $language['lang']; ?>
								
								<div style="float:left;width:<?=$width?>%;" class="ml5">
									<label for="<?= $term ?>_<?=$lang?>"><?=$language['name']?></label>
									<textarea name="<?= $term ?>_<?=$lang?>" id="<?= $term ?>_<?=$lang?>" class="h60 ml5" style="width:100%;"><?= $translated_items[$lang][$term] ?></textarea>
								
								</div>
							
							<?php endforeach ;?>
												
							<p style="line-height:1em;clear:both;margin-left"><span style="color:#666;font-size:0.9em;margin-left:10px;"><?= $views_terms['views'][$term] ?></span></p>
						
						</div>
					</div>

				<?php endforeach ;?>
		
			</div>
		</fieldset>
	</form>
</div>

<script type="text/javascript">
	
	/**
	 * Panel toolbox
	 * Init the panel toolbox is mandatory !!! 
	 *
	 */
	MUI.initToolbox('translation_toolbox');


	
	$$('#block .toggler').each(function(el)
	{
		el.fx = new Fx.Slide($('el-' + el.getProperty('for')), {
		    mode: 'vertical',
		    duration: 200
		});
		el.fx.hide();
	});
	
	
	$$('#block .toggler').addEvent('click', function()
	{
		this.fx.toggle();
		this.toggleClass('expand');
		this.getParent().toggleClass('highlight');
	});
	
</script>