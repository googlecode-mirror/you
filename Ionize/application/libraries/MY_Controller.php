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
 * MY_Controller Class
 *
 * Extends CodeIgniter Controller
 * Basic Model loads and settings set.
 *
 */
class MY_Controller extends Controller 
{
	/* Template array
	 * This array will be send to the view in case of standard output
	 * Used by $this->output
	 */
	protected $template = array();

	/* Default FTL tag
	 */
	protected $context_tag = 'ion';

	/**
	 * Contructor
	 *
	 */
    public function __construct()
	{
		parent::__construct();

		// Check the database settings
		if ($this->test_database_config() === false)
		{
			redirect(base_url().'install/');
			die();
		}

		$this->load->database();

		// Models
		$this->load->model('base_model', '', true);
		$this->load->model('settings_model', '', true);

		// Helpers
		$this->load->helper('file');
		$this->load->helper('trace');
		
		/*
		 * Language / Languages
		 *
		 */
		// Get all the website languages from DB and store them into config file "languages" key
		$languages = $this->settings_model->get_languages();

		// Put DB languages array to Settings
		Settings::set_languages($languages);	


		if( Connect()->is('editors', true))
		{
			Settings::set_all_languages_online();
		}

		/*
		 * Settings
		 *
		 */
		// 	Lang independant settings : google analytics string, filemanager, etc.
		//	Each setting is accessible through : 
		//	Settings::get('setting_name');
		Settings::set_settings_from_list($this->settings_model->get_settings(), 'name','content');

		/*
		 * Security : No access if install folder is already there
		 *
		 */
//		if (config_item('protect_installer') == TRUE)
//		{
			// Try to find the installer class
			$installer = glob(BASEPATH.'../*/class/installer'.EXT);
	
			// If installer class is already here, avoid site access
			if (!empty($installer))
			{
				// Get languages codes from availables languages folder/translation file
				$languages = $this->settings_model->get_admin_langs();
				
				if ( ! in_array(config_item('language_abbr'), $languages))
					$this->config->set_item('language_abbr', config_item('default_lang'));
				
				$this->lang->load('admin', config_item('language_abbr'));
				
				Theme::set_theme('admin');
				
				// Set the view to output
				$this->output('delete_installer');
				
				// Display the view directly
				$this->output->_display();
				
				// Don't do anything more
				die();
			}
//		}
    }


	// ------------------------------------------------------------------------


    /**
     * Outputs the global template regarding to the used library to do this stuff
     *
     * @param	string	The view name
     *
     */
    public function output($view)
    {
    	Theme::output($view, $this->template);
    }


	// ------------------------------------------------------------------------


	/**
	 * Returns true if this is an XMLHttpRequest (ie. Javascript).
	 * 
	 * This requires a special header to be sent from the JS
	 * (usually the Javascript frameworks' Ajax/XHR methods add it automatically):
	 * 
	 * <code>
	 * X-Requested-With: XMLHttpRequest
	 * </code>
	 * 
	 * @return bool
	 */
	public function is_xhr()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}


	/**
	 * Returns true if database settings are correct
	 *
	 */
	public function test_database_config()
	{
		require(config_item('base_path') . '/application/config/database.php');
				
		if ($db[$active_group]['hostname'] == '' || $db[$active_group]['username'] == '' || $db[$active_group]['database'] == '')
		{
			return false;
		}
		return true;
	}
	
	public function get_modules_config()
	{
		// Modules config include
		$config_files = glob(config_item('module_path').'*/config/config.php');

		if ( ! empty($config_files))
		{
			// Add each module config element to the main config 
			foreach($config_files as $file)
			{
				include($file);
				
				if ( isset($config))
				{
					foreach($config as $k=>$v)
						$this->config->set_item($k, $v);
	
					unset($config);
				}
			}
		}
	}
} 
// End MY_Controller


// ------------------------------------------------------------------------


class Base_Controller extends MY_Controller
{
	/**
	 * Constructor
	 *
	 */		
    public function __construct()
    {
        parent::__construct();

// $this->output->enable_profiler(true);
//		$this->output->cache(100);
		
		// Unlock filtering if admin or editor users is logged in
//		$this->load->library('connect');

		$this->connect = Connect::get_instance();


		// Libraries
		$this->load->library('structure');	
		$this->load->library('widget');

		// FTL parser
		require_once APPPATH.'libraries/ftl/parser.php';

		// Models
		$this->load->model('structure_model', '', true);
		$this->load->model('menu_model', '', true);

		// Modules config
		$this->get_modules_config();


		/*
		 * Theme
		 *
		 */
		// Set the current theme
		Theme::set_theme(Settings::get('theme'));

		// Theme config file
		// Overwrite Ionize standard config.
		if (is_file($file = Theme::get_theme_path().'config/config.php'))
		{
			include($file);
			if ( ! empty($config))
			{
				foreach($config as $k=>$v)
					$this->config->set_item($k, $v);

				unset($config);
			}
		}

		/*
		 * Menus
		 *
		 */
		Settings::set('menus', $this->menu_model->get_list());
		  

		/*
		 * Language
		 *
		 */
		// Get all the website languages from DB and store them into config file "languages" key
		$languages = $this->settings_model->get_languages();

		// Put all DB languages array to Settings
		Settings::set_languages($languages);	

		// Set all languages online if conected as editor or more
		if( Connect()->is('editors', true))
		{
			Settings::set_all_languages_online();
		}

		
		// Simple languages code array, used to detect if Routers found language is in DB languages
		$lang_codes = array();
		foreach(Settings::get_online_languages() as $language)
		{
			$online_lang_codes[] = $language['lang'];
		}


		// If Router detected that the lang code is not in DB languages, set it to the DB default one
		if ( ! in_array(config_item('language_abbr'), $online_lang_codes))
		{
			// Settings::get_lang('default') returns the DB default lang code
			Settings::set('current_lang', Settings::get_lang('default'));
			
			$this->config->set_item('language_abbr', Settings::get_lang('default'));
		}
		else
		{
			// Store the current lang code (found by Router) to Settings
			Settings::set('current_lang', config_item('language_abbr'));		
		}

		// Lang dependant settings for the current language : Meta, etc.
		Settings::set_settings_from_list($this->settings_model->get_lang_settings(config_item('language_abbr')), 'name','content');


		/*
		 * Static language
		 *
		 */
		$lang_folder = Theme::get_theme_path().'language/'.Settings::get_lang().'/';

		// Core languages files : Including
		$lang_files = glob(APPPATH.'language/'.Settings::get_lang().'/*_lang.php');

		// Theme languages files : Including. Can be empty
		$lf = glob(Theme::get_theme_path().'language/'.Settings::get_lang().'/*_lang.php');
		if ( !empty($lf))
			$lang_files = array_merge((Array)$lang_files, $lf);
		
		// Modules languages files : Including. Can be empty
		$mla = glob(config_item('module_path').'*/language/'.Settings::get_lang().'/*_lang.php');
		if ( !empty($mla))
			$lang_files = array_merge((Array)$lang_files, $mla);

		$modules = glob(config_item('module_path').'*');


		// Add the module path to the Finder
		if (!empty($modules))
		{
			foreach ($modules as $module)
			{
				Finder::add_path($module.'/');
			}
		}
		
		// Widgets languages translations loading
		// Now done by the Widget library


		// Load all modules lang files
		if ( ! empty($lang_files))
		{
			foreach($lang_files as $l)
			{
				if (is_file($l) && '.'.end(explode('.', $l)) == EXT )
				{
					include $l;
					if ( ! empty($lang))
					{
						$this->lang->language = array_merge($this->lang->language, $lang);
						unset($lang);
					}
				}
			}
		}
	}


	protected function parse($string, $context, $tag_prefix = 'ion')
	{
		$p = new FTL_Parser($context, array('tag_prefix' => $tag_prefix));

		return $p->parse($string);
	}


	protected function render($view, &$context = null, $return = false)
	{
		// Loads the view to parse
		$parsed = Theme::load($view);

		// We can now check if the file is a PHP one or a FTL one
		if (substr($parsed, 0, 5) == '<?php')
		{
			$parsed = $this->load->view($view, array(), true);					
		}
		else
		{
			$parsed = $this->parse($parsed, $context);
		}
		
		// Returns the result or output it directly
		if ($return)
			return $parsed;
		else
			$this->output->set_output($parsed);
	}
}



// ------------------------------------------------------------------------



/**
 * MY_Admin Class
 *
 * Extends MY_Controller
 *
 */
class MY_Admin extends MY_Controller
{
	/* Response message type
	 * Used by controller to send answer to request
	 * can be 'error', 'notice', 'success'
	 *
	 */
	public $message_type = '';
	
	/* Response message to the user
	 * Human understandable message
	 *
	 */
	public $message = '';
	
	/* Array of HTMLDomElement to update with corresponding update URL
	 * Array (
	 *		'htmlDomElement' => 'controller/method/'
	 * );
	 *
	 */
	public $update = array();
	
	/* Current element ID
	 *
	 */
	public $id;
	
	/* Javascript callback function 
	 * Not implemented yet
	 *
	 */
	public $callback;


	/**
	 * Constructor
	 *
	 */		
    public function __construct()
    {
        parent::__construct();

		// Redirect the not authorized user to the login panel. See /application/config/connect.php
		Connect()->restrict_type_redirect = array(
					'uri' => '/admin/user/login',
					'flash_msg' => 'You have been denied access to %s',
					'flash_use_lang' => false,
					'flash_var' => 'error');

//	$this->output->enable_profiler(true);

		// PHP standard session is mandatory for MCE FileManager authentication
		// and other external lib
		session_start();
		$_SESSION['isLoggedIn'] = true;

		// Librairies
		$this->connect = Connect::get_instance();
		
		// Current user		
		$this->template['current_user'] = $this->connect->get_current_user();

		// Set the admin theme as current theme
		Theme::set_theme('admin');
		
		
		/*
		 * Admin languages : Depending on installed translations in /application/languages/
		 * The Admin translations are only stored in the translation file /application/languages/xx/admin_lang.php
		 *
		 */
		// Set admin lang codes array
		Settings::set('admin_languages', $this->settings_model->get_admin_langs());
		
		// Set Router's found language code as current language
		Settings::set('current_lang', config_item('language_abbr'));

		// Load the current language translations file
		$this->lang->load('admin', Settings::get_lang());


		/*
		 * Modules config
		 *
		 */
		$this->get_modules_config();

		// Including all modules languages files
		// $lang_files = glob(config_item('module_path').'*/language/'.Settings::get_lang().'/*');
		
		
		
		
		
		
		// Load all modules lang files
/*
Look how to make Modules translations available in javascript 
Notice : $yhis->lang object is transmitted to JS through load->view('javascript_lang')
 
		if ( ! empty($lang_files))
		{
			foreach($lang_files as $l)
			{
				if (is_file($l))
				{
//					$logical_name = substr($l, strripos($l, '/') +1);
//					$logical_name = str_replace('_lang.php', '', $logical_name);
//					$this->lang->load($logical_name, Settings::get_lang());

					include $l;
					$this->lang->language = array_merge($this->lang->language, $lang);
					unset($lang);

				}
			}
		}
*/

	
		/*
		 * Settings
		 *
		 */
		


		// @TODO : Remove this thing from the global CMS. Not more mandatory, but necessary for compatibility with historical version
		// Available menus
		// Each menu was a root node in which you can put several pages, wich are composing a menu.
		// Was never really implemented in ionize historical version, but already used as : menus[0]...
		Settings::set('menus', config_item('menus'));

		
		// Don't want to cache this content
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");
    }
    

	// ------------------------------------------------------------------------


	/**
	 * Sets an error message and call the response method
	 * 
	 * @param	string		Message to the user
	 * @param	array		Additional data to put to the answer. Optional.
	 *
	 */
    public function error($message, $addon_data = null)
    {
    	$this->message_type = 'error';
    	$this->message = $message;
    	
    	if ( !isset($this->redirect) )
    	{
    		$this->redirect = $_SERVER['HTTP_REFERER'];
    	}
    	
    	$this->response($addon_data);

		exit();
    }

   
	// ------------------------------------------------------------------------


	/**
	 * Sets a success message and call the response method
	 * 
	 * @param	string		Message to the user
	 * @param	array		Additional data to put to the answer. Optional.
	 *
	 */
    public function success($message, $addon_data = null)
    {
    	$this->message_type = 'success';
    	$this->message = $message;
    	
    	$this->response($addon_data);
    }


	// ------------------------------------------------------------------------


	/**
	 * Sets a notice message and call the response method
	 * 
	 * @param	string		Message to the user
	 * @param	array		Additional data to put to the answer. Optional.
	 *
	 */
    public function notice($message, $addon_data = null)
    {
    	$this->message_type = 'notice';
    	$this->message = $message;
    	
    	$this->response($addon_data);
    }


	// ------------------------------------------------------------------------


    /**
     * Send an answer to the browser depending on the incoming request
     * If the request cames from XHR, sends a JSON object as response
     * else, check if redirect is defined and redirect
     *
     * @param	array	Additional data to put to the answer. Optional.
     *
     */
    public function response($addon_data = null)
    {
    	/* XHR request : JSON answer
    	 * Sends a JSON javascript object
    	 *  
    	 */
    	if ($this->is_xhr() === true)
    	{
			// Basic JSON answser
    		$data = array (    	
				'message_type' => $this->message_type,
				'message' => $this->message,
				'update' => $this->update,
				'callback' => $this->callback
			);
			
			// Puts additional data to answer
			if ( ! empty($addon_data))
			{
				$data = array_merge($data, $addon_data);
			}
			
			// Adds element ID if isset
			if (isset($this->id) )
			{
				$data['id'] = $this->id;
			}
			echo json_encode($data);
    	}
    }
}


/**
 * Base Admin Module Class
 *
 * All modules Admin class must extend this class
 *
 * @author	Martin Wernstahl
 *
 */

abstract class Module_Admin extends MY_Admin
{
	protected $parent;
	
	/**
	 * Constructor
	 *
	 * @param	CI object	The CI object ($this)
	 *
	 */
	final public function __construct(Controller $c)
	{
		$this->parent = $c;
		$this->construct();
	}
	
	/**
	 * The deported construct function
	 * Should be called instead of parent::__construct by inherited classes
	 *
	 */
	abstract protected function construct();
	

	// ------------------------------------------------------------------------


	public function __get($prop)
	{
		if(property_exists($this->parent, $prop))
		{
			return $this->parent->$prop;
		}
		else
		{
			throw new Exception('Missing property');
		}
	}
	

	// ------------------------------------------------------------------------


	public function __call($method, $param)
	{
	 	if(method_exists($this->parent, $method))
	 	{
	 		return call_user_func_array(array($this->parent, $method), $param);
	 	}
	 	else
	 	{
	 		throw new BadMethodCallException(get_class($this).'::'.$method);
	 	}
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns a simple view
	 * Available from each module
	 *
	 */
	public function get($view = false)
	{
		$args = func_get_args();
		$args = implode('/', $args);

		$this->output($args);
	}
}


/* End of file MY_Controller.php */
/* Location: ./application/libraries/MY_Controller.php */