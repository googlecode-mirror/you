<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.9.4
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS - Dashboard Class
 *
 * Prints out the dashboard
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Ionize Dev Team
 */
class Dashboard extends MY_Admin {


	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('page_model', '', true);
		$this->load->model('article_model', '', true);
		$this->load->model('users_model', '', true);
	}


	function index()
	{
		// Last articles
		$articles = $this->article_model->get_lang_list(false, Settings::get_lang('default'), '5', FALSE, 'updated DESC');

		// Orphan articles
		$orphan_articles = $this->article_model->get_lang_list(array('id_page' => '0'), Settings::get_lang('default'), false, false, 'name ASC');
		
		// Orphan pages
		$orphan_pages = $this->page_model->get_lang_list(array('id_menu' => '0'), Settings::get_lang('default'), false, false, 'name ASC');
		
		// All pages
		$pdb = $this->page_model->get_list();
		$pages = array();
		foreach($pdb as $p)
		{
			$pages[$p['id_page']] = $p;
		}
		
		// Last connected users
		$users = array_filter($this->connect->model->get_users(), array($this, '_filter_users'));
		
		
		// Updates on articles
		foreach($articles as & $article)
		{
			// User name update
			foreach($users as $user)
			{
				if($user['username'] == $article['updater']) $article['updater'] = $user['screen_name'];
				if($user['username'] == $article['author']) $article['author'] = $user['screen_name'];
			}
			
			// Page Name ?
			$article['page_name'] = (! empty($pages[$article['id_page']]['name'])) ? $pages[$article['id_page']]['name'] : '';
			
		}

		// Updates on phantom pages
		foreach($orphan_pages as & $page)
		{
			// User name update
			foreach($users as $user)
			{
				if($user['username'] == $page['updater']) $page['updater'] = $user['screen_name'];
				if($user['username'] == $page['author']) $page['author'] = $user['screen_name'];
			}
		}
		
		
		// Updates on phantom articles
		foreach($orphan_articles as & $article)
		{
			// User name update
			foreach($users as $user)
			{
				if($user['username'] == $article['updater']) $article['updater'] = $user['screen_name'];
				if($user['username'] == $article['author']) $article['author'] = $user['screen_name'];
			}
		}
		
		
		$this->template['articles'] = $articles;
		$this->template['orphan_pages'] = $orphan_pages;
		$this->template['orphan_articles'] = $orphan_articles;
		$this->template['users'] = $users;	
		
		$this->output('dashboard');		
	}


	// ------------------------------------------------------------------------


	/**
	 * Users filter callback function
	 * Returns only users from Editors to Super-Admin groups
	 *
	 */
	function _filter_users($row)
	{
		return ($row['group']['level'] > 999) ? true : false; 
	}

}
/* End of file dashboard.php */
/* Location: ./application/admin/controllers/dashboard.php */