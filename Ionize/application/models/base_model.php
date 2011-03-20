<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.90
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Base Model
 * Extends the Model class and provides basic ionize model functionnalities
 *
 * @package		Ionize
 * @subpackage	Models
 * @category	Base model
 * @author		Ionize Dev Team
 *
 */

class Base_model extends Model 
{
	/*
	 * Stores if this model is already loaded or not.
	 *
	 */ 
	protected static $_inited = false;

	/*
	 * Table name
	 *
	 */
	public $table = ''; 		// Table name

	/*
	 * Table primary key column name
	 *
	 */
	public $pk_name = '';
	
	/*
	 * Lang table of elements
	 * For example, "page" has a corresponding lang table called "page_lang"
	 *
	 */
	public $lang_table 	= '';

	/*
	 * Extended fields definition table
	 * This table contains definition of each extended field
	 *
	 */ 
	public $extend_field_table = 	'extend_field';

	/*
	 * Extended fields intances table.
	 * This table contains all the extended fields data
	 *
	 */
	public $extend_fields_table = 	'extend_fields';

	/*
	 * Extended fields prefix. Needs to be the same as the one defined in /models/base_model
	 *
	 */
	private $extend_field_prefix = 	'ion_';

	/*
	 * Stores if we already got or not the extended fields definition
	 * If we already got them, they don't need to be loaded once more...
	 *
	 */
	protected $got_extend_fields_def = false;
	
	/*
	 * Array of extended fields definition
	 * Contains all the extended fields definition for a type of data.
	 * "page" is a type of data.
	 */
	protected $extend_fields_def = array();
	

	public $limit 	= null;		// Query Limit
	public $offset 	= null;		// Query Offset

	/*
	 * Publish filter
	 * true : the content is filtered on online and published values (default)
	 * false : all content is returned
	 *
	 */
	protected static $publish_filter = true;
	
	/*
	 * Array of table names on which media can be linked
	 *
	 */
	protected $with_media_table = array('page', 'article');

	
	
	// ------------------------------------------------------------------------


	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::Model();

		if(self::$_inited)
		{
			return;
		}
		self::$_inited = true;

		$CI =& get_instance();
		
		// Unlock the publish filter (filter on publish status of each item)
		if (Connect()->is('editors'))
		{
			self::unlock_publish_filter();
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the model table name
	 *
	 */
	public function get_table()
	{
		return $this->table;
	}


	// ------------------------------------------------------------------------

	
	/** 
	 * Get one element
	 *
	 * @param	string		where array
	 * @param	string		Optional. Lang code
	 * @return	array		array of media
	 *
	 */
	function get($where, $lang = NULL) 
	{
		if ( ! is_null($lang))
		{
			$this->db->select('t1.*, t2.*', false);
			$this->db->join($this->lang_table.' t2', 't2.id_'.$this->table.' = t1.id_'.$this->table, 'inner');
			$this->db->where('t2.lang', $lang);		
		}
		else
		{
			$this->db->select('t1.*', false);	
		}
	
		if ( is_array($where) )
		{
			foreach ($where as $key => $value)
			{
				$this->db->where('t1.'.$key, $value);
			}
		}
		else
		{
			$this->db->where('t1.'.$this->pk_name, $where);
		}
		
		$data = array();

		$query = $this->db->get($this->table.' t1');

		if ( $query->num_rows() > 0)
		{
			$data = $query->row_array();
			$query->free_result();
				
			// Add medias to data array
			if (in_array($this->table, $this->with_media_table))
				$this->add_linked_media($data, $this->table, $lang);
			
		}
		
		return $data;
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Get a resultset Where
	 *
	 * @access	public
	 * @param 	array	An associative array
	 * @return	array	Result set
	 *
	 */
	public function get_where($where = null)
	{
		return $this->db->get_where($this->table, $where, $this->limit, $this->offset);
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Get all the records
	 *
	 * @access	public
	 * @return	array	Result set
	 *
	 */
	public function get_all()
	{
		$query = $this->db->get($this->table);
		
		return $query->result();
	}


	// ------------------------------------------------------------------------


	/**
	 * Get one row
	 *
	 * @access	public
	 * @param 	int		The result id
	 * @return	object	A row object
	 *
	 */
	public function get_row($id = NULL)
	{
		$this->db->where($this->pk_name, $id);
		$query = $this->db->get($this->table);
		
		return $query->row();
	}


	// ------------------------------------------------------------------------


	/**
	 * Get one row_array
	 *
	 * @access	public
	 * @param 	int		The result id
	 * @return	object	A row object
	 *
	 */
	public function get_row_array($id = NULL)
	{
		$this->db->where($this->pk_name, $id);
		$query = $this->db->get($this->table);
		
		return $query->row_array();
	}


	// ------------------------------------------------------------------------


	/**
	 * Get array of records
	 *
	 * @access	public
	 * @param 	array		An associative array
	 * @param 	string		order_by field name
	 * @param 	boolean		Limit value
	 * @return	array		Array of records
	 *
	 */
	function get_list($where = false, $orderby = null, $limit=false)
	{
		$data = array();
		
		if ( is_array($where) )
			$this->db->where($where);

		if ( ! is_null($orderby) )
			$this->db->order_by($orderby);

		if ($limit !== false)
			(is_array($limit)) ? $this->db->limit($limit[1], $limit[0]) : $this->db->limit($limit);

		$this->db->select($this->table.'.*');
		
		$query = $this->db->get($this->table);
		
		if ( $query->num_rows() > 0 )
			$data = $query->result_array();

		$query->free_result();
		
		return $data;
	}

	
	// ------------------------------------------------------------------------


	/** 
	 * Get element lang data (from lang table only)
	 *
	 * @param 	string	Element ID
	 * @param	array	Arraylist of all translations rows
	 *  
	 */
	function get_lang($id)
	{
		$data = array();
				
		$this->db->where('id_'.$this->table, $id);
		
		$query = $this->db->get($this->lang_table);

		if ( $query->num_rows() > 0 )
			$data = $query->result_array();
		
		$query->free_result();
		
		return $data;
	}


	// ------------------------------------------------------------------------


	/** Get post list with lang data
	 *  Used by front-end to get the elements list with lang data
	 *
	 *	@param	array	WHERE array
	 *	@param	string	Language code
	 *	@param	number	Limit to x records
	 *	@param	string	complete LIKE String
	 *	
	 *	@return	array	The complete arrayList of element, including medias
	 *
	 */
	function get_lang_list($where=false, $lang=NULL, $limit=false, $like=false)
	{
		$data = array();

		// Make sure we have only one time each element
		$this->db->distinct();

		// Lang data
		if ( ! is_null($lang))
		{
			$this->db->select($this->lang_table.'.*');
			$this->db->join($this->lang_table, $this->lang_table.'.id_'.$this->table.' = ' .$this->table.'.id_'.$this->table, 'inner');			
			$this->db->where($this->lang_table.'.lang', $lang);
		}

		// Main data select						
		$this->db->select($this->table.'.*', false);

		// Limit ?
		if ($limit !== false)
			(is_array($limit)) ? $this->db->limit($limit[1], $limit[0]) : $this->db->limit($limit);

		// Where ?
		if (is_array($where) )
		{
			foreach ($where as $key => $value)
			{
				$protect = true;

				if (substr($key, -2) == 'in')
				{
					$protect = false;
				}
				if (strpos($key, '.') > 0)
				{
					$this->db->where($key, $value, $protect);			
				}
				else
				{
					$this->db->where($this->table.'.'.$key, $value, $protect);
				}
			}
		}
		
		// Like ?
		if ($like)
			$this->db->like($like);
		
		$query = $this->db->get($this->table);

		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$query->free_result();

			// Add linked medias to the "media" index of the data array		
			if (in_array($this->table, $this->with_media_table))
				$this->add_linked_media($data, $this->table, $lang);
					
			// Add extended fields if necessary
			$this->add_extend_fields($data, $this->table, $lang);
			
			// Add URLs for each language
			if ($this->table == 'page' OR $this->table == 'article')
				$this->add_lang_urls($data, $this->table, $lang);
		}

		return $data;
	}


	// ------------------------------------------------------------------------


	/**
	 * Get pages or articles from their lang URL
	 *
	 * @param 	Mixed	ID or array of IDs to exclude for the search
	 *
	 * @returns	Array	Array of elements
	 *
	 */
	function get_from_urls($urls, $excluded_id)
	{
		$data = array();
		
		// Main data select						
		$this->db->select($this->table.'.*', false);
		$this->db->join($this->lang_table, $this->lang_table.'.id_'.$this->table.' = ' .$this->table.'.id_'.$this->table, 'inner');			
		$this->db->where_in($this->lang_table.'.url', $urls);
		
		// Add excluded IDs to the statement
		if ($excluded_id !='' && !is_array($excluded_id))
			$excluded_id = array($excluded_id);

		if ( !empty($excluded_id))
		{
			$this->db->where_not_in($this->lang_table.'.id_'.$this->table, $excluded_id);
		}
		
		
		$query = $this->db->get($this->table);

		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			$query->free_result();
		}		
		
		return $data;
	}

	// ------------------------------------------------------------------------


	protected function get_extend_fields_definition()
	{
		if ($this->got_extend_fields_def == false)
		{
			$this->set_extend_fields_definition($this->table);
		}
		return $this->extend_fields_def;
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the current linked childs items as a simple array from a N:M table
	 *
	 * @param	String		Items table name
	 * @param	String		Parent table name
	 * @param	Integer		Parent ID
	 *
	 * @return	array		items keys simple array
	 *
	 */
	function get_joined_items_keys($items_table, $parent_table, $parent_id)
	{
		$data = array();
		
		// N to N table
		$link_table = $parent_table.'_'.$items_table;
		
		// Items table primary key detection
		$fields = $this->db->list_fields($items_table);
		$items_table_pk = $fields[0];
		
		// Parent table primary key detection
		$fields = $this->db->list_fields($parent_table);
		$parent_table_pk = $fields[0];
		
		$this->db->where($parent_table_pk, $parent_id);
		$this->db->select($items_table_pk);
		$query = $this->db->get($link_table);

		foreach($query->result() as $row)
		{
			$data[] = $row->$items_table_pk;
		}
		
		return $data;
	}


	// ------------------------------------------------------------------------


	/**
	 * Gets items key and value as an associative array
	 *
	 * @param	string	Elements table name
	 * @param	string	Value field name
	 * @param	string	Zero value name. Usefull when feeding a selectbox
	 * @param	string	Orderby SQL string 
	 *
	 */
	function get_items_select($items_table, $value, $nothing_value = NULL, $order_by = NULL)
	{
		$data = array();
		
		// Add the Zero value item
		if ( ! is_null($nothing_value))
			$data = array('0' => $nothing_value);

		// Items table primary key detection
		$fields = $this->db->list_fields($items_table);
		$items_table_pk = $fields[0];

		// ORDER BY
		if ( ! is_null($order_by))
			$this->db->order_by($order_by);

		// Query
		$query = $this->db->get($items_table);

		foreach($query->result() as $row)
		{
			$data[$row->$items_table_pk] = $row->$value;
		}
		
		return $data;
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Sets the current table
	 *
	 * @param string	table name
	 *
	 */
	public function set_table($table)
	{
		$this->table = $table;
	}


	// ------------------------------------------------------------------------


	/**
	 * Sets the current table pk
	 *
	 * @param string	table pk name
	 *
	 */
	public function set_pk_name($pk_name)
	{
		$this->pk_name = $pk_name;
	}


	// ------------------------------------------------------------------------


	/**
	 * Sets the current lang table name
	 *
	 * @param string	lang table name
	 *
	 */
	public function set_lang_table($table)
	{
		$this->lang_table = $table;
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the extend fields definition and store them in the private property "extend_fields_def"
	 *
	 * @param	String	Parent type
	 * @return	Array	Extend fields definition array
	 *
	 */
	protected function set_extend_fields_definition($parent)
	{
		$CI =& get_instance();

		// Loads the model if it isn't loaded
		if (!isset($CI->extend_field_model))
			$CI->load->model('extend_field_model');
			
		// Get the extend fields definition if not already got
		if ($this->got_extend_fields_def == false)
		{
			// Store the extend fields definition
			$this->extend_fields_def = $CI->extend_field_model->get_list(array('parent' => $parent));
			
			// Set this to true so we don't get the extend field def a second time for an object of same kind
			$this->got_extend_fields_def = true;
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Save one element, including lang depending data
	 *
	 * @param 	array	Standard data table
	 * @param 	array	Lang depending data table. optional.
	 *
	 * @return 	int		Saved element ID
	 *
	 */
	function save($data, $dataLang = false) 
	{
		/*
		 * Base data save
		 */
	 
		// Insert
		if( ! isset($data[$this->pk_name]) || $data[$this->pk_name] == '' )
		{
			// Remove the ID so the generated SQL will be clean (no empty String insert in the table PK field)
			unset($data[$this->pk_name]);
			
			$this->db->insert($this->table, $data);
			$id = $this->db->insert_id();
		}
		// Update
		else
		{
			$this->db->where($this->pk_name, $data[$this->pk_name]);
			$this->db->update($this->table, $data);
			$id = $data[$this->pk_name];
		}

		/*
		 * Lang data save
		 */
		if ( ($dataLang !== false) && ( !empty($dataLang) ) )
		{
			foreach(Settings::get_languages() as $language)
			{
				foreach($dataLang as $lang => $data)
				{
					if($lang == $language['lang'])
					{
						$where = array(
									$this->pk_name => $id,
									'lang' => $lang
								  );
	
						// Update
						if( $this->exists($where, $this->lang_table))
						{
							$this->db->where($where);
							$this->db->update($this->lang_table, $data);
						}
						// Insert
						else
						{
							// Correct lang & pk field on lang data array
							$data['lang'] = $lang;
							$data[$this->pk_name] = $id;
							
							$this->db->insert($this->lang_table, $data);
						}
					}
				}
			}
		}
		return $id;
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves ordering for items in the current table or in the join table, depending on parent var.
	 *
	 * @param	mixed	String of coma separated new order or array of order
	 * @return	string	Coma separated order
	 *
	 */
	function save_ordering($ordering, $parent = false, $id_parent = false)
	{
		if ( ! is_array($ordering))
		{
			$ordering = explode(',', $ordering);
		}
		$new_order = '';
		$i = 1;
		
		while (list ($rank, $id) = each ($ordering))	
		{
			$this->db->where($this->pk_name, $id);
			$this->db->set('ordering', $i++);
			
			// If parent table is defined, save ordering in the join table
			if ($parent !== false)
			{
				$this->db->update($parent.'_'.$this->table);
			}
			else
			{
				$this->db->update($this->table);
			}
					
			$new_order .= $id.",";
		}
		
		return substr($new_order, 0, -1);
	}


	// ------------------------------------------------------------------------


	/**
	 * Add all media for one element to an array and returns this array
	 *
	 * @param	array	By ref. The array to add the media datas
	 * @param	string	parent name. Example : 'page', 'article', etc.
	 * @param	string	Lang code
	 *
	 */
	protected function add_linked_media(&$data, $parent, $lang = NULL)
	{
		// Select medias
		$this->db->select('*, media.id_media');
		$this->db->from('media,'. $parent .'_media');
		$this->db->where('media.id_media', $parent.'_media.id_media', false);
		$this->db->orderby($parent.'_media.ordering');

		if ( ! is_null($lang))
		{
			$this->db->join('media_lang', 'media.id_media = media_lang.id_media', 'left outer');
			$this->db->where('(media_lang.lang =\'', $lang.'\' OR media_lang.lang is null )', false);
		}
		
		$query = $this->db->get();

		$result = array();

		// Feed each media array
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}			

		// If the data array is a list of arrays
		if (isset($data[0]) && is_array($data[0]))
		{
			foreach($data as $k=>$el)
			{
				$data[$k]['medias'] = array_values(array_filter($result, create_function('$row','return $row["id_'.$this->table.'"] == "'. $el['id_'.$this->table] .'";')));
				
				// Add extended fields values for each media
				// Needs to be improved as the extend fieldsdefinition loaded in $this->extend_fields_def are these from the table and not from the medias...
				// But this has no importance, it's just not clean.
				if (Settings::get('use_extend_fields') == '1' && !empty($data[$k]['medias']))
				{
					$this->add_extend_fields($data[$k]['medias'], 'media', $lang);
				}
			}
		}
		// The data array is a hashtable
		else
		{
			$data['medias'] = array_values(array_filter($result, create_function('$row','return $row["id_'.$this->table.'"] == "'. $data['id_'.$this->table] .'";')));

			if (Settings::get('use_extend_fields') == '1' && !empty($data['medias']))
			{
				$this->add_extend_fields($data['medias'], 'media', $lang);
			}
		}
		
		$query->free_result();
	}


	// ------------------------------------------------------------------------


	/**
	 * Adds to each element (page or article) the "urls" field, containing the URL for each language code
	 *
	 * @param	array	By ref. The array to add the urls datas
	 * @param	string	parent name. Example : 'page', 'article', etc.
	 */
	protected function add_lang_urls(&$data, $parent)
	{
		// Select medias
		$this->db->select('id_'.$this->table.','.$this->table.'_lang.lang'.','.$this->table.'_lang.url');
		$this->db->from($this->table .'_lang');
	
		$query = $this->db->get();

		$result = array();

		// Feed each media array
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}			
		
		// If the data array is a list of arrays
		$languages = Settings::get_languages();
		
		if (isset($data[0]) && is_array($data[0]))
		{
			foreach($data as $k=>$el)
			{
				foreach($languages as $language)
				{
					$url = array_values(array_filter($result, create_function('$row','return ($row["id_'.$this->table.'"] == "'. $el['id_'.$this->table] .'" && $row["lang"] == "'.$language['lang'].'");')));
					$url = (!empty($url[0])) ? $url[0]['url'] : '';
					$data[$k]['urls'][$language['lang']] = $url;
				}
			}
		}
	
	}


	// ------------------------------------------------------------------------


	/**
	 * Add extended fields and their values if website settings allow it.
	 * 
	 * @param	Array	Data array. By ref.
	 * @param	String	Parent type. can be "page", "article", etc.
	 * @param	String	Lang code
	 *
	 */
	protected function add_extend_fields(&$data, $parent, $lang = NULL)
	{	
		// Check the website settings regarding the extend fields
		if (Settings::get('use_extend_fields') == '1')
		{
			// get the extend fields definition array
			$this->set_extend_fields_definition($this->table);
			
			// Get the elements ID to filter the SQL on...
			$ids = array();
			
			foreach ($data as $d)
			{
				$ids[] = $d['id_'.$parent];
			}
			
			// Get the extend fields details, filtered on parents ID
			$this->db->where(array('parent'=>$parent));
			$this->db->where_in($ids);
			$this->db->join($this->extend_fields_table, $this->extend_field_table.'.id_'.$this->extend_field_table.' = ' .$this->extend_fields_table.'.id_'.$this->extend_field_table, 'inner');			

			$query = $this->db->get($this->extend_field_table);

			$result = array();
			if ( $query->num_rows() > 0)
				$result = $query->result_array();
			
			// Filter the result by lang : Only returns the not translated data and the given language tranlated data
			$result = array_filter($result,  create_function('$row','return ($row["lang"] == "'. $lang .'" || $row["lang"] == "" );'));


			// Attach each extra field to the corresponding data array
			foreach ($data as &$d)
			{
				// Store the extend definition array
				// Not usefull for the moment.
				// Can be used for debugging
				// $d['_extend_fields_definition'] = $this->get_extend_fields_definition();
				
				// First set the extended fields of the data row to an empty value
				// So it exists...
				foreach ($this->extend_fields_def as $e)
				{
					$d[$this->extend_field_prefix.$e['name']] = '';
				}
				
				// Feeds the extended fields
				// Each extended field will be prefixed to avoid collision with standard fields names
				foreach ($result as $e)
				{	
					if ($d['id_'.$parent] == $e['id_parent'])
					{
						$d[$this->extend_field_prefix.$e['name']] = $e['content'];
					}
				}
			}			
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Join multiple items keys to a parent through a N:M table
	 *
	 * Items are consired as 'childs' and will be attached to a 'parent' through the join table.
	 * That means before saving, all rows with the 'parent ID' key will be deleted in the join table.
	 *
	 * Note: 	When attaching 'categories' to an 'article', the category array will be considered as 'child'
	 *			and the article as 'parent'.
	 *			That means the join table MUST be named 'parent_child'.
	 *			Example : ARTICLE_CATEGORY is the join table between articles and categories
	 *			In that case, the tables ARTICLE and the table CATEGORY MUST exist
	 *
	 * @param	string		items table name
	 * @param	array		items to save. Simple array of keys.
	 * @param	string		parent table name.
	 * @param	int			parent ID
	 *
	 * @return	int		number of attached items
	 *
	 */
	function join_items_keys_to($items_table, $items, $parent_table, $parent_id)
	{
		// N to N table
		$link_table = $parent_table.'_'.$items_table;
		
		// Items table primary key detection
		$fields = $this->db->list_fields($items_table);
		$items_table_pk = $fields[0];
		
		// Parent table primary key detection
		$fields = $this->db->list_fields($parent_table);
		$parent_table_pk = $fields[0];
		
		// Delete existing link between items table and parent table
		$this->db->where($parent_table_pk, $parent_id);
		$this->db->delete($link_table);

		// nb inserted items
		$nb = 0;
		
		// Insert 
		if ( !empty($items) )
		{
			foreach($items as $item)
			{
				if($item != 0 && $item !== false)
				{
					$data = array(
					   $parent_table_pk => $parent_id,
					   $items_table_pk => $item
					);

					$this->db->insert($link_table, $data);
					$nb += 1;
				}
			}
		}
		
		return $nb;
	}


	// ------------------------------------------------------------------------


	/**
	 * Deletes one join row between an item and its parent
	 *
	 * @param	string		items table name
	 * @param	int			item ID to delete
	 * @param	string		parent table name.
	 * @param	int			parent ID
	 *
	 * @return	int			number of affected rows
	 *
	 */
	function delete_joined_key($items_table, $item_key, $parent_table, $parent_id)
	{
		// N to N table
		$link_table = $parent_table.'_'.$items_table;
		
		// Items table primary key detection
		$fields = $this->db->list_fields($items_table);
		$items_table_pk = $fields[0];
		
		// Parent table primary key detection
		$fields = $this->db->list_fields($parent_table);
		$parent_table_pk = $fields[0];

		$this->db->where($parent_table_pk, $parent_id);
		$this->db->where($items_table_pk, $item_key);

		return (int) $this->db->delete($link_table);
	}


	// ------------------------------------------------------------------------


	/**
	 * Set an item online / offline dependaing on its current status
	 *
	 * @param	int			item ID
	 *
	 * @return 	boolean		New status
	 *
	 */
	function switch_online($id)
	{
		// Current status
		$status = $this->get_row($id)->online;
	
		// New status
		($status == 1) ? $status = 0 : $status = 1;

		// Save		
		$this->db->where($this->pk_name, $id);
		$this->db->set('online', $status);
		$this->db->update($this->table);
		
		return $status;
	}


	// ------------------------------------------------------------------------


	/**
	 * Feed the template array with data for each field in the table
	 *
	 * @param	int		ID of the search element
	 * @param	array	By ref, the template array
	 *
	 */
	function feed_template($id, &$template)
	{
		$data = $this->get($id);

		foreach($data as $key=>$val)
		{
			$template[$key] = $val;
		}

	}


	// ------------------------------------------------------------------------


	/**
	 * Feed the template array with data for each field in language table
	 *
	 * @param	array	By ref, the template array
	 *
	 */
	function feed_lang_template($id, &$template)
	{
		// lang_table fields
		$fields = NULL;
		$rows = $this->get_lang($id);

		foreach(Settings::get_languages() as $language)
		{
			$lang = $language['lang'];
		
			// Feeding of template languages elements
			foreach($rows as $row)
			{
				if($row['lang'] == $lang)
				{
					$template[$lang] = $row;
				}
			}
			
			// Language not defined : Feed with blank data
			if( ! isset($template[$lang]))
			{
				// Get lang_table fields if we don't already have them
				if (is_null($fields))
					$fields = $this->db->list_fields($this->lang_table);

				foreach ($fields as $field)
				{
					if ($field != $this->pk_name)
						$template[$lang][$field] = '';
					else
						$template[$lang][$this->pk_name] = $id;
				}
			}
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Feeds the template array with blank data for each field in the table
	 *
	 * @param	array	By ref, the template array
	 *
	 */
	function feed_blank_template(&$template)
	{
		$fields = $this->db->list_fields($this->table);

		$fields_data = $this->field_data($this->table);

		foreach ($fields as $field)
		{
			$field_data = array_values(array_filter($fields_data, create_function('$row', 'return $row["Field"] == "'. $field .'";')));
			$field_data = (isset($field_data[0])) ? $field_data[0] : false;

			$template[$field] = (isset($field_data['Default'])) ? $field_data['Default'] : '';
		}

	}


	// ------------------------------------------------------------------------


	/**
	 * Feed the template array with blank data for each field in language table
	 *
	 * @param	array	By ref, the template array
	 *
	 */
	function feed_blank_lang_template(&$template)
	{
		$fields = $this->db->list_fields($this->lang_table);

		$fields_data = $this->field_data($this->lang_table);
					
		foreach(Settings::get_languages() as $language)
		{
			$lang = $language['lang'];
			
			foreach ($fields as $field)
			{
				$field_data = array_values(array_filter($fields_data, create_function('$row', 'return $row["Field"] == "'. $field .'";')));
				$field_data = (isset($field_data[0])) ? $field_data[0] : false;
				
				$template[$lang][$field] = (isset($field_data['Default'])) ? $field_data['Default'] : '';
			}
		}
	}


	// ------------------------------------------------------------------------


	/** 
	 * Switch the publish filter off
	 * 
	 */
	public function unlock_publish_filter()
	{
		self::$publish_filter = false;
	}


	// ------------------------------------------------------------------------


	/**
	 * Insert a row
	 *
	 * @access	public
	 * @param 	array	An associative array of data
	 * @return	the last inserted id
	 *
	 */
	public function insert($data = null)
	{
		$this->db->insert($this->table, $data);
		
		return $this->db->insert_id();
	}
	

	// ------------------------------------------------------------------------

	
	/**
	 * Update a row
	 *
	 * @access	public
	 * @param 	int		The result id
	 * @param 	array	An associative array of data
	 * @return	int		Number of updated rows
	 *
	 */
	public function update($id = NULL, $data = NULL)
	{
		$this->db->where($this->pk_name, $id);
		$this->db->update($this->table, $data);
		
		return (int) $this->db->affected_rows();
	}

	
	// ------------------------------------------------------------------------

	
	/**
	 * Delete a row
	 *
	 * @access	public
	 * @param 	int		The result id
	 * @return	int		Number of deleted rows
	 *
	 */
	public function delete($id = NULL)
	{
		$this->db->where($this->pk_name, $id);
		$this->db->delete($this->table);
		
		return (int) $this->db->affected_rows();
	}

	
	// ------------------------------------------------------------------------

	
	/**
	 * Count all rows in a table or count all results from the current query
	 *
	 * @access	public
	 * @param	bool	true / false
	 * @return	int 	The number of all results
	 *
	 */
	public function count_all($results = false)
	{
		if($results !== false)
		{
			$query = $this->db->count_all_results($this->table);
		}
		else
		{
			$query = $this->db->count_all($this->table);
		}
		
		return (int) $query;
	}

	
	// ------------------------------------------------------------------------

	
	/**
	 * Empty table
	 *
	 * @access	public
	 * @return	void
	 *
	 */
	public function empty_table()
	{
		$this->db->empty_table($this->table);
	}

	
	// ------------------------------------------------------------------------

	
	/**
	 * Check if a record exists in a table
	 *
	 * @access	public
	 * @return	boolean
	 *
	 */
	public function exists($where = NULL, $table = NULL)
	{
		$table = ( ! is_null($table)) ? $table : $this->table ;
		
		$query = $this->db->get_where($table, $where);

		if ($query->num_rows() > 0) 
			return true; 
		else
			return false;
	}
		
	
	// ------------------------------------------------------------------------


	/**
	 * Returns the table fields array list
	 *
	 * @param	String		Table name
	 * @return	Array		Array of field names
	 *
	 */
	function field_data($table)
	{
		$query = $this->db->query("SHOW COLUMNS FROM " . $table);
	
		return $query->result_array();
	}
	
	
	// ------------------------------------------------------------------------

	
	/**
	 * DEPRECATED : Replaced by article_model->update_links(), more efficient
	 * 
	 * Correct a page or article link name if needed
	 * @param		Array	Page Array
	 * @param		String	The link name as stored in the current article
	 *
	 * @returns		Array	The corrected link array
	 *
	function correct_link_name($data, $table)
	{
		$correct = FALSE;

		if ($data['link_type'] != '' )
		{
			$db_data = $this->get($data['link_id']);
			
			$this->db->where('id_'.$data['link_type'], $data['link_id']);
			$query = $this->db->get($data['link_type']);

			if ( $query->num_rows() > 0)
			{
				$db_data = $query->row_array();
				$query->free_result();
				
				/* Correct the DB if needed
				 * This correction is only made to keep coherence in data.
				 * If the link name changed and the page wich has the link is not edited, 
				 * this doesn't matter because internal links are ID based and not name based.
				 *
				 *
				if ($data['link'] != $db_data['name'])
				{
					$data['link'] = $db_data['name'];
					
					$this->db->set(array('link' => $data['link']));
					$this->db->where('id_'.$table, $data['id_'.$table]);
					$this->db->update($table);
				}
			}
		}
		
		return $data['link'];
	}
	 */

}


/* End of file base.php */
/* Location: ./application/models/base.php */