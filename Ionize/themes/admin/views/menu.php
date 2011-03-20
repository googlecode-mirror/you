
<div id="sidecolumn" class="close">

	<div id="options">

		<!-- New Menu -->
		<h3 class="toggler"><?=lang('ionize_title_add_menu')?></h3>

		<div class="element">

			<form name="newMenuForm" id="newMenuForm" method="post" action="<?= base_url() ?>admin/menu/save">

				<!-- Menu Name -->
				<dl>
					<dt class="small">
						<label for="name_new"><?=lang('ionize_label_name')?></label>
					</dt>
					<dd>
						<input id="name_new" name="name_new" class="inputtext w40" type="text" value="" />
					</dd>
				</dl>

				<!-- Menu Title -->
				<dl>
					<dt class="small">
						<label for="title_new"><?=lang('ionize_label_title')?></label>
					</dt>
					<dd>
						<input id="title_new" name="title_new" class="inputtext w140" type="text" value=""/><br />
					</dd>
				</dl>

				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_new" type="submit" class="submit" value="<?= lang('ionize_button_save_new_menu') ?>" />
					</dd>
				</dl>
				
			</form>

		</div> <!-- /element -->

	</div> <!-- /options -->

</div> <!-- /sidecolumn -->


<!-- Main Column -->

<div id="maincolumn">

	<form name="existingMenuForm" id="existingMenuForm" method="post" action="<?= base_url() ?>admin/menu/update">

	<h3><?=lang('ionize_title_existing_menu')?></h3>
	

	<!-- Sortable UL -->
	<ul id="menuContainer" class="sortable">

		<?php foreach($menus as $menu) :?>

			<?php
				$name = $menu['name'];
				$id = $menu['id_menu'];
				$title = $menu['title'];
			?>

			<li id="menu_<?= $id ?>" class="sortme ">

				<!-- Drag icon -->
				<div class="drag" style="float:left;height:100px;">
					<img src="<?=base_url()?>themes/admin/images/icon_16_ordering.png" />
				</div>

				<!-- Name -->
				<dl>
					<dt>
						<label for="name_<?= $id ?>"><?=lang('ionize_label_name')?></label>
					</dt>
					<dd>
						<?php if($id < 3) :?> 
							<input type="text" disabled="disabled" value="<?= $name ?>"  class="inputtext" />
						<?php endif ;?>
						
						<input type="<?php if($id < 3) :?>hidden<?php else :?>text<?php endif ;?>" name="name_<?= $id ?>" id="name_<?= $id ?>" class="inputtext" value="<?= $name ?>"/>
						
						<!-- Delete button -->
						<?php if($id > 2) :?>
							<img title="<?=lang('ionize_button_delete')?>" onclick="javascript:menuManager.deleteItem('<?= $id ?>')" class="inputicon pointer" src="<?=base_url()?>themes/admin/images/icon_16_delete.png" />
						<?php endif ;?>
					</dd>
				</dl>

				<!-- Title -->
				<dl>
					<dt>
						<label for="title_<?= $id ?>"><?=lang('ionize_label_title')?></label>
					</dt>
					<dd>
						<input name="title_<?= $id ?>" id="title_<?= $id ?>" class="inputtext" type="text" value="<?= $title ?>"/>
					</dd>
				</dl>

			</li>

		<?php endforeach ;?>

		</ul>

	</form>


</div> <!-- /maincolumn -->


<script type="text/javascript">
	

	/**
	 * Form action
	 * see init-form.js for more information about this method
	 *
	 */
	MochaUI.setFormSubmit('newMenuForm', 'submit_new', 'admin/menu/save');

	
	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('menu_toolbox');

				
	MUI.initAccordion('.toggler', 'div.element');
	


	/*
	 * Menu itemManager
	 * Use of ItemManager.deleteItem, etc.
	 */
	menuManager = new IonizeItemManager(
	{
		element: 	'menu',
		container: 	'menuContainer'		
	});
	
	menuManager.makeSortable();

	
</script>



