<?php
/**
 * Ionize
 *
 * @package		Ionize
 * @author		Martin Wernståhl on 2010-01-02
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.92
 */


/**
 * Page TagManager 
 *
 */
class TagManager_Page extends TagManager
{	
	/* Special URI array
	 * Feeded with values from $this->ci->config->item('special_uri');
	 * Used to define which URI are "special one"
	 * Special URI permit to get articles with special condition like "by archive" or "by categories"
	 *
	 */
	protected $uri_config = array();

	// ------------------------------------------------------------------------

	/**
	 * 
	 * 
	 * @return 
	 */
	public function __construct($controller, $con, $pages)
	{
		$this->ci = $controller;

		// Add pages to the context
		if ( empty($con->globals->pages))
		{
			$con->globals->pages = $pages;
			
			// Set all abolute URLs one time, for perf.
			$this->init_absolute_urls($con);
		}
		
		// Article model
		$this->ci->load->model('article_model');

		// Helpers
		$this->ci->load->helper('text');

		// Pagination URI
		$this->uri_config = $this->ci->config->item('special_uri');
		
		// Get the pagination URI
		$uri_config = array_flip($this->uri_config);
		$this->pagination_uri = $uri_config['pagination'];
		
		$this->tag_definitions = array_merge($this->tag_definitions, array(
			
			// Page
//			'link' => 				'tag_page_link',					// Deprecated since 0.9.5
			'count' => 				'tag_count',
			'period' => 			'tag_period',
			'pagination' =>			'tag_pagination',
			'absolute_url' =>		'tag_absolute_url',
			
			// Languages
			'languages' =>					'tag_languages',
			'languages:language' =>			'tag_languages_language',
			'languages:name' =>				'tag_languages_name',
			'languages:code' =>				'tag_languages_code',
			'languages:active_class' =>		'tag_languages_active_class',
//			'languages:page_url' =>			'tag_languages_page_url',		// Deprecated in 0.9.5. Replaced by <ion:url />
			'languages:url' =>			'tag_languages_url',
			
			// Page
			'id_page' => 			'tag_page_id',
			'page:name' => 			'tag_page_name',
			'page:url' => 			'tag_page_url',
			'title' => 				'tag_page_title',
			'subtitle' => 			'tag_page_subtitle',
			'meta_title' => 		'tag_page_meta_title',
			'content' =>			'tag_page_content',
			
			// Breadrumb
			'breadcrumb' =>			'tag_breadcrumb',
			
			// Navigation
			'navigation' => 					'tag_navigation',
			'navigation:title' =>				'tag_page_title',			
			'navigation:subtitle' =>			'tag_page_subtitle',
			'navigation:active_class' =>		'tag_navigation_active_class',			
			'navigation:base_link' =>			'tag_navigation_base_link',			
			'navigation:lang_link' =>			'tag_navigation_lang_link',			
			'navigation:url' =>					'tag_navigation_url',			
			'tree_navigation' => 				'tag_tree_navigation',
			'tree_navigation:active_class' =>	'tag_navigation_active_class',			
			'tree_navigation:base_link' =>		'tag_navigation_base_link',			
			'tree_navigation:lang_link' =>		'tag_navigation_lang_link',			
				
			// Articles
			'articles' => 				'tag_articles',
			'articles:article' => 		'tag_articles_article',
			'articles:id_article' => 	'tag_article_id',
			'articles:view' => 			'tag_article_view',
			'articles:author' => 		'tag_article_author_name',
			'articles:author_email' => 	'tag_article_author_email',
			'articles:name' => 			'tag_article_name',
			'articles:title' => 		'tag_article_title',
			'articles:subtitle' => 		'tag_article_subtitle',
			'articles:meta_title' =>    'tag_article_meta_title', //Added Tag name here
			'articles:date' => 			'tag_article_date',
			'articles:content' => 		'tag_article_content',
			'articles:url' => 			'tag_article_url',
//			'articles:link' => 			'tag_article_link',				// Deprecated since 0.9.5
			'articles:categories' => 	'tag_article_categories',
			'articles:readmore' => 		'tag_article_readmore',
						
			// Articles by Categories
			'categories' => 				'tag_categories',
			'categories:url' => 			'tag_categories_url',
			'categories:active_class' => 	'tag_categories_active_class',
			'categories:lang_url' => 		'tag_categories_lang_url',
			
			// Archives
			'archives' =>				'tag_archives',
			'archives:url' => 			'tag_archives_url',
			'archives:lang_url' => 		'tag_archives_lang_url',
			'archives:period' => 		'tag_archives_period',
			'archives:nb' => 			'tag_archives_nb',
			'archives:active_class' => 	'tag_archives_active_class',
			
			// Medias tags
			// Missing tag : date !!!!
			'medias' => 			'tag_page_medias',
			'articles:medias' => 	'tag_article_medias',
			'medias:id_media' => 	'tag_media_id',
			'medias:alt' => 		'tag_media_alt',
			'medias:base_path' => 	'tag_media_base_path',
			'medias:path' => 		'tag_media_path',				// Can do nesting, no change if not nested ('path' => 'tag_src')
			'medias:src' => 		'tag_media_src',
			'medias:size' => 		'tag_media_size',
			'medias:title' => 		'tag_media_title',
			'medias:link' => 		'tag_media_link',
			'medias:file_name' => 	'tag_media_file_name',
			'medias:description' => 'tag_media_description',
			'medias:copyright' => 	'tag_media_copyright'
		));
	}

	
	// ------------------------------------------------------------------------

	
	public function add_globals(FTL_Context $con)
	{
		parent::add_globals($con);

		// Get current asked page
		$con->globals->page = $this->get_current_page($con, $this->ci->uri->segment(3));
	
		// Show 404 if no page
		if(empty($con->globals->page))
		{
			$this->set_404($con);
			// show_error("TagManager_Page Error :<br/><ul><li>No existing page or</li><li>Unable to determine wich page should be displayed or </li><li>Page translation not done in the default language : <b>".Settings::get_lang().'</b></li></ul>');
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Set base data for a 404 page
	 *
	 */
	public function set_404(&$con)
	{
		$con->globals->page = array(
			'link' => '404'
		);
	}	


	// ------------------------------------------------------------------------


	/**
	 * Set data for a page
	 *
	 */
	public function set_page_data($data)
	{
		$con->globals->page = array(
			'id_page' => ( ! empty($data['id_page'])) ? $data['id_page'] : 0,
			'view' => ( ! empty($data['view'])) ? $data['view'] : '',
			'title' => ( ! empty($data['title'])) ? $data['title'] : '',
			'level' => ( ! empty($data['level'])) ? $data['level'] : '',
			'link' => ( ! empty($data['link'])) ? $data['link'] : '',
			'content' => ( ! empty($data['content'])) ? $data['content'] : ''
		);
	}	


	// ------------------------------------------------------------------------


	/**
	 * Set all the absolute URLs, for pages but also for languages
	 *
	 *
	 */
	public function init_absolute_urls(&$con)
	{
		foreach ($con->globals->pages as &$page)
		{
			// Set the page complete URL
			$page['absolute_url'] = '';

			// If link, returns the link
			if ($page['link'] != '' )
			{
				// External link
				if ($page['link_type'] == '')
				{
					$page['absolute_url'] = $page['link'];
				}
				
				// For article link, retrieve the page to build the link
				if($page['link_type'] == 'article')
				{
					// Get the article (we need to know its parent page ID)
					$article = $this->ci->article_model->get(array('id_article'=>$page['link_id']));
					
					// Of course, only if not empty...
					if (!empty($article))
					{
						// Get the article's parent page
						$parent_page = array_values(array_filter($con->globals->pages, create_function('$row','return $row["id_page"] == "'. $article['id_page'] .'";')));
						
						$page['absolute_url'] = (!empty($parent_page[0])) ? $parent_page[0]['url'] . '/' . $page['link'] : '';
					}
				}
				else
				{
					// This is a page link
					$page['absolute_url'] = $page['link'];
				}
			}
			/*
			else
			{
				// Home page
				if ($page['home'] == 1)
				{
					$page['absolute_url'] = '';
				}
			}
			*/
			
			// This test is only done for external link
			if ( empty($page['absolute_url']))
			{
				// Only returns the URL containing the lang code when languages > 1
				if (count(Settings::get_online_languages()) > 1)
				{
					// Home page
					if ($page['home'] == 1 )
					{
						// Default language : No language code in the URL for the home page
						if (Settings::get_lang('default') == Settings::get_lang())
						{
							$page['absolute_url'] = base_url();
						}
						// Other language : The home page has the lang code in URL
						else
						{
							$page['absolute_url'] = base_url() . Settings::get_lang();
						}
					}
					// Other pages : lang code in URL
					else
					{
						$page['absolute_url'] = base_url() . Settings::get_lang() . '/' . $page['url'];
					}
					
					// Set the lang code depending URL (used by language subtag)
					$page['absolute_urls'] = array();
					foreach (Settings::get_online_languages() as $lang)
					{
					
						if ($page['home'] == 1 )
						{
							// Default language : No language code in the URL for the home page
							if (Settings::get_lang('default') == $lang['lang'])
							{
								$page['absolute_urls'][$lang['lang']] = base_url();
							}
							// Other language : The home page has the lang code in URL
							else
							{
								$page['absolute_urls'][$lang['lang']] = base_url() . $lang['lang'];
							}
						}
						// Other pages : lang code in URL
						else
						{
							$page['absolute_urls'][$lang['lang']] = base_url() . $lang['lang'] . '/' . $page['urls'][$lang['lang']];
						}
					}
				}
				// 1 language : The lang code is not in the URL
				else
				{
					if ($page['home'] == 1)
					{
						$page['absolute_url'] = base_url();
					}
					else
					{
						$page['absolute_url'] = base_url() . $page['url'];
					}
					
					// Set the lang code depending URL (used by language subtag)
					$page['absolute_urls'][Settings::get_lang()] = $page['absolute_url'];
					
				}
				
				// Adds the suffix if defined
				if ( config_item('url_suffix') != '' ) $page['absolute_url'] .= config_item('url_suffix');
			}
			else
			{
				foreach (Settings::get_online_languages() as $lang)
				{
					$page['absolute_urls'][$lang['lang']] = $page['absolute_url'];
				}
			}
		}
	}
	

	// ------------------------------------------------------------------------


	/**
	 * Get Articles
	 * @param	
	 *
	 * 1. Try to get the articles from a special URI
	 * 2. Get the articles from the current page
	 * 3. Filter on the article name if the article name is in URI segment 1
	 *
	 */
	function get_articles($tag, $filter=false, $order_by=false, $like=false)
	{
		$articles = array();

		// Page from locals
		$pages =& $tag->locals->pages;

		// Get the potential special URI
		$special_uri = (isset($this->ci->uri_segment[1])) ? $this->ci->uri_segment[1] : false;

		// Use Pagination
		// The "articles" tag must explicitely indicates it want to use pagination. 
		// This explicit declaration is done to avoid all articles tags on one page using the same pagination value.
		$use_pagination = (isset($tag->attr['pagination']) && $tag->attr['pagination'] == 'true') ? true : false;

		// Don't use the "article_list_view" setting set through Ionize
		$keep_view = (isset($tag->attr['keep_view'])) ? true : false;

		// Use this view for each article if more than one article
		$list_view = (isset($tag->attr['list_view'])) ? $tag->attr['list_view'] : false;


		// Number of article limiter
		$num = (isset($tag->attr['num'])) ? $this->get_attribute($tag, 'num') : 0 ;

		// Get the special URI config array (see /config/ionize.php)
		$uri_config = $this->ci->config->item('special_uri');

		// filter & "with" tag compatibility
		// create a SQL filter
		$filter = (isset($tag->attr['filter']) && $tag->attr['filter'] != '') ? $tag->attr['filter'] : false;

		/* Scope can be : 
		 * not defined : 	means current page
		 * "parent" :		parent page
		 * "global" :		all pages from the website
		 * "pages" : 		one or more page names. Not done for the moment
		 *
		 */
		$scope = (isset($tag->attr['scope']) && $tag->attr['scope'] != '' ) ? $tag->attr['scope'] : false;

		// from page name ?
		// $from_page = (isset($tag->attr['from']) && $tag->attr['from'] !='' ) ? $tag->attr['from'] : false;
		$from_page = (isset($tag->attr['from']) && $tag->attr['from'] !='' ) ? $this->get_attribute($tag, 'from') : false;

		// from categories ? 
		$from_categories = (isset($tag->attr['from_categories']) && $tag->attr['from_categories'] != '') ? $this->get_attribute($tag, 'from_categories') : false;
		$from_categories_condition = (isset($tag->attr['from_categories_condition']) && $tag->attr['from_categories_condition'] != 'or') ? 'and' : 'or';

		// Order. Default order : ordering ASC
		$order_by = (isset($tag->attr['order_by']) && $tag->attr['order_by'] != '') ? $tag->attr['order_by'] : 'ordering ASC';

		/*
		 * Preparing WHERE on articles
		 * From where do we get the article : from a page, from the parent page or from the all website ?
		 *
		 */
		$where = array();

		// Add type to the where array
		if ( ! empty ($tag->attr['type']) )
		{
			$where['article_type.type'] = $tag->attr['type'];
		}

		// If a page name is set, returns only articles from this page
		if ($from_page !== false)
		{
			// Get the asked page details
			$asked_pages = explode(',', $from_page);

			$in_pages = array();
			
			// Check if one lang URL of each page can be used for filter
			foreach($pages as $page)
			{
				$add_ref = FALSE;
				$urls = array_values($page['urls']);
				foreach($urls as $url)
				{
					if (in_array($url, $asked_pages))
						$in_pages[] = $page['id_page'];
				}
			}

			// If not empty, filter articles on id_page
			if ( ! empty($in_pages))
			{
				$where['id_page in'] = '('.implode(',', $in_pages).')';
			}
			// else return nothing. Seems the asked page doesn't exists...
			else
			{
				return;
			}
		}
		else if ($scope == 'parent')
		{
			$where += $this->set_parent_scope($tag);
		}
		else if ($scope == 'global')
		{
			$where += $this->set_global_scope($tag);
		}
		// Get only articles from current page
		else
		{
			$where['id_page'] = $tag->locals->page['id_page'];
		}


		/* Get the articles
		 *
		 */
		// If a special URI exists, get the articles from it.
		if ($special_uri !== false && array_key_exists($special_uri, $uri_config) && $from_page === false)
		{
			if (method_exists($this, 'get_articles_from_'.$uri_config[$special_uri]))
				$articles = call_user_func(array($this, 'get_articles_from_'.$uri_config[$special_uri]), $tag, $where, $order_by, $filter);
		}
		// This case is very special : getting one article through his name in the URL
		else if ($special_uri !== false && !array_key_exists($special_uri, $uri_config) && $from_page == false)
		{
			$articles = $this->get_articles_from_one_article($tag, $where, $order_by, $filter);
		}
		// Get all the page articles
		// If Pagination is active, set the limit. This articles result is the first page of pagination
		else 
		{
			$limit = (!empty($tag->locals->page['pagination']) && ($tag->locals->page['pagination'] > 0) ) ? $tag->locals->page['pagination'] : false;

			if ($limit == false && $num > 0) $limit = $num;
			
			if ($from_categories !== false)
			{
				$articles = $this->ci->article_model->get_from_categories(
					$where,
					explode(',', $from_categories),
					$from_categories_condition,
					$lang = Settings::get_lang(),
					$limit,
					$like = false,
					$order_by,
					$filter
				);
			}
			else
			{
				$articles = $this->ci->article_model->get_lang_list(
					$where,
					$lang = Settings::get_lang(),
					$limit,
					$like = false,
					$order_by,
					$filter
				);
			}
		}

		// Here, we are in an article list configuration : More than one article, page display
		// If the article-list view exists, we will force the article to adopt this view.
		// Not so much clean to do that in the get_article funtion but for the moment just helpfull...
		if (count($articles) > 1 && $keep_view == false)
		{
			if ( !empty($tag->locals->page['article_list_view']) OR $list_view !== false )
			{
				foreach ($articles as $k=>$article)
				{
					// Set the article view to the page "article-list" value view.
					if ($list_view !== false)
					{
						$articles[$k]['view'] = $list_view;
					}
					else
					{
						$articles[$k]['view'] = $tag->locals->page['article_list_view'];
					}
				}
			}
		}

		return $articles;
	}


	// ------------------------------------------------------------------------

	/**
	 * Pagination articles
	 *
	 * @param	Array	Current page array
	 * @param	Array	SQL Condition array
	 * @param	String	order by condition
	 * @param	String	Filter string
	 *
	 * @return	Array	Array of articles
	 *
	 */
	function get_articles_from_pagination($tag, $where, $order_by, $filter)
	{
		$page = & $tag->locals->page;

		$start_index = array_pop(array_slice($this->ci->uri_segment, -1));

		// Load CI Pagination Lib
		isset($this->ci->pagination) OR $this->ci->load->library('pagination');
	
		// Number of displayed articles / page
		// If no pagination : redirect to the current page
		$per_page = (isset($page['pagination']) && $page['pagination'] > 0) ? $page['pagination'] : redirect($this->ci->uri_segment[0]);

		// from categories ? 
		$from_categories = (isset($tag->attr['from_categories']) && $tag->attr['from_categories'] != '') ? $this->get_attribute($tag, 'from_categories') : false;
		$from_categories_condition = (isset($tag->attr['from_categories_condition']) && $tag->attr['from_categories_condition'] != 'or') ? 'and' : 'or';

		if ($from_categories !== false)
		{
			$articles = $this->ci->article_model->get_from_categories(
				$where,
				explode(',', $from_categories),
				$from_categories_condition,
				$lang = Settings::get_lang(),
				array( (int) $start_index, (int) $per_page),
				$like = false,
				$order_by,
				$filter
			);
		}
		else
		{
			$articles = $this->ci->article_model->get_lang_list(
				$where,
				$lang = Settings::get_lang(),
				array( (int) $start_index, (int) $per_page),
				$like = false,
				$order_by,
				$filter
			);
		}

		// Set the view
		// Rule : If page has article_list_view defined, used this one.
		if($page['article_list_view'] != false)
		{
			foreach ($articles as $k=>$article)
			{
				$articles[$k]['view'] = $page['article_list_view'];
			}
		}

		return $articles;
	}
	
	
	// ------------------------------------------------------------------------


	/**
	 * Get articles linked to a category
	 * Called if special URI "category" is found. See tag_articles()
	 * Uses the $this->ci->uri_segment var to determine the category name
	 *
	 * @param	array	Current page array
	 * @param	Array	SQL Condition array
	 * @param	String	order by condition
	 * @param	String	Filter string
	 *
	 * @return	Array	Array of articles
	 *
	 */
	function get_articles_from_category($tag, $where, $order_by, $filter)
	{
		$page = & $tag->locals->page;

		// Get the start index for the SQL limit query param : last part of the URL
		$start_index = array_pop(array_slice($this->ci->uri_segment, -1));

		// If category name exists
		if (isset($this->ci->uri_segment[2]))
		{
			// Set the query limit
			$limit = false;

			if ($start_index > 0 && $page['pagination'] > 0)
				$limit = array($start_index, $page['pagination'] );
			else if ($page['pagination'] > 0)
			{
				$limit = $page['pagination'];
			}
							
			// Get the articles
			$articles = $this->ci->article_model->get_from_category
			(
				$where, 
				$this->ci->uri_segment[2], 
				Settings::get_lang(),
				$limit,
				$like= false,
				$order_by, 
				$filter
			);

			return $articles;
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Get articles linked from a period
	 * Called if special URI "archives" is found. See tag_articles()
	 * Uses the $this->ci->uri_segment var to determine the category name
	 *
	 * @param	Array	Current page array
	 * @param	Array	SQL Condition array
	 * @param	String	order by condition
	 * @param	String	Filter string
	 *
	 * @return	Array	Array of articles
	 *
	 */
	function get_articles_from_archives($tag, $where, $order_by, $filter)
	{
		$page = & $tag->locals->page;

		$start_index = 0;
	
		// Get the start index for the SQL limit query param : last part of the URL only if the 4th URI segmenet (pagination) is set
		if (isset($this->ci->uri_segment[4]))
			$start_index = array_pop(array_slice($this->ci->uri_segment, -1));

		// If year is set
		if (isset($this->ci->uri_segment[2]))
		{

			$year = $this->ci->uri_segment[2];
		
			$month = isset($this->ci->uri_segment[3]) ? $this->ci->uri_segment[3] : NULL;
			
			// Set the query limit
			$limit = false;

			if ($start_index > 0 && $page['pagination'] > 0)
				$limit = array($start_index, $page['pagination'] );
			else if ($page['pagination'] > 0)
			{
				$limit = $page['pagination'];
			}

			$articles =  $this->ci->article_model->get_from_archives
			(
				$where, 
				$year, 
				$month, 
				Settings::get_lang(),
				$limit,
				$like= false,
				$order_by, 
				$filter
			);

			return $articles;
		}
	}

	

	// ------------------------------------------------------------------------

	
	/**
	 * Returns one named article from website
	 * In this case, the current pag s not important, the URL asked article will be displayed.
	 * Gives the ability to display a given article at any place of the website.
	 *
	 * @param	array	Current page array
	 * @param	Array	SQL Condition array
	 * @param	String	order by condition
	 * @param	String	Filter string
	 *
	 * @return	Array	Array of articles
	 */
	function get_articles_from_one_article($tag, $where, $order_by, $filter)
	{
		$page = & $tag->locals->page;
	
		$articles = array();
		
		$name = array_pop(array_slice($this->ci->uri_segment, -1));

		$where = array('article_lang.url' => $name);

		$articles =  $this->ci->article_model->get_lang_list
		(
			$where, 
			Settings::get_lang(),
			$limit = false,
			$like = false,
			$order_by,
			$filter
		);
		
		return $articles;
	}

	
	function set_parent_scope($tag)
	{
		$where = array();
		$in_pages = array();
	
		$id_parent = $tag->locals->page['id_parent'];
		
		/**
		 * NOT DONE FOR THE MOMENT
		 * IDEA :
		 * Use the parent tag to define a parent scope : 
		 * Means not only the article from the current page parent can be displayed, but also the 
		 * articles from parent / parent
		 *
		$scope_level = (!empty($tag->attr['scope_level'])) ? $tag->attr['scope_level'] : false;
		
		// Scope level can be equal to 0 : first level
		if ($scope_level !== false)
		{
		}
		 */
		
		// Get all pages ID where the parent is the current parent ID
		// Sister pages from current parent page
		$parents = $this->ci->page_model->get_list(array('id_parent' => $id_parent));

		// Page from locals
		$pages =&  $tag->locals->pages;

		foreach($parents as $page)
			$in_pages[] = $page['id_page'];
		
		// If not empty, filter articles on id_page
		if ( ! empty($in_pages))
			$where['id_page in'] = '('.implode(',', $in_pages).')';
			
		return $where;
	}

	function set_global_scope($tag)
	{
		$where = array();
		$in_pages = array();

		// Page from locals
		$pages =&  $tag->locals->pages;

		// Get only articles from autorized pages
		foreach($pages as $page)
			$in_pages[] = $page['id_page'];

		$where['id_page in'] = '('.implode(',', $in_pages).')';
		
		return $where;
	}


	// ------------------------------------------------------------------------


	/**
	 * Count the articles depending on the URI segments
	 * Detect if a special URI is used.
	 *
	 * @return	int		The number of count articles
	 *
	 */
	function count_articles($tag, $filter)
	{
		if ( ! isset($tag->locals->page['nb_articles']))
		{
			$nb = 0;
		
			// Check if articles comes from a special URI result
			$special_uri = isset($this->ci->uri_segment[1]) ? $this->ci->uri_segment[1] : false;
	
			// Special URI
			// For example, to count articles from one archive
			if ($special_uri !== false && array_key_exists($special_uri, $this->uri_config) && $this->uri_config[$special_uri] != 'pagination' )
			{
				// If special URI count method exists, use it !
				// That mean that foreach special URI, you need to define a method to count the articles
				// depending of this special URI.
				if (method_exists($this, 'count_articles_from_'.$this->uri_config[$special_uri]))
					$nb = call_user_func(array($this, 'count_articles_from_'.$this->uri_config[$special_uri]), $tag, $filter);
			}
			// Only one article is displayed
			// The special URI is the article name
			else if ($special_uri !== false && ( ! array_key_exists($special_uri, $this->uri_config) ))
			{
				return 1;
			}
			// No special URI
			else 
			{
				$where = array();

				// from categories ? 
				$from_categories = (isset($tag->attr['from_categories']) && $tag->attr['from_categories'] != '') ? $this->get_attribute($tag, 'from_categories') : false;
				$from_categories_condition = (isset($tag->attr['from_categories_condition']) && $tag->attr['from_categories_condition'] != 'or') ? 'and' : 'or';
			
				// Get the scope set to the pagination tag
				$scope = (isset($tag->attr['scope']) && $tag->attr['scope'] != '' ) ? $tag->attr['scope'] : false;
				
				if ($scope !== false)
				{
					if ($scope == 'parent')
						$where = $this->set_parent_scope($tag);
			
					if ($scope == 'global')
						$where = $this->set_global_scope($tag);
				}
				else
				{
					$where = array('id_page'=>$tag->locals->page['id_page']);
				}

				
				// Reduce to the categories
				if ($from_categories !== false)
				{
					$nb = $this->ci->article_model->count_articles_from_categories(
						$where, 
						explode(',', $from_categories), 
						$from_categories_condition, 
						Settings::get_lang('current'), 
						$filter
					);
				}
				else
				{
					// Count all articles in the current page : SQL
					$nb = $this->ci->article_model->count_articles(
						$where,
						Settings::get_lang('current'),
						$filter
					);
				}
			}
			return $nb;
		}
		else
		{
			return $tag->locals->page['nb_articles'];
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Count the articles from a given category
	 * Called by count_articles
	 *
	 * @return	int		The number of count articles
	 *
	 */
	function count_articles_from_category($tag, $filter)
	{
		$nb = 0;
		
		$category = isset($this->ci->uri_segment[2]) ? $this->ci->uri_segment[2] : NULL;
		
		if ( ! is_null($category))
		{
			$nb = $this->ci->article_model->count_articles_from_category
			(
				array('id_page'=>$tag->locals->page['id_page']),
				$category,
				Settings::get_lang('current'),
				$filter
			);
		}
		return $nb;
	}


	// ------------------------------------------------------------------------


	/**
	 * Count the articles from archive
	 * Called by count_articles
	 *
	 * @return	int		The number of count articles
	 *
	 */
	function count_articles_from_archives($tag, $filter)
	{
		$nb = 0;
		
		$year = 	isset($this->ci->uri_segment[2]) ? $this->ci->uri_segment[2] : NULL;
		$month = 	isset($this->ci->uri_segment[3]) ? $this->ci->uri_segment[3] : NULL;
		
		if ( ! is_null($year))
		{
			$nb = $this->ci->article_model->count_articles_from_archives(
				array('id_page'=>$tag->locals->page['id_page']),
				$year,
				$month,
				Settings::get_lang('current'),
				$filter
			);
		}

		return $nb;
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns the pagination addon URI for categories pagination
	 *
	 * @return	String		The pagination addon URI
	 *
	 */
	function get_pagination_uri_addon_from_category()
	{
		$category_uri = 	$this->ci->uri_segment[1];
		$category_name = 	$this->ci->uri_segment[2];

		return $category_uri . '/' . $category_name .'/';
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns the pagination addon URI for archives pagination
	 *
	 * @return	String		The pagination addon URI
	 *
	 */
	function get_pagination_uri_addon_from_archives()
	{
		$archive_uri = $this->ci->uri_segment[1];
	
		$year = isset($this->ci->uri_segment[2]) ? $this->ci->uri_segment[2] : NULL;
		$month = isset($this->ci->uri_segment[3]) ? $this->ci->uri_segment[3] : NULL;
		
		if ( ! is_null($year))
		{
			$archive_uri .= '/' .  $year;

			if ( ! is_null($month))
			{
				$archive_uri .= '/' .  $month;
			}				
		}

		return $archive_uri .'/';
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the medias regarding the type
	 *
	 */
	public static function get_medias($tag, $medias)
	{
		// Media type
		$type = (isset($tag->attr['type']) ) ? $tag->attr['type'] : false;

		// Media extension
		$extension = (isset($tag->attr['extension']) ) ? $tag->attr['extension'] : false;
		
		// Number of wished displayed medias
		$num = (isset($tag->attr['num'] )) ? $tag->attr['num'] : 9999 ;

		// Range : Start and stop index, coma separated
		$range = (isset($tag->attr['range'] )) ? explode(',',$tag->attr['range']) : false ;
		$from = $to = false;
		
		if ($range !== false)
		{
			$from = $range[0];
			$to = (isset($range[1]) && $range[1] >= $range[0]) ? $range[1] : false;
		}
		
		// Return list ?
		// If set to "list", will return the media list, coma separated.
		// Usefull for javascript
		// Not yet implemented
		$return = ( ! empty($tag->attr['return'])) ? $tag->attr['return'] : false;


		$i = 0;
		
		if ($type !== false)
		{
			$str = '';
			$filtered_medias = array();

			if ( ! empty($medias))
			{
				// First get the correct media type
				// filter by type
				foreach($medias as $media)
				{
					if ($media['type'] == $type && $i < $num)
					{
						$filtered_medias[] = $media;
					}
				}
				
				// Filter by extension if needed
				if ($extension !== false)
				{
					$extension = explode(',', $extension);
					
					$tmp_medias = $filtered_medias;
					$filtered_medias = array();
					
					foreach($tmp_medias as $media)
					{
						$ext = substr($media['file_name'], strrpos($media['file_name'], '.') +1 );
						
						if (in_array($ext, $extension))
						{
							$filtered_medias[] = $media;
						}
					}
				}
				
				// Second, if there is a range, get the medias from this range
				if ($range !== false)
				{
					foreach($filtered_medias as $index => $media)
					{
						if ($index >= $from && $i < $num)
						{
							if ($index <= $to OR $to === false)
							{
								$tag->locals->media = $media;
								$str .= $tag->expand();
								$i++;
							}
						}
					}
				}
				// Else, get all medias, just $num limited
				else
				{
					foreach($filtered_medias as $media)
					{
						if ($i < $num)
						{
							$tag->locals->media = $media;
							$str .= $tag->expand();
							$i++;
						}
					}
				}
			}
			return $str;
		}
		else
		{
			return;
		}
	}


	// ------------------------------------------------------------------------

	/**
	 * Returns the page absolute URL
	 *
	 */
	public function tag_absolute_url($tag)
	{
		return $tag->locals->page['absolute_url'];
	}


	// ------------------------------------------------------------------------


	/**
	 * Languages tag
	 * 
	 * @param	FTL_Binding		The binded tag to parse
	 *
	 */
	public function tag_languages($tag)
	{
		$languages = Settings::get_online_languages();

		$str = '';

		foreach($languages as $lang)
		{
//			$url = $tag->locals->page['absolute_urls'][$lang['lang']];
			
//			if ($url == '') $url = $tag->locals->page['url'];

			$tag->locals->lang = $lang;
			$tag->locals->url = $tag->locals->page['absolute_urls'][$lang['lang']];

			if (Connect()->is('editors', true) OR $lang['online'] == 1)
			{
				$str .= $tag->expand();
			}
		}
		
		return $str;
	}


	/**
	 * Languages nested tags
	 * 
	 * @param	FTL_Binding		The binded tag to parse
	 *
	 */
	public function tag_languages_language($tag) { return $tag->locals->lang['name']; }
	public function tag_languages_name($tag) { return $tag->locals->lang['name']; }
	public function tag_languages_code($tag) { return $tag->locals->lang['lang']; }
	public function tag_languages_url($tag) {return $tag->locals->url;}


	/**
	 * Language current active CSS class
	 * @default	'active'
	 *
	 */
	public function tag_languages_active_class($tag)
	{
		$active_class = (isset($tag->attr['active_class'])) ? $tag->attr['active_class'] : 'active';
		return ($tag->locals->lang['lang'] == Settings::get_lang('current') ) ? $active_class : '' ;
	}


	// ------------------------------------------------------------------------


	/**
	 * Pagination tag
	 * 
	 * @todo : More options should be implemented !!!!
	 *
	 * Main class name, id, open tag, close tag, every options from cI in fact ! 
	 *
	 */
	public function tag_pagination($tag)
	{
		/* 
		 * Tag attributes
		 */
		// Pagination configuration array
		$pagination_config = array();
		
		// Number of total articles
		$total_articles = 0;
	
		// Number of displayed articles : tag attribute has priority 1.
		$per_page = (isset($tag->attr['per_page']) && is_int( (int) $tag->attr['per_page']) ) ? $tag->attr['per_page'] : false;

		if ($per_page === false)
			$per_page = (isset($tag->locals->page['pagination']) && $tag->locals->page['pagination'] > 0) ? $tag->locals->page['pagination'] : false;

		// Filter
		$filter = (isset($tag->attr['filter'])) ? $tag->attr['filter'] : false;

		// Order. No default order
		$order_by = (isset($tag->attr['order_by']) && $tag->attr['order_by'] != '') ? $tag->attr['order_by'] : false;


		/*
		 * Pagination URL
		 * Pagination tag has to determine if a special URI is used in order to build the pagination base_url
		 *
		 */
		$base_url = '';
		$uri_addon = '';
		
		// Get the potential special URI
		$special_uri = (isset($this->ci->uri_segment[1])) ? $this->ci->uri_segment[1] : false;


		// Get the special URI config array (see /config/ionize.php)
		$uri_config = $this->ci->config->item('special_uri');


		// If a special URI exists and is different from pagination URI, get the special URI to the pagination URL
		// Calling a special function for that is mandatory as each special URI can have different numbers of parameters
		if ($special_uri !== false && $special_uri != $this->pagination_uri && array_key_exists($special_uri, $uri_config))
		{
			if (method_exists($this, 'get_pagination_uri_addon_from_'.$uri_config[$special_uri]))
			{
				$uri_addon = call_user_func(array($this, 'get_pagination_uri_addon_from_'.$uri_config[$special_uri]));
			}
		}

		/*
		 * URI building : Lang URI or not....
		 *
		 */
		// don't display the lang URL (by default)
		// If lang attribute is set to true, force the lang code to be in the URL
		// Usefull only if the website has only one language
		$lang_url = (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true' ) ? true : false;
		
		if (count(Settings::get_online_languages()) > 1 OR $lang_url === true)
		{
			$base_url = base_url() . Settings::get_lang('current') . '/'. $tag->locals->page['url'] . '/' . $uri_addon . $this->pagination_uri;
		}
		else
		{
			$base_url = base_url() . $tag->locals->page['url'] . '/' . $uri_addon . $this->pagination_uri;		
		}
		
		/*
		 * Pagination tag result design
		 */
		$class = (isset($tag->attr['class'])) ? ' class="' . $tag->attr['class'] .'" ' : '';
		$id = (isset($tag->attr['id'])) ? ' id="' . $tag->attr['id'] .'" ' : '';

		// Article count :
		$tag->locals->page['nb_articles'] = $this->count_articles($tag, $filter);

		// Pagination setup
		if ($per_page > 0 && $tag->locals->page['nb_articles'] > $per_page)
		{
			// Load CI Pagination Lib
			isset($this->ci->pagination) OR $this->ci->load->library('pagination');
			
			// Pagination theme config
			$cf = Theme::get_theme_path().'config/pagination.php';
			if (is_file($cf))
			{
				require($cf);
				$pagination_config = $config;
				unset($config);
			}	

			// Pagination config from tag
			if (isset($tag->attr['full_tag'])) {
				$pagination_config['full_tag_open'] = 		'<' . $tag->attr['full_tag'] . $id . $class . '>';
				$pagination_config['full_tag_close'] = 		'</' . $tag->attr['full_tag'] . '>';			
			}
			if (isset($tag->attr['first_tag'])) {
				$pagination_config['first_tag_open'] = 		'<' . $tag->attr['first_tag'] . '>';
				$pagination_config['first_tag_close'] = 	'</' . $tag->attr['first_tag'] . '>';
			}
			if (isset($tag->attr['last_tag'])) {
				$pagination_config['last_tag_open'] = 		'<' . $tag->attr['last_tag'] . '>';
				$pagination_config['last_tag_close'] = 		'</' . $tag->attr['last_tag'] . '>';
			}
			if (isset($tag->attr['cur_tag'])) {
				$pagination_config['cur_tag_open'] = 		'<' . $tag->attr['cur_tag'] . '>';
				$pagination_config['cur_tag_close'] = 		'</' . $tag->attr['cur_tag'] . '>';
			}
			if (isset($tag->attr['next_tag'])) {
				$pagination_config['next_tag_open'] = 		'<' . $tag->attr['next_tag'] . '>';
				$pagination_config['next_tag_close'] = 		'</' . $tag->attr['next_tag'] . '>';
			}
			if (isset($tag->attr['prev_tag'])) {
				$pagination_config['prev_tag_open'] = 		'<' . $tag->attr['prev_tag'] . '>';
				$pagination_config['prev_tag_close'] = 		'</' . $tag->attr['prev_tag'] . '>';
			}
			if (isset($tag->attr['num_tag'])) {
				$pagination_config['num_tag_open'] = 		'<' . $tag->attr['num_tag'] . '>';
				$pagination_config['num_tag_close'] = 		'</' . $tag->attr['num_tag'] . '>';
			}

			// Current page
			$cur_page = (in_array($this->pagination_uri, $this->ci->uri_segment)) ? array_pop(array_slice($this->ci->uri_segment, -1)) : 1;

			// Pagination tag config init
			$pagination_config = array_merge($pagination_config,
				array
				(
					'base_url' => $base_url,
					'per_page' => $per_page,
					'total_rows' => $tag->locals->page['nb_articles'],
					'num_links' => 3,
					'cur_page' => $cur_page,
					'first_link' => lang('first_link'),			// "First" text : see /theme/your_theme/language/xx/pagination_lang.php
					'last_link' => lang('last_link'),			// "Last" text
					'next_link' => lang('next_link'),
					'prev_link' => lang('prev_link')
				)
			);

			// Pagination initialization
			$this->ci->pagination->initialize($pagination_config); 

			// Create the links
			$tag->locals->page['pagination_links'] = $this->ci->pagination->create_links();

			return isset($tag->locals->page['pagination_links']) ? $tag->locals->page['pagination_links'] : '' ;
		}
	}

	
	// ------------------------------------------------------------------------


	/*
	 * Page tags
	 * 
	 * 
	 */	
	public static function tag_page_id($tag) { return self::enclose($tag, $tag->locals->page['id_page']); }
	
	public static function tag_page_name($tag) { return self::enclose($tag, $tag->locals->page['name']); }

	public static function tag_page_title($tag)	{ return self::enclose($tag, $tag->locals->page['title']); }

	public static function tag_page_url($tag)	{ return self::enclose($tag, $tag->locals->page['url']); }
	
	public static function tag_page_subtitle($tag) { return self::enclose($tag, $tag->locals->page['subtitle']); }

	public function tag_page_meta_title($tag)
	{
		// Try to find an article with the name of the last part of the URL.
		$name = array_pop(array_slice($this->ci->uri_segment, -1));

		$article = array();

		if ( ! empty($name))
		{
			$where = array('name' => $name);
			$articles =  $this->ci->article_model->get_lang_list
			(
				$where, 
				Settings::get_lang()
			);
			$article = (! empty ($articles)) ? $articles[0] : array();
		}		

		if (! empty ($article['meta_title']))
			return self::enclose($tag, $article['meta_title']);
		else if ( ! empty ($tag->locals->page['meta_title']) )
			return self::enclose($tag, $tag->locals->page['meta_title']);
		else
		{
			if (!empty($tag->locals->page['title']))
			{
				return self::enclose($tag, $tag->locals->page['title']);		
			}
			return '';
		}
	}		




	public static function tag_page_date($tag) { return self::format_date($tag, $tag->locals->page['date']); }

	/**
	 * Returns the medias tag content
	 * 
	 * @return
	 * @attributes	range	Range of media to display. Starts at 0.
	 *						If only one number is provided, returns all the medias from this index 
	 *						if the attribute "num" is not set
	 * 						example of use : 	<ion:medias range="2,4" />
	 *											<ion:medias range="2" />
	 *
	 *				num		Number of pictures to display.
	 *						Combined to the "range" attribute, you can display x medias from a given start index.
	 * 						example of use : 	Display 2 first medias : 
	 *					 						<ion:medias num="2" />
	 *											Display 3 medias starting from index 2 :
	 *											<ion:medias range="2" num="3" />
	 *
	 *
	 */
	public static function tag_page_medias($tag)
	{
		$medias = ( ! empty($tag->locals->page['medias'])) ? $tag->locals->page['medias'] : false;
		
		if ( $medias !== false)
		{
			return self::get_medias($tag, $medias);	
		}
		return ;
	}


	// ------------------------------------------------------------------------


	public static function tag_page_content($tag)
	{
		$content = ( ! empty($tag->locals->page['content'])) ? $tag->locals->page['content'] : '';

		return $content;
	}


	/*
	 * Articles / Article tags
	 * 
	 * 
	 */


	/**
	 * Returns the articles tag content
	 * 
	 * @param	FTL_Binding object 
	 * @return 
	 */
	public function tag_articles($tag)
	{
		// Returned string
		$str = '';

		// Page from locals
		$pages =&  $tag->locals->pages;

		// Number of wished last article
		$num = (isset($tag->attr['num'])) ? $this->get_attribute($tag, 'num') : 0 ;
		if ($num == 0) $num = 0;

		// paragraph limit ?
		$paragraph = (isset($tag->attr['paragraph'] )) ? $this->get_attribute($tag, 'paragraph') : false ;

		// view
		$view = (isset($tag->attr['view']) ) ? $tag->attr['view'] : false;


		/* Get the articles
		 *
		 */
		$articles = $this->get_articles($tag);

		// Add data like URL to each article
		// and finally render each article
		if ( ! empty($articles))
		{
			foreach($articles as $key => $article)
			{
				// parent page : one must exists !
				$page = array_values(array_filter($tag->locals->pages, create_function('$row','return $row["id_page"] == "'. $article['id_page'] .'";')));
				$page = $page[0];
				
				// Force the view if the "view" attribute is defined
				if ($view !== false)
				{	
					$articles[$key]['view'] = $view;
				}
	
				// Set the correct URL to the article
				$articles[$key]['url'] = 		base_url() . $page['url'] . '/' . $article['url'];			
				$articles[$key]['lang_url'] = 	base_url() . Settings::get_lang('current') . '/' . $page['url'] . '/' . $article['url'];
	
				// Limit to x paragraph if the attribute is set
				if ($paragraph !== false)
					$articles[$key]['content'] = tag_limiter($article['content'], 'p', $paragraph);

				// Autolink the content
				$articles[$key]['content'] = 	auto_link($articles[$key]['content'], 'both', true);			
			}

			// Set the articles
			$tag->locals->page['articles'] = $articles;
	
			foreach($tag->locals->page['articles'] as $article)
			{
				// Render the article
				$tag->locals->article = $article;
				$str .= $tag->expand();
			}
		}
		
//		$str = $tag->parse_as_nested($str);
		
		return self::enclose($tag, $str);		
	}

	
	// ------------------------------------------------------------------------


	/**
	 * Returns the article tag content
	 * To be used inside an "articles" tag
	 * 
	 * @param	FTL_Binding object
	 * @return 
	 */
	public static function tag_articles_article($tag)
	{
		// View : Overwrite each defined article view by the passed one
		// It is possible to bypass the Article view by set it to ''
		$view = (isset($tag->attr['view'] )) ? $tag->attr['view'] : false ;
		
		// Kind of article : Get only the article linked to the given view
		$type = (isset($tag->attr['type'] )) ? $tag->attr['type'] : false ;
		
		// paragraph limit ?
		$paragraph = (isset($tag->attr['paragraph'] )) ? $tag->attr['paragraph'] : false ;

		if ( ! empty($tag->locals->article))
		{
			// Current article (set by tag_articles() )
			$article = &$tag->locals->article;

			/*
			 * Article View
			 * If no view : First, try to get the pages defined article_list view
			 *				Second, get the pages defined article view
			 *				Else, get the default view
			 */
			if ($view === false)
			{
				// The article defined view
				$view = $article['view'];

				// If article has no defined view : view to 0, nothing or false
				if ( $view == false || $view == '')
				{				
					// Fisrt and second step : The page defined views for articles
					// Need to be discussed...
					$view = $tag->globals->page['article_view'] ? $tag->globals->page['article_view'] : $tag->globals->page['article_list_view'];
				}
			}
			
			// Paragraph limiter
			if ($paragraph !== false)
			{
				$article['content'] = tag_limiter($article['content'], 'p', $paragraph);
			}

			// View rendering
			if (empty($view))
			{
				return $tag->expand();
			}
			else
			{
				if ( ! file_exists(Theme::get_theme_path().'views/'.$view.EXT))
				{
					show_error('TagManager_Page Error : <b>Cannot find view file "'.Theme::get_theme_path().'views/'.$view.EXT.'".');
				}

				return $tag->parse_as_nested(file_get_contents(Theme::get_theme_path().'views/'.$view.EXT));
			}
		}
		return '';
	}

	
	public static function tag_article_id($tag) { return self::enclose($tag, $tag->locals->article['id_article']); }
	public static function tag_article_name($tag) { return self::enclose($tag, $tag->locals->article['name']); }
	public static function tag_article_title($tag) { return self::enclose($tag, $tag->locals->article['title']); }
	public static function tag_article_subtitle($tag) { return self::enclose($tag, $tag->locals->article['subtitle']); }
	public static function tag_article_date($tag) { return self::enclose($tag, self::format_date($tag, $tag->locals->article['date'])); }
	public static function tag_article_meta_title($tag) { return self::enclose($tag, $tag->locals->article['meta_title']); }

	/**
	 * Returns the article content
	 *
	 */
	public function tag_article_content($tag)
	{
		// paragraph limit ?
		$paragraph = (isset($tag->attr['paragraph'] )) ? (Int)$this->get_attribute($tag, 'paragraph') : false ;

		$content = $tag->locals->article['content'];

		// Limit to x paragraph if the attribute is set
		if ($paragraph !== false)
			$content = tag_limiter($content, 'p', $paragraph);

		return self::enclose($tag, $content);
	}


	/**
	 * Returns the URL of the article, based or not on the lang
	 * If only one language is online, this tag will return the URL without the lang code
	 * To returns the lag code if you have only one language, set the "lang" attribute to true
	 * If the link or the article is set, this tag will return the link instead of the URL to the article.
	 *
	 */
	public static function tag_article_url($tag) 
	{
		$url = '';
		
		// If lang attribute is set to true, force the lang code to be in the URL
		// Usefull only if the website has only one language
		$lang_url = (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true' ) ? true : false;

		// If link, return the link
		if ($tag->locals->article['link'] != '' )
		{
			// External link
			if ($tag->locals->article['link_type'] == '')
			{
				return $tag->locals->article['link'];
			}
			// If article link, get the page to build the complete link
			if($tag->locals->article['link_type'] == 'article')
			{
				// Get the article's parent page
				$parent_page = array_values(array_filter($con->globals->pages, create_function('$row','return $row["id_page"] == "'. $tag->locals->article['id_page'] .'";')));
				
				$url = (!empty($parent_page[0])) ? $parent_page[0]['url'] . '/' . $tag->locals->article['link'] : '';
			}
			// This is a link to one page
			else
			{
				$url = $tag->locals->article['link'];
			}

			$lang_code = '';
			// Only returns the URL containing the lang code when languages > 1 or atribute lang set to true
			if (count(Settings::get_online_languages()) > 1 OR $lang_url === true)
			{
				$lang_code = Settings::get_lang() . '/';
			}
			
			return base_url().$lang_code.$url;

		}

		// Only returns the URL containing the lang code when languages > 1 or atribute lang set to true
		if (count(Settings::get_online_languages()) > 1 OR $lang_url === true)
		{
			$url = $tag->locals->article['lang_url'];
		}
		else
		{
			$url = $tag->locals->article['url'];
		}
		
		// Adds the suffix if defined in /application/config.php
		if ( config_item('url_suffix') != '' ) $url .= config_item('url_suffix');
		
		return $url;
	}


	/**
	 * @deprecated
	 * Only exists for compatibility mode.
	 * Use <ion:url lang="true" instead.
	 */
	public static function tag_article_lang_url($tag) { return $tag->locals->article['lang_url']; }
	
	public static function tag_article_view($tag) { return $tag->locals->article['view']; }


	/**
	 * Article medias tag definition
	 * Medias in one article context
	 *
	 */
	public static function tag_article_medias($tag)
	{
		$medias = $tag->locals->article['medias'];	
		return self::get_medias($tag, $medias);
	}


	public function tag_article_author_name($tag)
	{
		// Get the users if they're not defined
		if (!isset($tag->globals->users))
		{
			$this->ci->base_model->set_table('users');
			$tag->globals->users = $this->ci->base_model->get_list();
		}
		
		foreach($tag->globals->users as $user)
		{
			if ($user['username'] == $tag->locals->article['author'])
				return self::enclose($tag, $user['screen_name']);
		}

		return '';
	}


	public function tag_article_author_email($tag)
	{
		// Get the users if they're not defined
		if (!isset($tag->globals->users))
		{
			$this->ci->base_model->set_table('users');
			$tag->globals->users = $this->ci->base_model->get_list();
		}
		
		foreach($tag->globals->users as $user)
		{
			if ($user['username'] == $tag->locals->article['author'])
				return self::enclose($tag, $user['email']);
		}

		return '';
	}



	/**
	 * Returns HTML categories links enclosed by the given tag
	 *
	 * @TODO : 	Add the open and closing tag for each anchor.
	 *			Example : <li><a>... here is the anchor ... </a></li>
	 *
	 */
	public function tag_article_categories($tag)
	{
		$data = array();
		
		// HTML Separatorof each category
		$separator = ( ! empty($tag->attr['separator'])) ? $tag->attr['separator'] : ' | ';	
		
		// Make a link from each category or not. Default : true
		$link = ( ! empty($tag->attr['link']) && $tag->attr['link'] == 'false') ? false : true;	

		// Field to return for each category. "title" by default, but can be "name"
		$field =  ( ! empty($tag->attr['field'])) ? $tag->attr['field'] : 'title';

		// don't display the lang URL (by default)
		$lang_url = '';

		// Set all languages online if conected as editor or more
//		if( Connect()->is('editors', true))
//		{
//			Settings::set_all_languages_online();
//		}

		// If lang attribute is set to true, force the lang code to be in the URL
		// Usefull only if the website has only one language
		if (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true' )
		{
			$lang_url = true;
		}

		// Only returns the URL containing the lang code when languages > 1
		// or atribute lang set to true
		if (count(Settings::get_online_languages()) > 1 OR $lang_url === true)
		{
			$lang_url = Settings::get_lang().'/';
		}
		
		// Current page
		$page = $tag->locals->page;
	
			
		// Get the category URI segment from /config/ionize.php config file
		$this->uri_config = $this->ci->config->item('special_uri');

		$uri_config = array_flip($this->uri_config);

		$category_uri = $uri_config['category'];

		
		// Get the categories from current article
		$categories = $tag->locals->article['categories'];	

		// Build the anchor array
		if ($link == true)
		{
			foreach($categories as $category)
			{
				$data[] = anchor(base_url().$lang_url.$page['name'].'/'.$category_uri.'/'.$category['name'], $category[$field]);
			}
		}
		else
		{
			foreach($categories as $category)
			{
				$data[] = $category[$field];
			}
		}		
		
		return self::enclose($tag, implode($separator, $data));
	}


	public static function tag_article_readmore($tag)
	{
		$term = (isset($tag->attr['term']) ) ? $tag->attr['term'] : '';
		$paragraph = (isset($tag->attr['paragraph'] )) ? $tag->attr['paragraph'] : false ;


		if ( ! empty($tag->locals->article))
		{
			// Current article (set by tag_articles() )
			$article = &$tag->locals->article;
		
			$content = 	tag_limiter($article['content'], 'p', $paragraph);
			
			if (strlen($content) < strlen($article['content']))
			{
				return self::enclose($tag, '<a href="'.$article['url'].'">'.lang($term).'</a>'); 
			}
			else
			{
				return '';
			}
		}
		else
		{
			return '';
		}
	}




	// ------------------------------------------------------------------------


//	public static function tag_count($tag) { return $tag->locals->page['count']; }
//	public static function tag_period($tag) { return $tag->locals->page['period']; }

	
	// ------------------------------------------------------------------------


	/**
	 * Medias tags callback functions
	 *
	 */
	public static function tag_media_title($tag) {	return self::enclose($tag, $tag->locals->media['title']); }
	public static function tag_media_link($tag) { return self::enclose($tag, $tag->locals->media['link']); }
	public static function tag_media_alt($tag) {	return self::enclose($tag, $tag->locals->media['alt']); }
	public static function tag_media_file_name($tag) { return $tag->locals->media['file_name']; }
	public static function tag_media_base_path($tag) { return $tag->locals->media['base_path']; }
	public static function tag_media_id($tag) { return $tag->locals->media['id_media']; }
	public static function tag_media_path($tag) { return $tag->locals->media['path']; }
	public static function tag_media_description($tag) { return self::enclose($tag, $tag->locals->media['description']); }
	public static function tag_media_copyright($tag) { return self::enclose($tag, $tag->locals->media['copyright']); }

	
	// ------------------------------------------------------------------------


	/**
	 * Returns the media complete URL
	 * 
	 * @usage : <ion:src folder="<folder_name>" />
	 *			Physically, the folder is prefixed by "thumb_" if the folder is containing thumbs
	 *			The tag automatiquely adds the "thumb_" prefix to the folder name
	 *
	 */
	public static function tag_media_src($tag)
	{
		// thumb folder name (without the 'thumb_' prefix)
		$folder = (isset($tag->attr['folder']) ) ? 'thumb_' . $tag->attr['folder'] : false;

		$media = $tag->locals->media;

		if ( ! empty($media))
		{
			// Media source complete URL
			if ($folder !== false) 
				return base_url() . $media['base_path'] . $folder . '/' . $media['file_name'];
			else
				return base_url() . $media['path'];
		}
		return '';
	}


	/**
	 * Returns the media size
	 *
	 * @usage : <ion:size folder="medium" dim="width|height" />
	 *
	 */
	public static function tag_media_size($tag)
	{
		// thumb folder name (without the 'thumb_' prefix)
		$folder = (isset($tag->attr['folder']) ) ? 'thumb_' . $tag->attr['folder'] : false;

		$dim = (isset($tag->attr['dim']) ) ? $tag->attr['dim'] : false;

		$media = $tag->locals->media;

		if (isset($media['size']))
		{
			return $media['size'][$dim];
		}
		else
		{
			if ( ! empty($media))
			{
				// Media source complete URL
				if ($folder !== false) 
					$folder = base_url() . $media['base_path'] . $folder . '/' . $media['file_name'];
				else
					$folder = base_url() . $media['path'];
	
				// Get media size
				if ($d = @getimagesize($folder))
				{
					return ($dim == 'width') ? $d['0'] : $d['1'];
				}
			}
		}
		return '';
	}


	// ------------------------------------------------------------------------


	/**
	 * Navigation tags
	 *
	 */

	
	/**
	 * Navigation tag definition
	 * @usage	
	 *
	 */
	public function tag_navigation($tag)
	{
		// Final string to print out.
		$str = '';

		/*
		 * Infos from tags
		 *
		 */
		
		// Menu : Main menu by default
		$menu_name = isset($tag->attr['menu']) ? $tag->attr['menu'] : 'main';
		$id_menu = 1;
		foreach($tag->globals->menus as $menu)
		{
			if ($menu_name == $menu['name'])
			{
				$id_menu = $menu['id_menu'];
			}	
		}
		
		// Navigation level. 0 if not defined
		$asked_level = isset($tag->attr['level']) ? $tag->attr['level'] : 0;

		// Current page
		$current_page =& $tag->locals->page;

		// Attribute : active CSS class
		$active_class = (isset($tag->attr['active_class']) ) ? $tag->attr['active_class'] : 'active';
		if (strpos($active_class, 'class') !== FALSE) $active_class= str_replace('\'', '"', $active_class);
		

		// Attribute : view to use to display the navigation
		// A navigation view must be set
		$view = (isset($tag->attr['view']) ) ? $tag->attr['view'] : false;

		// Attribute : Use lang_url or url ?
		// $lang_url = (isset($tag->attr['lang_url']) && $tag->attr['lang_url'] === 'true') ? true : false ;

		/*
		 * Getting menu data
		 *
		 */
		// Page from locals
		$global_pages = $tag->globals->pages;

		// Filter by menu and asked level : We only need the asked level pages !
		$pages = array_filter($global_pages, create_function('$row','return ($row["level"] == "'. $asked_level .'" && $row["id_menu"] == "'. $id_menu .'") ;'));
	
		// Filter on 'appears'=>'1'
		$pages = array_values(array_filter($pages, array($this, '_filter_appearing_pages')));

		// Active pages array. Array of ID
		$id_current_page = ( ! empty($current_page['id_page'])) ? $current_page['id_page'] : false;
		$active_pages = Structure::get_active_pages($global_pages, $id_current_page);

		// Add the active class key
		foreach($global_pages as &$page)
		{
			// Add the active_class key
			$page['active_class'] = in_array($page['id_page'], $active_pages) ? $active_class : '';
		}
		
		// Get the parent page from one level upper
		$parent_page = array();
		if ($asked_level > 0)
		{
			$parent_pages = array_filter($global_pages, create_function('$row','return $row["level"] == "'. ($asked_level-1) .'";'));
			
			$parent_page = array_values(array_filter($parent_pages, create_function('$row','return $row["active_class"] != "";')));
			$parent_page = ( ! empty($parent_page)) ? $parent_page[0] : false;
		}
		
		// Filter the current level pages on the link with parent page
		if ( ! empty($parent_page ))
		{
			$pages = array_filter($pages, create_function('$row','return $row["id_parent"] == "'. $parent_page['id_page'] .'";'));
		}
		else
		{
			if ($asked_level > 0)
				$pages = array();
		}
		
		foreach($pages as $idx => $page)
		{
			// Add the active_class key
			$page['active_class'] = in_array($page['id_page'], $active_pages) ? $active_class : '';
		
			$tag->locals->page = $page;
			
			// Try to parse the tag with the given view
			if ($view !== false )
			{
				if ( ! file_exists(Theme::get_theme_path().'views/'.$view.EXT))
				{
					show_error('TagManager_Page Error : <b>Cannot find view file "'.Theme::get_theme_path().'views/'.$view.EXT.'".');
				}
				
				$str .= $tag->parse_as_nested(file_get_contents(Theme::get_theme_path().'views/'.$view.EXT));
			}
			// Expand the tag
			else
			{
				$str .= $tag->expand();
			}
		}

		return self::enclose($tag, $str);
	}


	// ------------------------------------------------------------------------


	/**
	 * Return a tree navigation based on the given helper.
	 *
	 * @param	FTL_Binding object
	 *
	 */
	public function tag_tree_navigation($tag)
	{
		// Current page
		$page = $tag->locals->page;
	
		// If 404 : Put empty vars, so the menu will prints out without errors
		if ( !isset($page['id_page']))
		{
			$page = array(
				'id_page' => '',
				'id_parent' => ''
			);
		}

		// Menu : Main menu by default
		$menu_name = isset($tag->attr['menu']) ? $tag->attr['menu'] : 'main';
		$id_menu = 1;
		foreach($tag->globals->menus as $menu)
		{
			if ($menu_name == $menu['name'])
			{
				$id_menu = $menu['id_menu'];
			}	
		}
		
		// If set, attribute level, else parent page level + 1
		$from_level = (isset($tag->attr['level']) ) ? $tag->attr['level'] :0 ;

		// If set, depth
		$depth = (isset($tag->attr['depth']) ) ? $tag->attr['depth'] : -1;
		
		// Attribute : active class
		$active_class = (isset($tag->attr['active_class']) ) ? $tag->attr['active_class'] : 'active';

		// Attribute : HTML Tree container ID & class attribute
		$id = (isset($tag->attr['id']) ) ? $tag->attr['id'] : NULL ;
		if (strpos($id, 'id') !== FALSE) $id= str_replace('\'', '"', $id);

		$class = (isset($tag->attr['class']) ) ? $tag->attr['class'] : NULL ;
		if (strpos($active_class, 'class') !== FALSE) $active_class= str_replace('\'', '"', $active_class);
		
		// Attribute : Use lang_url or url ?
		$lang_url = (isset($tag->attr['lang']) && $tag->attr['lang'] === 'true') ? true : false ;
		if ($lang_url == false)
			$lang_url = (isset($tag->attr['lang_url']) && $tag->attr['lang_url'] === 'true') ? true : false ;
		
		// Attribute : Helper to use to print out the tree navigation
		$helper = (isset($tag->attr['helper']) && $tag->attr['helper'] != '' ) ? $tag->attr['helper'] : 'navigation';

		// Get helper method
		$helper_function = (substr(strrchr($helper, ':'), 1 )) ? substr(strrchr($helper, ':'), 1 ) : 'get_tree_navigation';
		$helper = (strpos($helper, ':') !== false) ? substr($helper, 0, strpos($helper, ':')) : $helper;

		// load the helper
		$this->ci->load->helper($helper);

		// Page from locals : By ref because of active_class definition
		$pages =&  $tag->locals->pages;

		/* Get the reference parent page ID
		 * Note : this is depending on the whished level.
		 * If the curent page level > asked level, we need to find recursively the parent page which has the good level.
		 * This is done to avoid tree cut when navigation to a child page
		 *
		 * e.g :
		 *
		 * On the "services" page and each subpage, we want the tree navigation composed by the sub-pages of "services"
		 * We are in the page "offer"
		 * We have to find out that the level 1 parent is "services"
		 *
		 *	Page structure				Level
		 *
		 *	home						0
		 *	 |_ about					1		
		 *	 |_ services				1		<- We want all the nested nav starting at level 1 from this parent page
		 *	 	   |_ development		2
		 *		   |_ design			2
		 *				|_ offer		3		<- We are here.
		 *				|_ portfolio	3	
		 *
		 */
		$page_level = (isset($page['level'])) ? $page['level'] : 0;
	 
		$parent_page = array(
			'id_page' => ($from_level > 0) ? $page['id_page'] : 0,
			'id_parent' => isset($page['id_parent']) ? $page['id_parent'] : 0
		);

		// Find out the wished parent page 
		while ($page_level >= $from_level && $from_level > 0)
		{
			$potential_parent_page = array_values(array_filter($pages, create_function('$row','return $row["id_page"] == "'. $parent_page['id_parent'] .'";')));
			
			if (isset($potential_parent_page[0]))
			{
				$parent_page = $potential_parent_page[0];
				$page_level = $parent_page['level'];
			}
			else
			{
				$page_level--;
			}
		}
		// Active pages array. Array of ID
		$active_pages = Structure::get_active_pages($pages, $page['id_page']);
		
		foreach($pages as $key => $p)
		{
			$pages[$key]['active_class'] = in_array($p['id_page'], $active_pages) ? $active_class : '';
		}

		
		// Filter on 'appears'=>'1'
		$nav_pages = array_values(array_filter($pages, array($this, '_filter_appearing_pages')));
		$nav_pages = array_filter($nav_pages, create_function('$row','return ($row["id_menu"] == "'. $id_menu .'") ;'));

		// Get the tree navigation array
		$tree = Structure::get_tree_navigation($nav_pages, $parent_page['id_page'], $from_level, $depth);
		
		// Return the helper function
		if (function_exists($helper_function))
			return call_user_func($helper_function, $tree, $lang_url, $id, $class);
	}


	// ------------------------------------------------------------------------


	public static function tag_navigation_active_class($tag) { return isset($tag->locals->page['active_class']) ? $tag->locals->page['active_class'] : ''; }
	

	// ------------------------------------------------------------------------


	/** 
	 * Return the URL of a navigation menu item.
	 *
	 */
	public function tag_navigation_url($tag) 
	{
		// If lang attribute is set to true, force the lang code to be in the URL
		// Usefull only if the website has only one language
		$lang_url = (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true' ) ? true : false;
		
		if ($tag->locals->page['link'] != '' && $tag->locals->page['link_type'] == '')
		{
			return $tag->locals->page['absolute_url'];
		}
		
		/*
		 * In this case, the <ion:url /> tag of the <ion:navigation /> tag forces the lang code to be in the URL
		 * Because the function init_pages_urls() has already put the lang code, this check is only useful
		 * for internal link if the lang code isn't set by init_pages_urls()
		 *
		 */
		if ($lang_url === true)
		{
			if (strpos($tag->locals->page['absolute_url'], base_url().Settings::get_lang()) === FALSE)
			{
				$tag->locals->page['absolute_url'] = str_replace(base_url(), base_url().Settings::get_lang() . '/', $tag->locals->page['absolute_url']);
			}
			
			return $tag->locals->page['absolute_url'];
		}
		
		return $tag->locals->page['absolute_url'];
	}
	
	
	// ------------------------------------------------------------------------


	/**
	 * Get the archives tag
	 *
	 *
	 */
	function tag_archives($tag)
	{
		// Period format
		$format = (isset($tag->attr['format']) ) ? $tag->attr['format'] : 'F';

		// Attribute : active class
		$active_class = (isset($tag->attr['active_class']) ) ? $tag->attr['active_class'] : 'active';

		// filter
		$filter = (isset($tag->attr['filter']) ) ? $tag->attr['filter'] : false;

		// month
		$with_month = (isset($tag->attr['with_month']) && $tag->attr['with_month'] == 'true' ) ? true : false;

		// Current archive
		$current_archive = isset($this->ci->uri_segment[2]) ? $this->ci->uri_segment[2] : '' ;
		$current_archive .= isset($this->ci->uri_segment[3]) ? $this->ci->uri_segment[3] : '' ;

		// Get the archives infos		
		$archives = $this->ci->article_model->get_archives_list
		(
			array('id_page' => $tag->locals->page['id_page']), 
			Settings::get_lang(),
			$filter,
			$with_month
		);


		// Translated period array
		$month_formats = array('D', 'l', 'F', 'M');

		// Flip the URI config array to have the category index first
		$uri_config = array_flip($this->uri_config);

		foreach ($archives as &$row)
		{
			$year = 	substr($row['period'],0,4);
			$month = 	substr($row['period'],4);
			
			if ($month != '')
			{
				$month = (strlen($month) == 1) ? '0'.$month : $month;

				$timestamp = mktime(0, 0, 0, $month, 1, $year);
    
				// Get date in the wished format
				$period = (String) date($format, $timestamp);

				if (in_array($format, $month_formats))
					$period = lang(strtolower($period));

				$row['period'] = $period . ' ' . $year;
				$row['url'] = base_url() . $tag->locals->page['name'] . '/' . $uri_config['archives'] . '/' . $year . '/' . $month ;
				$row['lang_url'] = base_url() . Settings::get_lang() . '/' . $tag->locals->page['name'] . '/' .  $uri_config['archives'] . '/' . $year . '/' . $month ;
				$row['active_class'] = ($year.$month == $current_archive) ? $active_class : '';
			}
			else
			{
				$row['period'] = $year;
				$row['url'] = base_url() . $tag->locals->page['name'] . '/' . $uri_config['archives'] . '/' . $year;
				$row['lang_url'] = base_url() . Settings::get_lang() . '/' . $tag->locals->page['name'] . '/' .  $uri_config['archives'] . '/' . $year;
				$row['active_class'] = ($year == $current_archive) ? $active_class : '';
			}
		}


		// Tag expand
		$str = '';

		foreach($archives as $archive)
		{
			$tag->locals->archive = $archive;
			$str .= $tag->expand();
		}

		return $str;
	}


	// ------------------------------------------------------------------------


	/**
	 * Archives tags callback functions
	 *
	 */
	public static function tag_archives_url($tag) 
	{ 
		// with lang code in the URL ?
		$lang = (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true') ? true : false ;

		if (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true')
		{
			// Set all languages online if connected as editor or more
//			if( Connect()->is('editors', true))
//			{
//				Settings::set_all_languages_online();
//			}
			
			// Only returns the URL containing the lang code when languages > 1
			if (count(Settings::get_online_languages()) > 1)
			{
				return $tag->locals->archive['lang_url'];
			}
		}
		return $tag->locals->archive['url']; 
	}


	/** 
	 * Deprecated, will be deleted in the next version 
	 * Use tag_archives_url
	 * @deprecated
	 */
	public static function tag_archives_lang_url($tag) { return ($tag->locals->archive['lang_url'] != '' ) ? $tag->locals->archive['lang_url'] : '' ; }
	
	
	public static function tag_archives_period($tag) { return ($tag->locals->archive['period'] != '' ) ? $tag->locals->archive['period'] : '' ; }
	public static function tag_archives_nb($tag) { return ($tag->locals->archive['nb'] != '' ) ? $tag->locals->archive['nb'] : '' ; }
	public static function tag_archives_active_class($tag) { return ($tag->locals->archive['active_class'] != '' ) ? $tag->locals->archive['active_class'] : '' ; }
	

	// ------------------------------------------------------------------------

	
	/**
	 * Displays the breacrumb : You are here !!!
	 * 
	 * @param	FTL_Binding object 
	 * @return	String	The parsed view
	 * 
	 */
	public function tag_breadcrumb($tag)
	{
		/* Attributes : Tag open, ID and class of the tag
		 *
		 */
		$class = (isset($tag->attr['class'])) ? ' class="' . $tag->attr['class'] .'" ' : '';
		$id = (isset($tag->attr['id'])) ? ' id="' . $tag->attr['id'] .'" ' : '';

		$full_tag_open = (isset($tag->attr['full_tag'])) ? '<' . $tag->attr['full_tag'] . $id . $class . '>' : '';
		$full_tag_close = (isset($tag->attr['full_tag'])) ? '</' . $tag->attr['full_tag'] . '>' : '';
		
		$lang_url = (isset($tag->attr['lang_url']) && $tag->attr['lang_url'] == 'true') ? true : false;
		
		$separator = (isset($tag->attr['separator']) ) ? $tag->attr['separator'] : ' &raquo; ';
								
		// Current lang
		$lang = Settings::get_lang();
		
		// Current page ID
		$current_page_id = $tag->globals->page['id_page'];
		
		// Get the Breadcrumbs array
		$breacrumbs = $this->get_breadcrumb_array($tag->locals->page, $tag->locals->pages, $lang );
		
		// Filter appearing pages
		$breacrumbs = array_values(array_filter($breacrumbs, array($this, '_filter_appearing_pages')));
		
		// Build the links
		$return = '';
		for($i=0; $i<count($breacrumbs); $i++)
		{
			$url = ($lang_url == true) ? base_url().Settings::get_lang().'/'.$breacrumbs[$i]['name'] : base_url().$breacrumbs[$i]['name'] ;
			
			// Adds the suffix if defined
			if ( config_item('url_suffix') != '' ) $url .= config_item('url_suffix');

			
			$a = $full_tag_open . '<a href="'.$url.'">'.$breacrumbs[$i]['title'].'</a>';
			
			$return .= ($return != '') ? $separator : '';
			
			$return .= $a;
		}
		
		return $return;				
	}
	
	

	/**
	 * Returns the breadcrumb data
	 *
	 * @param	Array	The starting page
	 * @param	Array	All the pages
	 * @param	String	Current language code
	 *
	 *
	 * @return	Array	Array of pages name (in the current language)
	 *
	 */
	private function get_breadcrumb_array($page, $pages, $lang, $data = array())
	{
		$parent = NULL;
		
		if (isset($page['id_parent']) ) // && $page['id_parent'] != '0')
		{
			// Find the parent
			for($i=0; $i<count($pages) ; $i++)
			{
				if ($pages[$i]['id_page'] == $page['id_parent'])
				{
					$parent = $pages[$i];
					$data = $this->get_breadcrumb_array($parent, $pages, $lang, $data);
					break;
				}
			}
			
			$data[] = $page;
		}
		
		return $data;
	}
	

	// ------------------------------------------------------------------------

	
	/**
	 * Renders the <ion:articles /> last_article sub tag
	 *
	 */
	function tag_last_articles_article($tag)
	{
		return $this->tag_articles_article($tag);
	}


	// ------------------------------------------------------------------------

// HERE : Add pagination : Number of page displayed on category view !!!!
// Could not be possible as the pagination tag don't know this Category tag attribute value (per_page)


	/**
	 * Categories tag
	 * Get the categories list from within the current page or globally
	 *
	 *
	 */
	function tag_categories($tag)
	{
		// Categories model
		isset($this->ci->category_model) OR $this->ci->load->model('category_model', '', true);

		// Attribute : active class
		$active_class = (isset($tag->attr['active_class']) ) ? $tag->attr['active_class'] : 'active';

		// view ?
		$view = (isset($tag->attr['view']) ) ? $tag->attr['view'] : false;

		// from page name ?
		$from_page = (isset($tag->attr['from']) ) ? $tag->attr['from'] : false;

		// Current category possibly exists ?
		$current_category = isset($this->ci->uri_segment[2]) ? $this->ci->uri_segment[2] : NULL ;

		// All pages
		$pages =&  $tag->locals->pages;

		// Current page
		$page = $tag->locals->page;

		// If a page name is set, try to get it
		if ($from_page !== false)
		{
			// Get the asked page details
			$page = array_values(array_filter($pages, create_function('$row','return $row["name"] == "'. $from_page .'";')));
			
			// If not empty, filter articles on id_page
			if ( ! empty($page))
			{
				$page = $page[0];
			}
		}

		// Get categories from this page articles
		$categories = $this->ci->category_model->get_categories_from_pages($page['id_page'], Settings::get_lang());

		// Flip the URI config array to have the category index first
		$uri_config = array_flip($this->uri_config);
		
		// Add the URL to the category to each category row
		// Also add the active class		
		foreach($categories as $key => $category)
		{
			$categories[$key]['url'] = 			base_url() . $page['url'] . '/' . $uri_config['category'] . '/' . $category['name'];
			$categories[$key]['lang_url'] = 	base_url() . Settings::get_lang() . '/' . $page['url'] . '/' . $uri_config['category'] . '/' . $category['name'];
			$categories[$key]['active_class'] = ($category['name'] == $current_category) ? $active_class : '';
		}

		// Reorder array keys
		$categories = array_values($categories);

		// Tag expand
		$str = '';
		foreach($categories as $category)
		{
			$tag->locals->page = $category;
			$str .= $tag->expand();
		}

		// View rendering
		if (empty($view))
		{
			return $str;
		}
		else
		{
			return $tag->parse_as_nested(file_get_contents(Theme::get_theme_path().'views/'.$view.EXT));
		}
	}

	
	// ------------------------------------------------------------------------


	/**
	 * Categories tags callback functions
	 *
	 */
	public static function tag_categories_url($tag) 
	{ 
		// don't display the lang URL (by default)
		$lang_url = false;

		// Set all languages online if conected as editor or more
//		if( Connect()->is('editors', true))
//		{
//			Settings::set_all_languages_online();
//		}

		// If lang attribute is set to true, force the lang code to be in the URL
		// Usefull only if the website has only one language
		if (isset($tag->attr['lang']) && $tag->attr['lang'] == 'true' )
		{
			$lang_url = true;
		}

		// Only returns the URL containing the lang code when languages > 1
		// or atribute lang set to true
		if (count(Settings::get_online_languages()) > 1 OR $lang_url === true)
		{
			return $tag->locals->page['lang_url'];
		}
		
		return $tag->locals->page['url'];
	}




	/** 
	 * Deprecated, will be deleted in the next version 
	 * Use tag_categories_url()
	 * @deprecated
	 */
	public static function tag_categories_lang_url($tag) { return ($tag->locals->page['lang_url'] != '' ) ? $tag->locals->page['lang_url'] : '' ; }

	public static function tag_categories_active_class($tag) { return ($tag->locals->page['active_class'] != '' ) ? $tag->locals->page['active_class'] : '' ; }



	// ------------------------------------------------------------------------


	/**
	 * Filters page which should appear
	 * used by $this->tag_navigation()
	 *
	 */
	private static function _filter_appearing_pages($row)
	{
		return ($row['appears'] == 1);
	}


	// ------------------------------------------------------------------------


	private function _filter_pages_authorization($row)
	{
		// If the page group != 0, then get the page group and check the restriction
		if($row['id_group'] != 0)
		{
			$page_group = false;
			
			// Get the page group
			foreach($this->ci->connect->groups as $group)
			{
				if ($group['id_group'] == $row['id_group']) $page_group = $group;
			} 

			// If the current connected user has access to the page return true
			if ($this->ci->user !== false && $page_group != false && $this->ci->user['group']['level'] >= $page_group->level)
				return true;
			
			// If nothing found, return false
			return false;
		}
		return true;
	}

}


/* End of file Page.php */
/* Location: /application/libraries/Tagmanager/Page.php */