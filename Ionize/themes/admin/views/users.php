<?php

/**
 * Displays the list of all users
 *
 */
?>
<div id="sidecolumn" class="close">

	<div id="options">

		<!-- New user -->
		<h3 class="toggler"><?=lang('ionize_title_add_user')?></h3>

		<div class="element">

			<form name="newUserForm" id="newUserForm" method="post" action="<?= base_url() ?>admin/users/save">

				<!-- Username -->
				<dl>
					<dt class="small">
						<label for="username"><?=lang('ionize_label_username')?></label>
					</dt>
					<dd>
						<input id="username" name="username" class="inputtext w140" type="text" value="" />
					</dd>
				</dl>
				
				<!-- Screen name -->
				<dl>
					<dt class="small">
						<label for="screen_name"><?=lang('ionize_label_screen_name')?></label>
					</dt>
					<dd>
						<input id="screen_name" name="screen_name" class="inputtext w140" type="text" value="" />
					</dd>
				</dl>
				
				<!-- Group -->
				<dl>
					<dt class="small">
						<label for="group_FK"><?=lang('ionize_label_group')?></label>
					</dt>
					<dd>
						<select name="id_group" class="select">
							<?php foreach($groups as $group) :?>
							
								<option value="<?= $group['id_group'] ?>"><?= $group['group_name'] ?></option>
							
							<?php endforeach ;?>
						</select>
					</dd>
				</dl>
				
				<!-- Email -->
				<dl>
					<dt class="small">
						<label for="email"><?=lang('ionize_label_email')?></label>
					</dt>
					<dd>
						<input id="email" name="email" class="inputtext w140" type="text" value="" />
					</dd>
				</dl>
				
				<!-- Password -->
				<dl>
					<dt class="small">
						<label for="password"><?=lang('ionize_label_password')?></label>
					</dt>
					<dd>
						<input id="password" name="password" class="inputtext w120" type="password" value="" />
					</dd>
				</dl>

				<!-- Password confirm -->
				<dl>
					<dt class="small">
						<label for="password2"><?=lang('ionize_label_password2')?></label>
					</dt>
					<dd>
						<input id="password2" name="password2" class="inputtext w120" type="password" value="" />
					</dd>
				</dl>

				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_new_user" type="submit" class="submit" value="<?= lang('ionize_button_save') ?>" />
					</dd>
				</dl>

			</form>

			<script type="text/javascript">
				

			</script>

		</div> <!-- /element -->
		
		
		<!-- New Group -->
		<h3 class="toggler"><?=lang('ionize_title_add_group')?></h3>

		<div class="element">

			<form name="newGroupForm" id="newGroupForm" method="post" action="<?= base_url() ?>admin/groups/save">

				<!-- Group name -->
				<dl>
					<dt class="small">
						<label for="slug"><?=lang('ionize_label_group_name')?></label>
					</dt>
					<dd>
						<input id="slug" name="slug" class="inputtext w140" type="text" value="" />
					</dd>
				</dl>
				
				<!-- Group Title -->
				<dl>
					<dt class="small">
						<label for="group_name"><?=lang('ionize_label_group_title')?></label>
					</dt>
					<dd>
						<input id="group_name" name="group_name" class="inputtext w140" type="text" value="" />
					</dd>
				</dl>
				
				<!-- Description -->
				<dl>
					<dt class="small">
						<label for="description"><?=lang('ionize_label_group_description')?></label>
					</dt>
					<dd>
						<input id="description" name="description" class="inputtext w140" type="text" value="" />
					</dd>
				</dl>
				
				<!-- Level -->
				<dl>
					<dt class="small">
						<label for="level"><?=lang('ionize_label_group_level')?></label>
					</dt>
					<dd>
						<select name="level" class="select">
							<?php foreach($groups as $group) :?>
							
								<option value="<?= $group['level'] ?>"><?= $group['group_name'] ?></option>
							
							<?php endforeach ;?>
						</select>
					</dd>
				</dl>
				
				<!-- Submit button  -->
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_new_group" type="submit" class="submit" value="<?= lang('ionize_button_save') ?>" />
					</dd>
				</dl>

			</form>
			
		</div> <!-- /element -->
		

		<!-- Users list export -->
		<h3 class="toggler"><?=lang('ionize_title_users_export')?></h3>

		<div class="element">

			<form name="userExportForm" id="userExportForm" method="post" action="<?= base_url() ?>admin/users/export">

				<dl>
					<dt class="small"><?= lang('ionize_label_export_meta') ?></dt>
					<dd>
						<?php foreach($meta_data as $meta) :?>
							<input id="meta_<?= $meta['Field'] ?>" name="metas[]" type="checkbox" value="<?= $meta['Field'] ?>" />
							<label for="meta_<?= $meta['Field'] ?>"><?= $meta['Field'] ?></label>
							<br/>
						<?php endforeach ;?>
					</dd>
				</dl>
				
				<dl>
					<dt class="small"><?= lang('ionize_label_export_format') ?></dt>
					<dd>
						<input id="format" name="format" type="radio" checked="checked" value="csv" />
						<label for="format">CSV</label>
					</dd>
				</dl>
	
				<dl>
					<dt class="small">&#160;</dt>
					<dd>
						<input id="submit_user_export" type="submit" class="submit" value="<?= lang('ionize_button_export') ?>" />
					</dd>
				</dl>
				
			</form>

		</div>
		
	</div> <!-- /options -->
</div> <!-- /sidecolumn -->


<div id="maincolumn">


	<!-- Existing groups table -->
	<h3><?=lang('ionize_title_existing_groups')?></h3>

	<table class="list" id="groupsTable">

		<thead>
			<tr>
				<th axis="string"><?= lang('ionize_label_group_name') ?></th>
				<th axis="string"><?= lang('ionize_label_group_title') ?></th>
				<th axis="string"><?= lang('ionize_label_group_level') ?></th>
				<th axis="string"><?= lang('ionize_label_group_description') ?></th>				
				<th></th>
			</tr>
		</thead>

		<tbody>
		
		<?php foreach($groups as $group) :?>
			
			<tr class="users<?= $group['id_group'] ?>">
				<td><a class="group" id="group<?= $group['id_group'] ?>" rel="<?= $group['id_group'] ?>" href="<?= site_url('admin/groups/edit/'. $group['id_group']) ?>"><?= $group['slug'] ?></a></td>
				<td><?= $group['group_name'] ?></td>
				<td><?= $group['level'] ?></td>
				<td><?= $group['description'] ?></td>
				<td>
					<?php if( $current_user_level > $group['level']) :?>
						<img class="pointer" onclick="javascript:groupsManager.deleteItem('<?= $group['id_group'] ?>')" src="<?= base_url() ?>themes/admin/images/icon_16_delete.png" />
					<?php endif ;?>
				</td>
			</tr>

		<?php endforeach ;?>
		
		</tbody>

	</table>


	<!-- Existing users table -->
	<h3><?=lang('ionize_title_existing_users')?></h3>

	<table class="list" id="usersTable">

		<thead>
			<tr>
				<th axis="string"><?= lang('ionize_label_username') ?></th>
				<th axis="string"><?= lang('ionize_label_screen_name') ?></th>
				<th axis="string"><?= lang('ionize_label_group') ?></th>
				<th axis="string"><?= lang('ionize_label_email') ?></th>				
				<th></th>
			</tr>
		</thead>

		<tbody>
		
		<?php foreach($users as $user) :?>
			
			<tr class="users<?= $user['id_user'] ?>">
				<td><a class="user" id="user<?= $user['id_user'] ?>" rel="<?= $user['id_user'] ?>" href="<?= site_url('admin/users/edit/'. $user['id_user']) ?>"><?= $user['username'] ?></a></td>
				<td><?= $user['screen_name'] ?></td>
				<td><?= $user['group']['group_name'] ?></td>
				<td><?= $user['email'] ?></td>
				<td><img class="pointer" onclick="javascript:usersManager.deleteItem('<?= $user['id_user'] ?>')" src="<?= base_url() ?>themes/admin/images/icon_16_delete.png" /></td>
			</tr>

		<?php endforeach ;?>
		
		</tbody>

	</table>

</div> <!-- /maincolumn -->


<script type="text/javascript">

	/**
	 * Panel toolbox
	 *
	 */
	MUI.initToolbox('options_toolbox');


	/**
	 * Options Accordion
	 *
	 */
	MUI.initAccordion('.toggler', 'div.element');


	/**
	 * Init help tips on label
	 *
	 */
	MUI.initLabelHelpLinks('#newUserForm');
	MUI.initLabelHelpLinks('#newGroupForm');
	MUI.initLabelHelpLinks('#userExportForm');

	/**
	 * New user form action
	 * see init.js for more information about this method
	 */
	MUI.setFormSubmit('newUserForm', 'submit_new_user', 'admin/users/save');
	MUI.setFormSubmit('newGroupForm', 'submit_new_group', 'admin/groups/save');
//	MUI.setFormSubmit('userExportForm', 'submit_user_export', $('userExportForm').action);


	/**
	 * Events to each user
	 * Opens an edition window
	 */
	$$('.user').each(function(item)
	{
		item.addEvent('click', function(e)
		{
			var e = new Event(e).stop();
			var id = item.getProperty('rel');
			MUI.formWindow(	id, 							// object ID
								'userForm',					// Form ID
								'ionize_title_user_edit', 	// Window title
								'admin/users/edit/' + id,	// Window content URL
								{width: 340, height: 320}	// Window options
			);
		});
	});

	/**
	 * Events to each group
	 * Opens an edition window
	 */
	$$('.group').each(function(item)
	{
		item.addEvent('click', function(e)
		{
			var e = new Event(e).stop();
			var id = item.getProperty('rel');
			MUI.formWindow(	id, 							// object ID
								'groupForm',						// Form ID
								'ionize_title_group_edit', 		// Window title
								'admin/groups/edit/' + id,		// Window content URL
								{width: 340, height: 230}		// Window options
			);
		});
	});

	/**
	 * Users itemManager
	 * Manager delete
	 *
	 */
	usersManager = new IonizeItemManager(
	{
		element: 	'users'
	});

	/**
	 * Groups itemManager
	 *
	 */
	groupsManager = new IonizeItemManager(
	{
		element: 	'groups'
	});

	
	/**
	 * Adds Sortable function to the user list table
	 *
	 */
	new sortableTable('usersTable',{sortOn: 0, sortBy: 'ASC'});
	new sortableTable('groupsTable',{sortOn: 3, sortBy: 'ASC'});

</script>





