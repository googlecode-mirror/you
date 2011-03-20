<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Ionize Media Model
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	Media management
 * @author		Ionize Dev Team
 *
 */

class Media_model extends Base_model 
{


	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->table =		'media';
		$this->pk_name = 	'id_media';
		$this->lang_table = 'media_lang';
	}


	// ------------------------------------------------------------------------


	/** 
	 * Get media list for one defined parent
	 *
	 * @param	string	Media type. Can be 'picture', 'music', 'video', 'file'
	 * @param	string	parent. Example : 'article', 'page'
	 * @param	string	Parent ID
	 *
	 */
	public function get_list($parent, $id_parent, $type=NULL)
	{	
		$data = array();
	
		// $this->db->where('id_'.$parent, $id_parent);
		$this->db->order_by('ordering', 'ASC');
		
		$this->db->select($this->table.'.*', false);
		$this->db->where($parent.'_'.$this->table.'.id_'.$parent, $id_parent);

		if ( ! is_null($type))
			$this->db->where($this->table.'.type', $type);

		$this->db->join($parent.'_'.$this->table, $this->table.'.id_'.$this->table.'='.$parent.'_'.$this->table.'.id_'.$this->table);

		$this->db->select($parent.'_'.$this->table.'.ordering');

		$query = $this->db->get($this->table);

		if($query->num_rows() > 0)
			$data = $query->result_array();
		
		return $data;
	}


	// ------------------------------------------------------------------------


	/** 
	 * Inserts / Update a media into the media table.
	 * Updates the media if the media complete path already exists
	 * 
	 * @param	string	Medium type. Can be 'picture', 'music', 'video', 'file'
	 * @param	string	Complete path to the medium, including file name.
	 * @return	boolean	true if succeed, false if errors
	 *
	 */
	function insert_media($type, $path)
	{
		if ($path) {

			// If no '/' in the path...
			if(strpos($path, '/') === false) 
			{
				$file_name =  $path;
				$base_path = '';
			}
			else 
			{
				$file_name = substr( strrchr($path, '/') ,1 );
				$base_path = str_replace($file_name, '', $path);
			}

			$data['type'] = 	 $type;
			$data['path'] = 	 $path;
			$data['file_name'] = $file_name;
			$data['base_path'] = $base_path;
			
			// Update if exists
			$query = $this->get_where(array('path'=>$path));
			if( $query->num_rows() > 0)
			{
				$medium = $query->row_array();
				$this->db->where('path', $path);
				$this->db->update($this->table, $data);
				$id = $medium['id_media'];
			}
			// Insert
			else
			{
				$this->db->insert($this->table, $data);
				$id = $this->db->insert_id();
			}
			return $id;
		}
		return false;
	}


	// ------------------------------------------------------------------------


	/**
	 * Attach one medium to a parent
	 *
	 * @param	string	Media type. Can be 'picture', 'video', etc.
	 * @param	string	parent. Example : 'article', 'page'
	 * @param	string	Parent ID
	 * @param	string	Medium ID
	 * @return	boolean	true if success, false if error
	 *
	 */
	public function attach_media($type, $parent, $id_parent, $id_media)
	{
		// Get the media ordering value, regarding to the type
		if ($this->db->field_exists('ordering', $parent.'_media'))
		{
			$this->db->select_max('ordering');
			$this->db->join('media', 'media.id_media = '.$parent.'_media.id_media');
			$this->db->where('id_'.$parent, $id_parent);
			$this->db->where('media.type', $type);

			$query = $this->db->get($parent.'_media');

			if ($query->num_rows() > 0)
			{	
				$row =		$query->row();
				$ordering =	$row->ordering;
			}
			else 
			{
				$ordering = 0;
			}
			$this->db->set('ordering', $ordering += 1);
		}
		
		$this->db->where('id_media', $id_media);
		$this->db->where('id_'.$parent, $id_parent);

		$query = $this->db->get($parent.'_media');

		if ($query->num_rows() == 0) {

			$this->db->set('id_media', $id_media);
			$this->db->set('id_'.$parent, $id_parent);

			$this->db->insert($parent.'_media');

			return true;
		}
		return false;
	}

	
	// ------------------------------------------------------------------------	


	/**
	 * Detach all media from a parent depending on the type
	 * If no type, all media attached to this parent will be deleted
	 *
	 * @param 	string	parent type. Ex 'page', 'article'
	 * @param	string	parent ID
	 * @param	string	media type. Optional.
	 *
	 */
	function detach_media_by_type($parent, $id_parent, $type = false)
	{
		/* INNER JOIN on delete is not possible with CI Active Record.
		 * So this request needs to be handly written
		 */
		$sql = 	' DELETE first from ' . $parent . '_media AS first';
		
		if ($type)
		{
			$sql .= ' INNER JOIN ' . $this->table . ' AS second WHERE first.id_media = second.id_media ';
			$sql .= ' AND second.type = \'' . $type . '\'';
			$sql .= ' AND first.id_'.$parent.' = '.$id_parent;
		}
		else
		{
			$sql .= ' WHERE first.id_' . $parent . ' = ' . $id_parent;
		}
		
		$this->db->query($sql);
		
		return (int) $this->db->affected_rows();		
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves one media data
	 *
	 * @param	array	standard data array
	 * @param	array	lang data array
	 *
	 * @return	string	Inserted / Updated media ID
	 */
	function save($data, $lang_data)
	{
		// Dates
		$data['date'] = ($data['date']) ? getMysqlDatetime($data['date']) : '0000-00-00';
		
		// Media saving
		return parent::save($data, $lang_data);
	}	
}


/* End of file media_model.php */
/* Location: ./application/models/media_model.php */