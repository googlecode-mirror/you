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
 * Ionize, creative CMS Core Class
 *
 * This class provides Ionizes basics functionalities
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Ionize Dev Team
 */
class Core extends MY_Admin {


	public function __construct()
	{
		parent::__construct();

		$this->connect->restrict('editors');
	}


	function index()
	{
        // By default, the controller will send the user to the login screen
		$this->login();
	}
	
	
	/**
	 * Gets the website current structure and ouput it as a tree
	 *
	 */
	function get_structure()
	{
		// Structure model
		$this->load->model('menu_model', '', true);
		$this->load->model('structure_model', '', true);
		
		// Structure librairy
		$this->load->library('structure');

		// Article model
		$this->load->model('article_model', '', true);

		// Get all articles from DB
		$articles = $this->structure_model->get_articles();

		// Menus : All menus
		$menus = $this->menu_model->get_list($where=false, 'ordering ASC');

		foreach($menus as &$menu)
		{
			$menu['items'] = array();
			$menu_items = $this->structure_model->get($menu['id_menu']);

			$this->structure->get_nested_structure($menu_items, $menu['items'], 0,	0, -1, $articles);
		}
		
		$this->template['menus'] = $menus;
		
		$this->output('structure');
	}
	

	/**
	 * Get main informations about settings
	 * Used during developement
	 *
	 *
	 */
	function get_info()
	{
		$this->output('info');
	}


	/** 
	 * Gets a simple view
	 * @param	string		the view name, without extension
	 *
	 */
	function get($view = false)
	{
		// Adds optional args to template
		//$this->template['args'] = $args;
		
		$this->template['view'] = $view;
		
		$args = func_get_args();
		$args = implode('/', $args);

		$this->output($args);
	}
	
}

/* End of file core.php */
/* Location: ./application/admin/controllers/core.php */