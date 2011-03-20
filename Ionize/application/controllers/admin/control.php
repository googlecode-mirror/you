<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Ionize, creative CMS Control Class
 *
 * This class controls 
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Controllers
 * @author		Ionize Dev Team
 */
class Control extends MY_Admin {


	public function __construct()
	{
		parent::__construct();
	}


	function index()
	{
		// No access to index
	}
	
	
	/** Check if data already exists in Database
	 *  Warning : Ajax use only !
	 *
	 *  @param	$table	table name
	 *	@param	$field	fieldname to check
	 *	@param	$value	Value to check
	 *	@return	true if data are found, false if nothing found
	 */
	function isExisting($table, $field, $value, $url_title=false)
	{
		$this->load->model('m_control', '', true);

		if ($this->m_control->isExisting($table, $field, $value, $url_title))
		{
			echo 'yes';
		}
		else
		{
			echo 'no';
		}
	}
	
	
}

/* End of file control.php */
/* Location: ./application/admin/controllers/control.php */