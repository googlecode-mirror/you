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
 * Ionize, creative CMS Article Controller
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Article
 * @author		Ionize Dev Team
 *
 */

class Article extends MY_admin 
{

	/**
	 * Fields on wich the htmlspecialchars function will not be used before saving
	 * 
	 * @var array
	 */
	protected $no_htmlspecialchars = array('content');


	/**
	 * Fields on wich no XSS filtering is done
	 * 
	 * @var array
	 */
	protected $no_xss_filter = array('content');


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('menu_model', '', true);
		$this->load->model('page_model', '', true);
		$this->load->model('article_model', '', true);
		$this->load->model('structure_model', '', true);
		$this->load->model('category_model', '', true);
		$this->load->model('article_type_model', '', true);
		$this->load->model('tag_model', '', true);
		if (Settings::get('use_extend_fields') == '1')
		{
			$this->load->model('extend_field_model', '', true);
		}
		
		$this->load->library('structure');
	}


	// ------------------------------------------------------------------------


	/**
	 * Default : Do nothing
	 *
	 */
	function index()
	{
		return;
	}
	

	// ------------------------------------------------------------------------


	/** 
	 * Create one article
	 * @TODO	Developp the "existing tags" functionality
	 
	 * @param	string 	page ID. Article parent.
	 *
	 */
	function create($id_page) 
	{
		// Check if page exists
		$page = $this->page_model->get($id_page);

		// Page exists : Article can be created
		if ( !empty($page) )
		{
			// Create blank data for this article
			$this->article_model->feed_blank_template($this->template);
			$this->article_model->feed_blank_lang_template($this->template);

			// Put the page ID to the template
			$this->template['id_page'] = $id_page;

			// Tags : Default no one
			$this->template['tags'] = '';
			
			// Existing Tags in all other articles
// Has to be checked
			$this->template['existing_tags'] = $this->tag_model->get_list();
			
			// All other pages articles
			$this->template['articles'] = $this->article_model->get_list(array('id_page'=>$id_page), 'article.ordering ASC');
			
			// Dropdown menus
			$datas = $this->menu_model->get_select();
			$this->template['menus'] =	form_dropdown('id_menu', $datas, $page['id_menu'], 'id="id_menu" class="select"');

			// Dropdown parents
			$datas = $this->page_model->get_lang_list(array('id_menu' => $page['id_menu']), Settings::get_lang('default'));
			$parents = array();
			($parents_array = $this->structure->get_parent_select($datas) ) ? $parents += $parents_array : '';
			$this->template['parent_select'] = form_dropdown('id_page', $parents, $page['id_page'], 'id="id_page" class="select"');
		
			// Dropdown articles views
			if (is_file(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php'))
				require_once(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php');

			$datas = isset($views['article']) ? $views['article'] : array() ;

			if(count($datas) > 0)
			{
				$datas = array('0' => lang('ionize_select_default_view')) + $datas; 
				$this->template['article_views'] = form_dropdown('view', $datas, false, 'class="select w160"');
			}

			// Categories
			$categories = $this->category_model->get_categories_select();
			$this->template['categories'] =	form_dropdown('categories[]', $categories, false, 'class="select" multiple="multiple"');


			// Article types
			$types = $this->article_type_model->get_types_select();
			$this->template['article_types'] =	form_dropdown('id_type', $types, false, 'class="select"');
			
			/*
			 * Extends fields
			 *
			 */
			$this->template['extend_fields'] = array();
			if (Settings::get('use_extend_fields') == '1')
			{
				$this->template['extend_fields'] = $this->extend_field_model->get_element_extend_fields('article');
			}

			$this->output('article');
		}
	}	
	

	// ------------------------------------------------------------------------

	
	/**
	 * Saves one article
	 *
	 * @param	boolean		if true, the transport is through XHR
	 */
	function save()
	{
		/* Check if the default lang URL or the default lang title are set
		 * One of these need to be set to save the article
		 *
		 */
		if ($this->_check_before_save() == TRUE)
		{

			$id = $this->input->post('id_article');
			
			// try to get the page with one of the form provided URL
			$urls = array_values($this->_get_urls());

			$articles = $this->article_model->get_from_urls($urls, $exclude = $id);

			// If no article ID (means new one) and this article URL already exists in DB : No save 
			if ( !empty($articles) )
			{
				$this->error(lang('ionize_message_article_url_exists'));
			}
			// else, save...
			else
			{
				// Prepare data before saving
				$this->_prepare_data();
	
				// Saves article to DB
				$this->id = $this->article_model->save($this->data, $this->lang_data);
				
				// Correct DB integrity
				if ( ! empty($id) )
					$this->article_model->correct_integrity($this->data, $this->lang_data);

				// Saves linked categories
				$this->base_model->join_items_keys_to('category', $this->input->post('categories'), 'article', $this->id);

				// Saves tags
				$this->tag_model->save_tags($this->input->post('tags'), 'article', $this->id);

				// Save extend fields data
				if (Settings::get('use_extend_fields') == '1')
					$this->extend_field_model->save_data('article', $this->id, $_POST);

				
				/* Update the content structure tree
				 * The data var is merged to the default lang data_lang var,
				 * in order to send the lang values to the browser without making another SQL request
				 */
				$menu = $this->menu_model->get_from_page($this->data['id_page']);
				
				$this->data = array_merge($this->lang_data[Settings::get_lang('default')], $this->data);
				$this->data['title'] = htmlspecialchars_decode($this->data['title'], ENT_QUOTES);
				$this->data['id_article'] = $this->id;
				$this->data['element'] = 'article';
				$this->data['menu'] = $menu;
				
				// Insert Case
				if ( empty($id) )
				{
					$this->update[] = array(
						'element' => 'mainPanel',
						'url' => site_url('admin/article/edit/'.$this->id),
						'title' => lang('ionize_title_edit_article')
					);
					$this->callback = array(
						'fn' => $menu['name'].'Tree.insertTreeArticle',
						'args' => $this->data
					);
				}
				// Update case
				else
				{
					$this->callback = array(
						'fn' => $menu['name'].'Tree.updateTreeNode',
						'args' => $this->data
					);
				}				
				$this->success(lang('ionize_message_article_saved'));
			}
		}
		else
		{
			$this->error(lang('ionize_message_article_needs_url_or_title'));
		}

	}


	// ------------------------------------------------------------------------


	/** 
	 * Edit one article
	 *
	 * @updated 	13.09.2009
	 *
	 * @param	string		article ID
	 *
	 *
	 */
	function edit($id)
	{
		// Base article datas
		$article = $this->article_model->get($id);

		if( ! empty($article) ) {

			$this->template = array_merge($this->template, $article);
						
			// Parent page data
			$page = $this->page_model->get_lang_list(array('id_page' => $this->template['id_page']));
			
			if ( empty($page))
			{
				$page = array(
					'id_menu' => '1',
					'id_page' => '0'
				);
			}
			else
			{
				$page = $page[0];
			}
			$this->template['page'] = $page;

			// Array of path to the element. Gives the complete URL to the element.
//			$this->template['parent_array'] = $this->page_model->get_parent_array($id);

			// Dropdown menus
			$datas = $this->menu_model->get_select();
			$this->template['menus'] =	form_dropdown('id_menu', $datas, $page['id_menu'], 'id="id_menu" class="select"');

			// Dropdown parents
			$datas = $this->page_model->get_lang_list(array('id_menu' => $page['id_menu']), Settings::get_lang('default'));
			$parents = array();
			($parents_array = $this->structure->get_parent_select($datas) ) ? $parents += $parents_array : '';
			$this->template['parent_select'] = form_dropdown('id_page', $parents, $this->template['id_page'], 'id="id_page" class="select"');


			// Dropdown article views (templates)
			if (is_file(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php'))
				require_once(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php');

			$datas = isset($views['article']) ? $views['article'] : array() ;
			if(count($datas) > 0)
			{
				$datas = array('0' => lang('ionize_select_default_view')) + $datas; 
				$this->template['article_views'] = form_dropdown('view', $datas, $this->template['view'], 'class="select"');
			}

			// All other articles from this page
			$this->template['articles'] = $this->article_model->get_list(array('id_page'=>$this->template['id_page']), 'article.ordering ASC');


			/*
			 * Categories
			 */
			 
			// Get all categories list in order to feed the select box
			$categories = $this->category_model->get_categories_select();
			
			// Current article categories
			$current_categories = $this->category_model->get_current_categories('article', $id);
			
			// Categories select box
			$this->template['categories'] =	form_dropdown('categories[]', $categories, $current_categories, 'class="select" multiple="multiple"');

			/* 
			 * Articles Types
			 */
			$types = $this->article_type_model->get_types_select();
			$this->template['article_types'] =	form_dropdown('id_type', $types, $article['id_type'], 'class="select"');


			/*
			 * Tags
			 */
			// Tags from this parent
			$this->template['tags'] =	$this->tag_model->get_tags_from_parent('article', $id, 'string');
			
			// Existing tags
			$this->template['existing_tags'] =	$this->tag_model->get_tags('string');
			
			/*
			 * extend fields
			 *
			 */
			$this->template['extend_fields'] = array();
			if (Settings::get('use_extend_fields') == '1')
			{
				$this->template['extend_fields'] = $this->extend_field_model->get_element_extend_fields('article', $id);
			}

			/*
			 * Lang depending data
			 */
			$this->article_model->feed_lang_template($id, $this->template);


			$this->output('article');
		}
		else
		{
			$this->error(lang('ionize_message_article_not_exist'));
		}
	}


	// ------------------------------------------------------------------------


	function duplicate($id, $id_page, $name)
	{
		// Source article
		$source = $this->article_model->get($id, Settings::get_lang('default'));
		$page = $this->page_model->get($id_page);
		
		$this->template['id_article'] = $id;
		$this->template['id_page'] = $id_page;
		$this->template['name'] = $name;
	
		
		// Dropdown menus
		$datas = $this->menu_model->get_select();
		$this->template['menus'] =	form_dropdown('dup_id_menu', $datas, $page['id_menu'], 'id="dup_id_menu" class="select"');

		// Dropdown parents
		$datas = $this->page_model->get_lang_list(array('id_menu' => $page['id_menu']), Settings::get_lang('default'));
		$parents = array();
		($parents_array = $this->structure->get_parent_select($datas) ) ? $parents += $parents_array : '';
		$this->template['parent_select'] = form_dropdown('dup_id_page', $parents, $id_page, 'id="dup_id_page" class="select"');
		
		$this->output('article_duplicate');
	}


	// ------------------------------------------------------------------------


	function save_duplicate()
	{
		if( $this->input->post('dup_url') != '' )
		{
			// No name change : exit
			if (url_title($this->input->post('dup_url')) == $this->input->post('name'))
			{
				$this->error(lang('ionize_message_article_duplicate_no_name_change'));
			}
			
			/* New article data :
			 * - The updater is set to nobody
			 * - The author become the current connected user
			 *
			 */
			$user = $this->connect->get_current_user();
			$data = array(
				'name' => url_title($this->input->post('dup_url')),
				'id_page' => $this->input->post('dup_id_page'),
				'updater' => $user['username'],
				'author' => $user['username']
			);
			
			// Duplicate the article base data and get the new ID
			$id_new_article = $this->article_model->duplicate($this->input->post('id_article'), $data, $this->input->post('ordering_select') );
		
			if ($id_new_article !== false)
			{
				/* Update the content structure tree
				 * The data var is merged to the default lang data_lang var,
				 * in order to send the lang values to the browser without making another SQL request
				 */
				$menu = $this->menu_model->get_from_page($this->input->post('dup_id_page'));
				
				$article = $this->article_model->get($id_new_article, Settings::get_lang('default'));

				$this->data = $article;
				$this->data['title'] = htmlspecialchars_decode($article['title'], ENT_QUOTES);
				$this->data['id_article'] = $id_new_article;
				$this->data['element'] = 'article';
				$this->data['menu'] = $menu;
				$this->data['online'] = 0;

				// Panels Update array
				$this->update[] = array(
					'element' => 'mainPanel',
					'url' => site_url('admin/article/edit/'.$id_new_article),
					'title' => lang('ionize_title_edit_article')
				);
				
				$this->callback = array(
					'fn' => $menu['name'].'Tree.insertTreeArticle',
					'args' => $this->data
				);

				// Answer send
				$this->success(lang('ionize_message_article_duplicated'));
			}
			else
			{
				$this->error(lang('ionize_message_article_not_duplicated'));
			}
		}
		else
		{
			$this->error(lang('ionize_message_article_not_duplicated'));
		}
	
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Set an item online / offline depending on its current status
	 *
	 * @param	int		item ID
	 *
	 */
	function switch_online($id)
	{
		$status = $this->article_model->switch_online($id);

		$this->id = $id;
		
		// Additional data
		$data = array('status' => $status);
		
		$this->success(lang('ionize_message_operation_ok'), $data);
	}


	// ------------------------------------------------------------------------


	/** 
	 * Saves article ordering
	 * 
	 */
	function save_ordering()
	{
		if( $order = $this->input->post('order') )
		{
			// Saves the new ordering
			$this->article_model->save_ordering($order);
			
			// Update array
			$this->update[] = array(
				'element' => 'structurePanel', 
				'url' => site_url('admin/core/get_structure')
			);
			
			// Answer send
			$this->success(lang('ionize_message_article_ordered'));
		}
		else 
		{
			// Answer send
			$this->error(lang('ionize_message_operation_nok'));
		}
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Gets the article list for the ordering select dropdown
	 * @param	int		Page ID
	 *
	 * @returns	string	HTML string of options items
	 *
	 */
	function get_ordering_article_select($id_page)
	{
		// Articles array
		$this->template['articles'] = $this->article_model->get_lang_list(array('id_page'=>$id_page), Settings::get_lang('default'));
		
		$this->output('article_ordering_select');
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Gets the parent list list for the parent select dropdown
	 * @param	int		Menu ID
	 * @param	int		Page parent ID
	 *
	 * @returns	string	HTML string of options items
	 *
	 */
	function get_parents_select($id_menu, $id_parent=0)
	{
		$datas = $this->page_model->get_lang_list(array('id_menu' => $id_menu), Settings::get_lang('default'));

		($parents_array = $this->structure->get_parent_select($datas, 0) ) ? $parents_array : '';
		
		$this->template['pages'] = $parents_array;
		$this->template['id_selected'] = $id_parent;
		
		$this->output('page_parent_select');
	}


	// ------------------------------------------------------------------------


	/** 
	 * Deletes one article
	 *
	 * @param	int 		Article ID
	 *
	 */
	function delete($id)
	{
		$article = $this->article_model->get(array('id_article' => $id));
	
		$affected_rows = $this->article_model->delete($id);
		
		// Delete was successfull
		if ($affected_rows > 0)
		{
			// Reaffected correct ordering
			$ordering = $this->article_model->get_articles_ordering($article['id_page']);
			
			$this->article_model->save_ordering($ordering);
			
			$this->id = $id;
			$addon_data = array('element' => 'article');
		
			$this->success(lang('ionize_message_operation_ok'), $addon_data);
		}
		else
		{
			$this->error(lang('ionize_message_operation_nok'));
		}
	}


	// ------------------------------------------------------------------------


	/** 
	 * Prepares data before saving
	 *
	 */
	function _prepare_data() 
	{
		// Standard fields
		$fields = $this->db->list_fields('article');
		
		// Set the data to the posted value.
		foreach ($fields as $field)
		{
			if ( ! in_array($field, $this->no_htmlspecialchars))
				$this->data[$field] = htmlspecialchars($this->input->post($field), ENT_QUOTES, 'utf-8');
			else
				$this->data[$field] = $this->input->post($field);
		}

		// Author & updater
		$user = $this->connect->get_current_user();
		if ($this->input->post('id_article'))
			$this->data['updater'] = $user['username'];
		else
			$this->data['author'] =  $user['username'];

		// Ordering
		$existing_ordering = $this->article_model->get_articles_ordering($this->input->post('id_page'));
		
		switch($this->input->post('ordering_select'))
		{
			case 'first' :
			
				// Existing article
				if ($this->input->post('id_article'))
				{
					// Delete current article in ordering array
					array_splice($existing_ordering, $this->input->post('ordering')-1, 1);

					// Unshift current article to ordering table
					array_unshift($existing_ordering, $this->input->post('id_article'));

					// Save articles ordering
					$this->article_model->save_ordering($existing_ordering);
				}
				else
				{
					$this->article_model->shift_article_ordering($this->input->post('id_page'));				
				}
				$this->data['ordering'] = 1;
				
				break;
			
			case 'last' :
			
				// Existing article
				if ($this->input->post('id_article'))
				{
					// Delete current article ordering 
					array_splice($existing_ordering, $this->input->post('ordering')-1, 1);
					
					$this->article_model->save_ordering($existing_ordering);
				}

				$this->data['ordering'] = count($existing_ordering) + 1 ;
				
				break;

			case 'after' :
			
				// Existing article
				if ($this->input->post('id_article'))
				{
					// Delete current article ordering 
					array_splice($existing_ordering, $this->input->post('ordering')-1, 1);
					
					// Push the current article to new pos
					$new_pos = array_search($this->input->post('ordering_after'), $existing_ordering) + 1;
					array_splice($existing_ordering, $new_pos, 0, $this->input->post('id_article'));
					
					// Save new ordering
					$this->article_model->save_ordering($existing_ordering);
					
					$this->data['ordering'] = $new_pos + 1;
				}
				else
				{
					$new_pos = array_search($this->input->post('ordering_after'), $existing_ordering) + 2;

					// Shift every article with a greather pos than ordering_after
					$this->article_model->shift_article_ordering($this->input->post('id_page'), $new_pos);				
					
					$this->data['ordering'] = $new_pos;
				}
			
				break;
				
		}

		// URLs : Feed the other languages URL with the default one if the URL is missing
		$urls = $this->_get_urls(TRUE);

		$default_lang_url = $urls[Settings::get_lang('default')];
		
		foreach($urls as $lang => $url)
			if ($url == '')	$urls[$lang] = $default_lang_url;
		
		// Update the page name (not used anymore in the frontend, but used in the backend)
		$this->data['name'] = $default_lang_url;


		/*
		 * Lang data
		 *
		 */
		$this->lang_data = array();

		$fields = $this->db->list_fields('article_lang');

		foreach(Settings::get_languages() as $language)
		{
			foreach ($fields as $field)
			{
				if ( $field != 'url' && $this->input->post($field.'_'.$language['lang']) !== false)
				{
					// Avoid or not security XSS filter
					if ( ! in_array($field, $this->no_xss_filter))
						$content = $this->input->post($field.'_'.$language['lang']);
					else
					{
						$content = stripslashes($_REQUEST[$field.'_'.$language['lang']]);
					}

					// Convert HTML special char only on other fields than these defined in $no_htmlspecialchars
					if ( ! in_array($field, $this->no_htmlspecialchars))
						$content = htmlspecialchars($content, ENT_QUOTES, 'utf-8');
						
					$this->lang_data[$language['lang']][$field] = $content;
				}
				// URL : Fill with the correct URLs array data
				else if ($field == 'url')
				{
					$this->lang_data[$language['lang']]['url'] = $urls[$language['lang']];
				}
			}
			
			// Online value
			$this->lang_data[$language['lang']]['online'] = $this->input->post('online_'.$language['lang']);
		}
		
		
		/*
		 * Links
		 *
		 */
		// External Link cleaning : We assume an external link has a "." in its URL
		if (strpos($this->data['link'], '.') !== FALSE OR $this->data['link_type'] == '')
		{
			$this->data['link_type'] = $this->data['link_id'] = '';
			
			if ( ! empty($this->data['link']))
				$this->data['link'] = prep_url($this->data['link']);
			
			// This link is unique : All languages data need to have the same
			foreach(Settings::get_languages() as $language)
			{
				$this->lang_data[$language['lang']]['link'] = $this->data['link'];
			}
			
		}
		// Internal link : Get link urls for each language
		else if ($this->data['link_type'] != '' && $this->data['link_type'] != '0')
		{
			$elements = $this->{$this->data['link_type'].'_model'}->get_lang($this->data['link_id']);


			foreach ($elements as $element)
			{
				$this->lang_data[$element['lang']]['link'] = $element['url'];
			}
		}
		// Clean languages link
		else
		{
			foreach(Settings::get_languages() as $language)
			{
				$this->lang_data[$language['lang']]['link'] = '';
			}
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Checks if the element save process can be done.
	 *
	 * @returns		Boolean		True if the save can be done, false if not
	 *
	 */
	function _check_before_save()
	{
		$default_lang = Settings::get_lang('default');
		$default_lang_url = $this->input->post('url_'.$default_lang);
		$default_lang_title = $this->input->post('title_'.$default_lang);
		
		if ($default_lang_url == FALSE && $default_lang_title == FALSE)
		{
			return FALSE;
		}
		
		return TRUE;
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns all the URLs sent for this element
	 *
	 * @param		Boolean		Should the empty lang index be filled with '' ?
	 *
	 * @returns		Array		Multidimensional array of URLs
	 *							ex : $url['en'] = 'my-element-url'
	 *
	 */
	function _get_urls($fill_empty_lang = FALSE)
	{
		$urls = array();
		
		foreach(Settings::get_languages() as $l)
		{
			// If lang URL exists, use it
			if ( $this->input->post('url_'.$l['lang']) !== '' )
			{
				$urls[$l['lang']] = url_title($this->input->post('url_'.$l['lang']));
			}
			else
			{
				// Try to use the lang title
				if ( $this->input->post('title_'.$l['lang']) !== '' )
				{
					$urls[$l['lang']] = url_title($this->input->post('title_'.$l['lang']));
				}
				// Fill with empty value if needed 
				else if ($fill_empty_lang == TRUE)
				{
					$urls[$l['lang']] = '';
				}
			}
		}
		
		return $urls;
	}
}


/* End of file article.php */
/* Location: ./application/controllers/admin/article.php */