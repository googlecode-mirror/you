<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.90
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

class Users extends MY_admin 
{

	var $current_user_level = -100;

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->base_model->set_table('users');
		$this->base_model->set_pk_name('id_user');

		// Users model
		$this->load->model('users_model', '', true);

		// Current connected user level
		$user = $this->connect->get_current_user();
		$this->current_user_level = $user['group']['level'];
	}


	// ------------------------------------------------------------------------


	/**
	 * Shows existing users and groups
	 *
	 */
	function index()
	{
		// Get user list filtered on levels <= current_user level
		$this->template['users'] = array_filter($this->connect->model->get_users(), array($this, '_filter_users'));
			
		// Get groups list filtered on level <= current_user level
		$this->template['groups'] = array_filter($this->connect->model->get_groups(array('order_by'=>'level DESC')), array($this, '_filter_groups'));

		// Send the current user's level to the view
		$this->template['current_user_level'] = $this->current_user_level;

		// Meta data list
		$meta_data = $this->base_model->field_data('users_meta');
		
		// Filter meta data : Don't add PK
		foreach ($meta_data as $field)
		{
			if ($field['Field'] != 'id_user')
			{
				$this->template['meta_data'][] = $field;
			}
		}

		$this->output('users');
	}


	// ------------------------------------------------------------------------


	/**
	 * Edit one user
	 *
	 */
	function edit($id)
	{
		$this->template['user'] = $this->connect->model->find_user(array('id_user' => $id));
		
		// Get groups list filtered on level <= current_user level
		$this->template['groups'] = array_filter($this->connect->model->get_groups(array('order_by'=>'level')), array($this, '_filter_groups'));
		
		// Get users meta data
		$this->template['meta_data_fields'] =  $this->users_model->get_meta_fields();
		$this->template['meta_data'] = $this->users_model->get_meta($id, $this->template['meta_data_fields']);
		
		$this->output('user');
	}


	// ------------------------------------------------------------------------


	/**
	 * Update one user
	 *
	 */
	function update()
	{
		if ($id_user = $this->input->post('user_PK'))
		{
			
			// Update array
			$data = array(
						'id_group' =>	$this->input->post('id_group'),
						'username' =>		$this->input->post('username'),
						'screen_name' =>	$this->input->post('screen_name'),
						'email' =>			$this->input->post('email'),
						'join_date' =>			$this->input->post('join_date'),
						'salt' =>			$this->input->post('salt')
					);

			if (($this->input->post('password') != '' && $this->input->post('password2') != '') &&
				($this->input->post('password') == $this->input->post('password2'))	)
			{
				$data['password'] = $this->connect->encrypt($this->input->post('password'), $data);
			}

			// Update the user
			$this->base_model->update($id_user, $data);
			
			// Update the user's meta
			$this->users_model->save_meta($id_user, $_POST);
			
			// UI update panels
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' => site_url('admin/users')
			);
			
			// Success message
			$this->success(lang('ionize_message_user_updated'));
		}		
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves one new user
	 *
	 */
	function save()
	{
		if (($this->input->post('username') && $this->input->post('password') && $this->input->post('email') ) && 
			($this->input->post('password') == $this->input->post('password2'))	
		)
		{
			// Insert array
			$data = array(
						'id_group' =>		$this->input->post('id_group'),
						'username' =>		$this->input->post('username'),
						'screen_name' =>	$this->input->post('screen_name'),
						'password' =>		$this->input->post('password'),
						'email' =>			$this->input->post('email'),
						'join_date' =>		date('Y-m-d H:i:s'),
						'salt' =>			$this->connect->get_salt()
					);
			
			$data['password'] = $this->connect->encrypt($data['password'], $data);
			
			// Save new user only if it not exists
			if ( ! $this->base_model->exists(array('username' => $data['username'])))
			{
				// DB insertion
				$id = $this->base_model->insert($data);

				// Update the user's meta
				$this->users_model->save_meta($id, $_POST);

				// UI update panels
				$this->update[] = array(
					'element' => 'mainPanel',
					'url' => site_url('admin/users')
				);
				
				// JSON answer
				$this->success(lang('ionize_message_user_saved'));
			}
			else
			{
				$this->error(lang('ionize_message_user_exists'));
			}
		}
		else
		{
			$this->error(lang('ionize_message_user_not_saved'));
		}
	}
	

	// ------------------------------------------------------------------------

	
	/**
	 * Deletes one user
	 *
	 */
	function delete($id)
	{
		$current_user = $this->connect->get_current_user();

		if ($current_user['id_user'] != $id)
		{
			$affected_rows = $this->users_model->delete($id);
	
			if ($affected_rows > 0)
			{
				$this->id = $id;
				
				$this->success(lang('ionize_message_user_deleted'));
			}
			else
			{
				$this->error(lang('ionize_message_user_not_deleted'));
			}
		}
		else
		{
			$this->error(lang('ionize_message_user_cannot_delete_yourself'));
		}
	}
	
	
	// ------------------------------------------------------------------------

	
	/**
	 * Export the users list
	 *
	 */
	function export($format = NULL)
	{
		$format = ( ! is_null($format)) ? $format : $this->input->post('format');
	
		// Load download helper
		$this->load->helper('download');
		
		// Get users & users meta data
		$users = $this->users_model->get_list($_POST['metas']);
		
		// If users, get the format
		if (!empty($users))
		{
			// Export in in asked format
			switch($format)
			{
				case 'csv': $this->_export_csv($users);
			}
			
			$this->success(lang('ionize_message_users_exported'));

		}
		else
		{
			$this->error(lang('ionize_message_users_not_exported'));		
		}

	}


	// ------------------------------------------------------------------------

	
	/**
	 * Export the email list to CSV
	 *
	 */
	private function _export_csv($users)
	{
		$data= array();

		// Add columns names to file header
		$data[] = implode(';', array_keys($users[0]));

		// Add users to data table
		foreach($users as $user)
		{
			$data[] = implode(';', $user);
		}
		
		// Add new line 
		$data = implode("\r\n", $data);

		// File name
		$name = Settings::get('theme').'_users.csv';

		// Send the file to the user
		force_download($name, $data);
	}


	// ------------------------------------------------------------------------


	/**
	 * Users filter callback function
	 *
	 */
	function _filter_users($row)
	{
		return ($row['group']['level'] <= $this->current_user_level) ? true : false; 
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


/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */