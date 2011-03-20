<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model handling database interacions for the Connect library.
 */
//  CI 2.0 Compatibility
if(!class_exists('CI_Model')) { class CI_Model extends Model {} }

class Connect_model extends CI_Model 
{
	public $error = false;
	
	/**
	 * The table to store users in.
	 *
	 * @var string
	 */
	public $users_table = 'users';
	
	/**
	 * The table to store groups in.
	 *
	 * @var string
	 */
	public $groups_table = 'user_groups';
	
	/**
	 * Users table's PK
	 *
	 * @var string
	 */
	public $users_pk = 'user_id';
	
	/**
	 * Groups table's PK
	 *
	 * @var string
	 */
	public $groups_pk = 'group_id';
	
	/**
	 * The table storing the access attempt data.
	 *
	 * @var string
	 */
	public $tracker_table = 'login_tracker';
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Contructor
	 *
	 */
	function __construct()
    {
        parent::__construct();

		$this->load->config('connect');

		$this->users_table 	= config_item('users_table');
		$this->users_pk 	= config_item('users_table_pk');
		
		$this->groups_table = config_item('groups_table');
		$this->groups_pk 	= config_item('groups_table_pk');

    }
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Finds a user.
	 *
	 * @param $identification An array or string that identifies the user
	 *        Like array('email' => 'the email') or just the username
	 * @return object
	 */
	public function find_user($identification)
	{		
		if( ! is_array($identification))
		{
			$identification = array('username' => $identification);
		}

		// return false if there are no conditions
		if( ! $this->num_conds($identification))
		{
			$this->error = $this->connect->set_error_message('connect_parameter_error', 'Connect_model::find_user()');
		}

		$identification = array_merge($identification, array('limit' => 1));

		$result = $this->get_users($identification);

		if(empty($result))
		{
			return false;
		}

		return array_shift($result);
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Finds an arbitrary amount of users.
	 * 
	 * @param  array  The conditions to filter by, also limit, offset and order by
	 *                limit, offset and order_by are sent to the IgnitedQuery methods
	 *                with the same name
	 * @return array  Array with User_records in it (groups are also stored in the user record)
	 */
	public function get_users($cond = array())
	{		
		foreach(array('limit', 'offset', 'order_by') as $key)
		{
			if(isset($cond[$key]))
			{
				call_user_func(array($this->db, $key), $cond[$key]);
				unset($cond[$key]);
			}
		}
		
		$this->db->join($this->groups_table, $this->users_table.'.'.$this->groups_pk.' = '.$this->groups_table.'.'.$this->groups_pk, 'left');
		
		$query = $this->db->get_where($this->users_table, $cond);

		$result = array();

		foreach($query->result_array() as $row)
		{
			$result[] = $this->split_user_group($row);
		}

		return $result;
	}
	
	
	// --------------------------------------------------------------------
	
	
	public function save_user($user_data = array())
	{
		return $this->db->insert($this->users_table, $user_data);
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Bans a user.
	 * 
	 * @param  int   The user id
	 * @return bool
	 */
	public function ban_user($user_id)
	{		
		// don't allow the current user to ban himself by id, let him use the direct method instead:
		// Access()->get_current_user()->ban();
		if($this->connect->get_current_user() && $this->connect->get_current_user()->user_id == $user_id)
		{
			$this->error = $this->connect->set_error_message('connect_cannot_ban_yourself');
		}
		
		$query->select('group_id')
			  ->from($this->groups_table)
			  ->where('slug', $this->connect->banned_user_group);
		
		return $this->db->update($this->users_table, array('group_id' => $query), array('user_id' => $user_id), 1);
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Finds a certain group.
	 * 
	 * @param  int|array  id or condition
	 * @return Group_record
	 */
	public function find_group($id)
	{
		if( ! is_array($id))
		{
			$id = array($this->groups_pk => $id);
		}

		$query = $this->db->get_where($this->groups_table, $id, 1);

		if( ! $query->num_rows())
		{
			return false;
		}

		return $query->row_array();
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Finds an arbitary amount of groups.
	 * 
	 * @param  array  The conditions to filter by, also limit, offset and order by
	 *                limit, offset and order_by are sent to the IgnitedQuery methods
	 *                with the same name
	 * @return array  Array with Group_records in it
	 */
	public function get_groups($cond = array())
	{
		foreach(array('limit', 'offset', 'order_by') as $key)
		{
			if(isset($cond[$key]))
			{
				call_user_func_array(array($this->db, $key), (Array) $cond[$key]);
				unset($cond[$key]);
			}
		}
		
		$query = $this->db->get_where($this->groups_table, $cond);

		$result = $query->result_array();

		return $result;
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Counts the identification values because empty may enable fetching of any user -
	 * a potential security vulnerability.
	 * 
	 * @param  mixed
	 * @return int
	 */
	private function num_conds($conds = array())
	{
		$num_conds = 0;
		foreach((Array) $conds as $key => $row)
		{
			if( ! empty($row) && ! empty($key))
			{
				$num_conds++;
			}
		}
		
		return $num_conds;
	}
	
	
	// --------------------------------------------------------------------
	
	
	function check_duplicate($str, $type)
	{
		return $this->db->select('1', false)->where($type, $str)->get($this->users_table)->num_rows;
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Splits the group and user data into separate objects, user->group = group object.
	 *
	 * @param $data the data
	 * @return object
	 */
	private function split_user_group($data)
	{
		$g_data = array();

		foreach(array($this->groups_pk, 'slug', 'level', 'group_name') as $col)
		{
			$g_data[$col] = $data[$col];
			unset($data[$col]);
		}

		$data[$this->groups_pk] = $g_data[$this->groups_pk];
		$data['group'] = $g_data;

		return $data;
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Sets the group for a user.
	 * 
	 * @param  int|string|array  String = slug, int = group_id
	 * @return void
	 */
	public function set_group($group = null)
	{		
		if(is_numeric($group))
		{
			$this->group_id = $group;
		}
		elseif(is_array($group))
		{
			$this->group_id = $group[$this->groups_pk];
		}
		else
		{
			if( ! empty($group) && $g = $this->find_group(array('slug' => $group)))
			{
				$this->group_id = $g[$this->groups_pk];
			}
			else
			{
				// just assign the lowest level of access, subquery
				$this->db
					->select('slug')
					->from($this->groups_table)->order_by('level', 'asc');
				
				$this->group_id =& $query;
			}
		}
		
		return $this->group_id;
	}
	
	
	// --------------------------------------------------------------------
	
	
	/**
	 * Updates the last visit counter.
	 * 
	 * @param  string  Date string formatted like 'Y-m-d H:i:s'
	 * @return void
	 */
	public function update_last_visit($user, $date = false)
	{
		$last_visit = $date ? $date : date('Y-m-d H:i:s');
		
		return $this->db->where($this->users_pk, $user[$this->users_pk])
					->update($this->users_table, array('last_visit' => $last_visit));
	}
	
	
	// --------------------------------------------------------------------
	
	
	public function save_tracker($tracker)
	{
		// update : No client IP : Set it ! 
		if ( empty($tracker['ip_address']) )
		{
			$tracker['ip_address'] = $this->input->ip_address();
			return $this->db->insert($this->tracker_table, $tracker);
		}
		else
		{
			return $this->db->where('ip_address', $this->input->ip_address())
					->update($this->tracker_table, $tracker);
		}
	}

}


/* End of file connect_model.php */
/* Location: ./application/libraries/connect_model.php */