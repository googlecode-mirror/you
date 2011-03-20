<?php

/**
 * View used by extend_field controller to display again the extend fields table after an ADD / DELETE of one extend field
 *
 */
?>

<?php if( !empty($extend_fields)) :?>

	<h3><?= lang('ionize_title_'.$parent.'s') ?></h3>

	<ul id="extendfieldsContainer" style="clear:both;overflow:hidden;">
	
	<?php foreach($extend_fields as $extend_field) :?>
	
		<li class="sortme extend_field<?= $extend_field['id_extend_field'] ?>" id="extend_field_<?= $extend_field['id_extend_field'] ?>">
			<img class="icon right" onclick="javascript:extendfieldsManager.deleteItem('<?= $extend_field['id_extend_field'] ?>')" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" alt="<?= lang('ionize_label_delete') ?>" />
			<img class="icon left drag" src="<?= base_url() ?>themes/admin/images/icon_16_ordering.png" />
			<a class="left pl5" href="javascript:void(0);" onclick="javascript:MUI.formWindow('extendfield', 'extendfieldForm', '<?= lang('ionize_title_extend_field') ?>', 'admin/extend_field/edit/<?= $extend_field['id_extend_field'] ?>/<?=$parent?>', {width: 400, height: 330});" title="<?= $extend_field['name'] ?> : <?= $extend_field['description'] ?>"><?= $extend_field['name'] ?> | <?= $extend_field['label'] ?></a>
		</li>
	
	<?php endforeach ;?>
	
	</ul>
	
	<script type="text/javascript">
	
		extendfieldsManager = new IonizeItemManager(
		{
			parent: 	'<?=$parent?>',
			element: 	'extend_field',
			container: 	'extendfieldsContainer'
		});
		
		extendfieldsManager.makeSortable();
	
	
	</script>
	

<?php endif; ?>
