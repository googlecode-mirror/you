<?php

/** 
 * Media picture list view
 * Used by ionizeMediaManager to display current aticles picture list
 */
?>

<?php foreach ($items as $media) :?>

	<?php
	
	// Thumb URL
	$thumb_dir = (Settings::get('system_thumb_list')) ? Settings::get('system_thumb_list') : 'thumb';
	$pictureUrl =	base_url().$media['base_path'].$thumb_dir.'/'.$media['file_name'];

	// Get picture thumb size	
	$width = $height = $type = $attr = '';
	
	if (is_file($pictureUrl))
		list($width, $height, $type, $attr) = @getimagesize($pictureUrl);
	
	?>
	
	<div class="picture drag" style="min-width:70px;width:<?= $width ?>px;" id="picture_<?= $media['id_media'] ?>">
		<img style="position: relative;" id="img_<?= $media['id_media'] ?>" src="<?= $pictureUrl ?>?t=<?=time()?>" alt="<?= $media['file_name'] ?>" />
		<p>
			<a href="javascript:MUI.formWindow('<?= $media['type'].$media['id_media'] ?>', 'mediaForm<?= $media['id_media'] ?>', '<?= $media['file_name'] ?>', 'admin/media/edit/picture/<?= $media['id_media'] ?>', {width:500,height:430});" title="edit : <?= $media['file_name'] ?>"><img src="<?=base_url()?>themes/admin/images/icon_16_edit.png"/></a>
			<a href="javascript:mediaManager.initThumbs('<?= $media['id_media'] ?>');" title="<?= lang('ionize_label_init_thumb') ?>"><img src="<?=base_url()?>themes/admin/images/icon_16_refresh.png"/></a>
			<a href="javascript:mediaManager.detachMedia('<?= $media['type'] ?>', '<?= $media['id_media'] ?>');" title="<?= lang('ionize_label_detach_media') ?>"><img src="<?=base_url()?>themes/admin/images/icon_16_delete.png"/></a>
		</p>

	</div>
	
<?php endforeach ;?>