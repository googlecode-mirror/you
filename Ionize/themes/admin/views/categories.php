
<!-- Existing categories -->

<ul id="categoryContainer">

<?php foreach($categories as $category) :?>

	<li class="sortme category<?= $category['id_category'] ?>" id="category_<?= $category['id_category'] ?>">
		<img class="icon right" onclick="javascript:categoriesManager.deleteItem('<?= $category['id_category'] ?>');" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" alt="<?= lang('ionize_label_delete') ?>" />
		<img class="icon left drag" src="<?= base_url() ?>themes/admin/images/icon_16_ordering.png" />
		<a class="left pl5" href="javascript:void(0);" onclick="javascript:MUI.formWindow('Category', 'categoryForm', '<?= lang('ionize_title_category_edit') ?>', 'admin/category/edit/<?= $category['id_category'] ?>/<?= $parent ?>/<?= $id_parent ?>', {width: 360, height: 230});" title="edit"><?= $category['name'] ?></a>
	</li>

<?php endforeach ;?>

</ul>

<script type="text/javascript">

	/**
	 * Categories list itemManager
	 *
	 */
	categoriesManager = new IonizeItemManager(
	{
		parent: 	'<?= $parent ?>',
		idParent: 	'<?= $id_parent ?>',
		element: 	'category',
		container: 	'categoryContainer'	
	});
	
	categoriesManager.makeSortable();

</script>
