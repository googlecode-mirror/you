
<!-- Existing article types -->

<ul id="typeContainer">

<?php foreach($types as $type) :?>

	<li class="sortme article_type<?= $type['id_type'] ?>" id="article_type_<?= $type['id_type'] ?>">
		<img class="icon right" onclick="javascript:typesManager.deleteItem('<?= $type['id_type'] ?>');" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" alt="<?= lang('ionize_label_delete') ?>" />
		<img class="icon left drag" src="<?= base_url() ?>themes/admin/images/icon_16_ordering.png" />
		<a class="left pl5" href="javascript:void(0);" onclick="javascript:MUI.formWindow('Type', 'typeForm', 'ionize_title_article_type_edit', 'admin/article_type/edit/<?= $type['id_type'] ?>/<?= $parent ?>/<?= $id_parent ?>', {width: 360, height: 75});" title="edit"><?= $type['type'] ?></a>
	</li>

<?php endforeach ;?>

</ul>

<script type="text/javascript">

	/**
	 * Categories list itemManager
	 *
	 */
	typesManager = new IonizeItemManager(
	{
		parent: 	'<?= $parent ?>',
		idParent: 	'<?= $id_parent ?>',
		element: 	'article_type',
		container: 	'typeContainer'	
	});
	
	typesManager.makeSortable();

</script>
