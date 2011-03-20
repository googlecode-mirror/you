<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Version extends MY_Admin
{
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Version()
	{
		parent::MY_Admin();
	}

	// --------------------------------------------------------------------

	/**
	 * Controller Default Function
	 *
	 * @access	public
	 *
	function index()
	{
		$data['new_version'] = $this->_get_latest();

		$this->template->display('admin/main', $data);
	}

	// --------------------------------------------------------------------

	/**
	 * Get the latest version of the application
	 *
	 * Grabs the Assembla version document, reads out the version number and
	 * returns the version if it's newer.
	 *
	 * @access	private
	 * @return	mixed	new version or false
	 * @see		http://www.assembla.com/wiki/show/breakoutdocs/Document_REST_API
	 *
	function _get_latest()
	{
		// Path to latest version file
		$uri = $this->config->item('version_check_uri');

		// Connection options
		$params = array(
		 				'http'	=>	array('timeout'	=>	1)
						);

		// Create stream context and open the stream
		$context = stream_context_create($params);
		$latest = @file_get_contents($uri, 0, $context);

		if ($latest)
		{
			return (version_compare(APP_VERSION, $latest) == -1) ? $latest : FALSE;
		}

		return FALSE;
	}
	
	*/
}

/* End of file admin.php */
/* Location: ./application/controllers/admin/version.php */