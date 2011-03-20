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
 * Ionize, creative CMS - Desktop Class
 *
 * This class creates the mocha based desktop
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Ionize Dev Team
 */
class Desktop extends MY_Admin {


	public $modules_folder = 'modules';


	public function __construct()
	{
		parent::__construct();
	}


	function index()
	{
		// Get the modules config file
		include APPPATH . 'config/modules.php';

		// Get all modules config files in modules folder
		$config_files = glob($this->modules_folder . '/*/config.xml');

		// Module data to put to template
		$moddata = array();
		
		// Get all modules from folders
		if (!empty($config_files))
		{
			foreach($config_files as $file)
			{
				$xml = simplexml_load_file($file);
				
				// Module folder
				preg_match('/\/([^\/]*)\/config.xml$/i', $file, $matches);
				$folder = $matches[1];
	
				$uri = (String) $xml->uri_segment;
	
				// Only add 
				// - installed modules (in $module var of config/modules.php)
				// - module with admin part
				if (in_array($folder, $modules) && $xml->has_admin == 'true')
				{
					// Store data
					$moddata[$uri] = array(
							'name'			=> (String) $xml->name,
							'uri_segment'	=> (String) $xml->uri_segment,
							'description'	=> (String) $xml->description,
							'folder'		=> $folder,
							'file'			=> $file,
							'access_group'	=> (String) $xml->access_group
					);
	
					// Get the user segment
					foreach($modules as $segment => $f)
					{
						if ($f == $folder)
							$moddata[$uri]['uri_segment'] = $segment; 
					}
				}
			}
		}
				
		// Put installed module list to template
		$this->template['modules'] = $moddata;

		$this->get('desktop');
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

		$this->output($view);
	}
}

/* End of file desktop.php */
/* Location: ./application/admin/controllers/desktop.php */