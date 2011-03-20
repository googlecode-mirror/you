<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.4
 */

// ------------------------------------------------------------------------

/**
 * Ionize Category Controller
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Users management
 * @author		Ionize Dev Team
 *
 */

class Groups extends MY_admin 
{

	var $current_user_level = -100;

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

//		$this->connect->restrict('editors');

		$this->base_model->set_table('user_groups');
		$this->base_model->set_pk_name('id_group');

		// Current connected user level
		$user = $this->connect->get_current_user();
		$this->current_user_level = $user['group']['level'];
	}


	// ------------------------------------------------------------------------


	/**
	 * Do nothing. See users.php
	 *
	 */
	function index()
	{
	}


	// ------------------------------------------------------------------------


	/**
	 * Edit one group
	 *
	 */
	function edit($id)
	{
		$this->template['group'] = $this->connect->model->find_group(array('id_group' => $id));

		// Get groups list filtered on level <= current_user level
		$this->template['groups'] = array_filter($this->connect->model->get_groups(), array($this, '_filter_groups'));
				
		$this->output('group');
	}


	// ------------------------------------------------------------------------


	/**
	 * Update one group
	 *
	 */
	function update()
	{
		if ($id_group = $this->input->post('group_PK'))
		{
			
			// Update array
			$data = array(
						'slug' =>			$this->input->post('slug'),
						'level' =>			$this->input->post('level'),
						'group_name' =>			$this->input->post('group_name'),
						'description' =>	$this->input->post('description')
					);

			
			// Update the group
			$this->base_model->update($id_group, $data);

			// UI update panels
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' => site_url('admin/users')
			);
			
			// Success message
			$this->success(lang('ionize_message_group_updated'));
		}		
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves one new group
	 *
	 */
	function save()
	{
		// Insert array
		$data = array(
					'slug' =>			$this->input->post('slug'),
					'level' =>			$this->input->post('level'),
					'group_name' =>			$this->input->post('group_name'),
					'description' =>	$this->input->post('description')
				);
		
		// Save new user only if it not exists
		if (!$this->base_model->exists(array('slug' => $data['slug'])))
		{
			// DB insertion
			$this->base_model->insert($data);

			// UI update panels
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' => site_url('admin/users')
			);
			
			// JSON answer
			$this->success(lang('ionize_message_group_saved'));
		}
		else
		{
			$this->error(lang('ionize_message_group_not_saved'));
		}
	}
	

	// ------------------------------------------------------------------------

	
	/**
	 * Deletes one group
	 * 
	 * @TODO : Add check before delete one group
	 *
	 */
	function delete($id)
	{
		// Here, Add group check : 
		// - No users in the group
		// - Group level must be < current connected user

		$affected_rows = $this->base_model->delete($id);

		if ($affected_rows > 0)
		{
			$this->id = $id;
			
			// UI update panels
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' => site_url('admin/users')
			);

			$this->success(lang('ionize_message_group_deleted'));
		}
		else
		{
			$this->error(lang('ionize_message_group_not_deleted'));
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Groups filter callback function
	 *
	 */
	function _filter_groups($row)
	{
		return ($row['level'] <= $this->current_user_level) ? true : false; 
	}

}


/* End of file groups.php */
/* Location: ./application/controllers/admin/groups.php */