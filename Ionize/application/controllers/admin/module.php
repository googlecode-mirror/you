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
 * Ionize, creative CMS Category Controller
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Module management
 * @author		Ionize Dev Team
 *
 */

class Module extends MY_admin 
{
	
	public $modules_folder = 'modules';


	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

//		$this->connect->restrict('editors');
	}


	// ------------------------------------------------------------------------


	/**
	 * Loads a module controller
	 * Receives the module name and proccess the URI
	 *
	 *
	 *
	 */
	function _remap($module_name)
	{
		// Delete the segments before the module name
		$mod_uri = array_slice($this->uri->segments, 3);
		
		// Get the controller, the called func name and the args
		$module_controller = $mod_uri[0];
		$module_func = $mod_uri[1];
		
		$module_args = array_slice($mod_uri, 2);

		// Module path
		$module_path = $this->modules_folder.'/'.ucfirst($module_name).'/';
		
		// Add the module path to the finder
		array_unshift(Finder::$paths, $module_path);

		// Includes the module Class file
		include($module_path.'controllers/admin/'.$module_controller.EXT);

		// Create an instance of the module controller
		$obj = new $module_controller($this);
		
		// Loads module language file, if exists
		if (is_file($module_path.'language/'.$this->config->item('language_abbr').'/'.$module_name.'_lang.php'))
			$this->lang->load($module_name, $this->config->item('language_abbr'));
		else
		{
			trace('warning: no language file for this module in : '. $module_path.'language/'.$this->config->item('language_abbr').'/');
		}

		call_user_func_array(array($obj, $module_func), $module_args);

	}

}


/* End of file module.php */
/* Location: ./application/controllers/admin/module.php */