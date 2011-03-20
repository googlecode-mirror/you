<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends Base_model 
{

	/**
	 * Constructor
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();

		$this->table =		'category';
		$this->pk_name 	=	'id_category';
		$this->lang_table = 'category_lang';
	}


	// ------------------------------------------------------------------------


	/** 
	 * Gets category list as array (id => name)
	 * 
	 */
	function get_categories_select()
	{
		return $this->get_items_select($this->table, 'name', lang('ionize_select_no_category'), 'ordering ASC');
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the current categories from parent element
	 *
	 * @param	string	parent name
	 * @param	int		parent ID
	 *
	 */
	function get_current_categories($parent, $parent_id)
	{
		return $this->get_joined_items_keys($this->table, $parent, $parent_id);
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Get the categories from pages regarding articles in these categories.
	 * If no articles are attached to one category, this category is not returned
	 *
	 * @param	int		Parent page ID
	 * @param	string	Current lang code
	 *
	 * @return	array	Array of categories
	 *
	 */
	function get_categories_from_pages($id_page, $lang)
	{
		$sql = 'select * from category_lang, category 
				where category_lang.id_category = category.id_category
				and category_lang.lang=\''. $lang .'\'
				and category.id_category in (
					select id_category
					from article_category
					where id_article in (
						select article.id_article from article
						join article_lang on article.id_article = article_lang.id_article
						join article_category on article.id_article = article_category.id_article
						where article.id_page=' . $id_page . '
						and article_lang.lang = \''. $lang .'\'';
		
		
		// Add the publish filter
		$sql .= $this->filter_on_published(self::$publish_filter, $lang);

		$sql .= 		' 
					)
				)';

		$data = array();

		$query = $this->db->query($sql);
		
		if ( $query->num_rows() > 0 )
			$data = $query->result_array();

		return $data;
	
	}
	

	// ------------------------------------------------------------------------


	/** 
	 * Filters the articles on published one
	 *
	 */
	protected function filter_on_published($on = true, $lang = NULL)
	{
		$sql = '';
		
		if ($on === true)
		{
			$sql .= ' 
					and article.online = \'1\'
					and  article_lang.online = \'1\'
					and ( 
						( article.publish_off > now() or article.publish_off = 0 )
						and (article.publish_on < now() or article.publish_on = 0 )
					)
			';					
		}
		return $sql;
	}
	
	
}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */