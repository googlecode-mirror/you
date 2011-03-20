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
 * Ionize Theme Class
 *
 * This class creates the mocha based desktop
 *
 * @package		Ionize
 * @subpackage	Librairies
 * @category	Librairies
 * @author		Ionize Dev Team
 */
 
class Theme {

	
	private static $theme_base_path = 'themes/';	// Themes base folder. All themes are stored in this folder in their own folder
	
	private static $theme = '';				// Current theme folder.


	/** 
	 * Sets the theme
	 *
	 * @access	public
	 * @param	string	The theme folder
	 */ 
	public static function set_theme($t)
	{
		self::$theme = $t;
		
		// Add current theme path to Finder searching path	
		array_unshift(Finder::$paths, self::get_theme_path());
	}
	

	// ------------------------------------------------------------------------

	
	/**
	 * Returns the theme name
	 *
	 */
	public static function get_theme()
	{
		return self::$theme;		
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns the complete path to the theme
	 *
	 */
	public static function get_theme_path()
	{
		return self::$theme_base_path.self::$theme.'/';		
	}
	

	/**
	 * Loads a view as a string
	 * Used by Base_Controller->render() method to load a view
	 *
	 * @param	string	View name to load
	 * @param	sring	Directory where is the view
	 *
	 * @return	string	The load view
	 *
	 */
	public static function load($name, $directory = 'views')
	{
		$file = Finder::find_file($name, $directory, true);
		
		if(empty($file))
		{
			show_error('Theme error : <b>The file "'.$directory.'/'.$name.'" cannot be found.</b>');
		}
		
		$string = file_get_contents(array_shift($file));
		
		return $string;
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Outputs one view
	 *
	 * @access	public
	 * @param	string	Name of the view
	 * @param	array	View's data array
	 *
	 */
	public function output($view, $data)
	{
		$ci =  &get_instance();

		// Loads the view
		$output = $ci->load->view($view, $data, true);

		// Set character encoding
		$this->output->set_header("Content-Type: text/html; charset=UTF-8");

		$ci->output->set_output($output);
	}

}


/* End of file Theme.php */
/* Location: ./application/libraries/Theme.php */