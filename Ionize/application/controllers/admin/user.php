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
 * Ionize User Controller
 *
 * Used to login / logout an user
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	User
 * @author		Ionize Dev Team
 *
 */

class User extends My_Admin 
{
	
	/**
	 * Constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();
		
		// Reset the restrict array for this contrustor to avoid the loop
		$this->connect->folder_protection = array();
//		Connect()->folder_protection = array();
	}


	// ------------------------------------------------------------------------

	/**
	 * Default
	 *
	 */
	function index()
	{
        // By default, the controller will send the user to the login screen
		$this->login();
	}


	// ------------------------------------------------------------------------


	/**
	 * Logs one user on the admin panel
	 *
	 */
	function login()
	{
		// If the user is already logged and if he is in the correct minimum group, go to Admin
		if($this->connect->logged_in() && $this->connect->is('editors', true))
		{
			redirect(site_url('admin'));
		}

		if( ! empty($_POST))
		{
			unset($_POST['submit']);

			if($this->_try_validate_login())
			{
                // Syntax talks from itself, isn't it? :)
                // The login method will check for a 'remember_me' value
                // If found it will remember the user until he log out.
                // Remember time is specified time in the access config file (default is 7 days)
				try
				{
					$this->connect->login($_POST);

					redirect(site_url('admin'));
				}
				catch(Exception $e)
				{
					$this->login_errors = $e->getMessage();
				}
			}
			else
			{
				$this->login_errors = "Something's wrong appears....";
			}
		}

		$this->output('access/login');
	}


	// ------------------------------------------------------------------------


	/**
	 * Logout and redirect to the welcome controller.
	 *
	 */
	function logout()
	{
		// Delete the session
		session_unset('isLoggedIn');	
		session_destroy();
		
		unset($_SESSION);

        // Here is also the right place to set a flash message or send
        // a screen message to the user if you use the redirect feature.
    	$this->connect->logout('admin');
	}


	// ------------------------------------------------------------------------


	/**
	 * Try to validate the user login form
	 *
	 */
	function _try_validate_login()
	{
        $this->load->library('form_validation');

        $rules = array(
	               array(
	                     'field'   => 'username',
	                     'label'   => 'Username',
	                     'rules'   => 'trim|required|min_length[4]|xss_clean'
	                  ),
	               array(
	                     'field'   => 'password',
	                     'label'   => 'Password',
	                     'rules'   => 'trim|required|min_length[4]|xss_clean'
	                  )
	            );

		$this->form_validation->set_rules($rules);

		return ($this->form_validation->run() === true);
	}
}

/* End of file user.php */
/* Location: ./application/controllers/admin/user.php */