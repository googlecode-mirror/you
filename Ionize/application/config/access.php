<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Users database table
|--------------------------------------------------------------------------
|
| Option:  string
| Default: 'users'
|
| This will be the name of the database table to store user login
| information in.
|
*/

$config['users_table'] = 'users';


/*
|--------------------------------------------------------------------------
| Groups database table
|--------------------------------------------------------------------------
|
| Option:  string
| Default: 'user_groups'
|
| This will be the name of the database table to store user groups in.
|
*/

$config['groups_table'] = 'user_groups';


/*
|--------------------------------------------------------------------------
| Activate user by email
|--------------------------------------------------------------------------
|
| Options: true / false
| Default: false
|
| true: the user will be put in the pending group.
|
| false: no verification will be done and the user will be able to
| Login directly after registration
|
*/
$config['verify_user'] = true;


/*
|--------------------------------------------------------------------------
| Activate user by Admin
|--------------------------------------------------------------------------
|
| Options: true / false
| Default: false
|
| true: the user can't activate himself but needs to be activated by an administrator.
|		overwrite the $config['verify_user'] value.
|
| false: the user don't need to be activated by admin.
|
*/
$config['activate_user_by_admin'] = true;


// If the previous is set to true, you'll need to complete also the following settings :
// @deprecated :
// Reason of deprecation : 
// The admin should be able to select which user can register or not.
// So the mail confirmation for auto-registration must be send by the controller
// in charge of registration, and not the Access lib.
$config['admin_email'] 		= '';	// Emails concerning the access system will be sent from this address
$config['admin_from_name'] 	= '';	// Emails will be sent with this name (can be set to false, then the email below will be used as "From name")
$config['reg_mail_subject'] = 'Your account information';	// Registration email subject  (Can be set to false, then "Your acount" will be used in each emails concerning the Access system)
$config['reg_site_name'] 	= 'Un-named Website';	// Site name that will be send to users in the registration emails


/*
|--------------------------------------------------------------------------
| Encryption key
|--------------------------------------------------------------------------
|
| Option: string
|
| This will be a string used by the encryption methods to calculate the
| unique encryption key used to encrypt the passwords of the users.
|
| You can type a long random string or generate it from this link:
| https://www.grc.com/passwords.htm
|
*/

$config['encrypt_key'] = 'asdfhhaUIHjhSIdhfshOIHOUFHUnHAO(dfyu(ASHFInADY&/%&#E?OI893749823wru';


/*
|--------------------------------------------------------------------------
| Salt length
|--------------------------------------------------------------------------
|
| Length of the user salt key used to hash its username
|
*/

$config['salt_length'] = '16';




/*
|--------------------------------------------------------------------------
| Default user group
|--------------------------------------------------------------------------
|
| Option:  string, a group slug
| Default: "users"
|
| This will be the group which the users will be assigned to after they
| have been activated.
| If the verify_user setting is set to false, the users will be assigned
| to this group after the registration has succeeded.
|
*/

$config['default_user_group'] = 'users';


/*
|--------------------------------------------------------------------------
| Automated folder protection
|--------------------------------------------------------------------------
|
| Option: array
|
| Drop the controllers you want to protect into the
| ./application/controllers/folder_to_protect/ folder.
|
| Multiple folders can be protected and individual access settings can be
| set for each of them.
|
| By default, the ./application/controllers/admin folder will be accessible
| only to administrators, and the ./application/controllers/protected
| folder only to users. You can change this in the configuration below.
|
| You can also add aditional folders or remove existing ones from the array
| using the key as the folder name and the value as the level of protection.
|
| The protection level is sent to Access::restrict(), so the syntax is the
| same as if you would call restrict() with the array value.
|
| The avaible levels are by default:
|  1. superadmins
|  2. admins
|  3. editors
|  4. users
|  5. pending
|  6. guests
|  7. banned
|  8. deactivated
|
| You can add more groups and change levels using the
| database table user_groups.
|
| If you don't want to use this feature, just leave the array empty:
| $config['folder_protection'] = array();
|
*/

$config['folder_protection'] = array(
									'admin' 	=> 'editors', 	// Will let only admins to access the admin folder
									'protected'	=> 'users' 		// Will let only users to access the protected folder
									);


/*
|--------------------------------------------------------------------------
| Redirect on login to resume browsing
|--------------------------------------------------------------------------
|
| Option:  bool
| Default: true
|
| This setting controls if the login() method of Access should redirect the
| user if a previous visit to a restricted page failed because he wasn't
| logged in.
|
| Eg.
| A user tries to access a protected page when he isn't logged in.
| Then he gets redirected to another page (configured in restrict_type_redirect,
| preferably with a login screen of some type).
| He logs in, after a successful login he is then redirected to the protected
| page he first tried to access.
|
| Note:
| This setting only works with $config['on_restrict'] = 'redirect'
|
*/

$config['login_redirect_to_blocked'] = true;


/*
|--------------------------------------------------------------------------
| Restrict type
|--------------------------------------------------------------------------
|
| Options: 'redirect', '404' and 'message'
| Default: 'redirect'
|
| This setting controls what should be done if the restrict() method
| denies a user access.
|
| 'redirect' - performs a redirect with the settings specified under
|              "Redirect settings"
| '404'      - shows a "404 page not found" message, which gives the user the
|              impression that the blocked page does not exist
| 'block'    - shows a custom message defined under "Deny acess message"
|              (equivalent to a 403 Forbidden error)
|
*/

$config['on_restrict'] = 'redirect';


/*
|--------------------------------------------------------------------------
| Redirect settings
|--------------------------------------------------------------------------
|
| Default: array('uri' => '', // root
| 				 'flash_msg' => 'You have been denyed access to %s',
| 				 'flash_use_lang' => false,
| 				 'flash_var' => 'error');
|
| This setting controls what should be done when the restrict() method
| redirects a denied user.
|
| Options:
|
| 'uri'            - The uri to redirect to
| 'flash_msg'      - The flash message to show to the user - if false, no
|                    message will be shown (will be sent through sprintf(),
|					 with the uri as the parameter)
| 'flash_use_lang' - If the flash message will be fetched using CI's
|                    Language class, if true the value of the key 'flash_msg'
|                    will be used as the line key
| 'flash_var'      - The flash variable to store the message in
|
*/

$config['restrict_type_redirect'] = array(
	'uri' => 'user/login',
	'flash_msg' => 'You have been denied access to %s',
	'flash_use_lang' => false,
	'flash_var' => 'error');


/*
|--------------------------------------------------------------------------
| Deny access message
|--------------------------------------------------------------------------
|
| Option:  array    keys: 'string', 'view' and 'lang'
| Default: 'string' => 'Access Denied'
|
| This setting controls what should be shown when the restrict() method
| denies a user access like a "403 Forbidden" page does.
|
| The key is the type of data: a string which just should be outputted,
| a view which should be rendered, or a lang string which should be ourputted.
| The value is: the string which will be shown, the view file to be loaded,
| or the lang key to load.
|
*/

$config['restrict_type_block'] = array('string' => 'Access Denied');


/*
|--------------------------------------------------------------------------
| Remember Me
|--------------------------------------------------------------------------
|
| Default: array(
|			  'on' => true, 
|			  'duration' => 604800, // 7 days
|			  'cookie_name' => 'somecookiename');
|
| This setting controls if the user should be able to have a remember me
| feature. Ie. not needing to login a second time within the timeframe
| configured here.
|
| Because this feature uses a cookie to remember the user, it can be
| tampered with (but highly unlikely, as it is encrypted and hashed - 
| but it can happen that the user can produce a replica), so it isn't
| recommended to be used on sites which need to be more secure.
|
| Oprions:
|
| on          - If this feature should be used, bool
| duration    - How long Access should remember the user, int - seconds
| cookie_name - The name of the cookie to save te data in, string
*/
$config['remember_me'] = array(
	'on' => true, 
	'duration' => 604800, // 7 days
	'cookie_name' => 'somecookiename');


/*
|--------------------------------------------------------------------------
| Login tracker
|--------------------------------------------------------------------------
|
| Option:  bool
| Default: true
|
| If login tracking should be used.
|
| The login tracker keeps track of the login attempts of the users and
| issues a time penalty if a user has failed with the login attempt.
| This time penalty depends on a polynomial function which factors in the
| time since the first attempt and the number of attempts. The more attempts
| the user has tried, the more severe the penalty.
| Usually, the time penalty does not affect the first 4 attempts.
|
*/

$config['enable_tracker'] = true;


/*
|--------------------------------------------------------------------------
| Tracker table
|--------------------------------------------------------------------------
|
| Option:  string
| Default: 'login_tracker'
|
| This will be the database table the tracker information will be stored in.
|
*/

$config['tracker_table'] = 'login_tracker';


/*
|--------------------------------------------------------------------------
| Blocking severeness and exponent
|--------------------------------------------------------------------------
|
| Option:  float
| Default: 1.0
| Default: 1.75
|
| This numbers adjusts the slope of the function which determines the
| time penalty and if a user will be blocked. The greater the number,
| the faster the user will be blocked, and the time penalty will also be more harsh.
| You can test different values in the demo application, to see how it will
| affect users and bots.
|
| Expression:
| f^e * s > t
|
| f = failures
| s = severeness
| e = exponent (>= 1)
| t = time since first attempt
|
| If the expression evaluates to true, the user is blocked
|
| To calculate how much time it is left to the next allowed login attempt:
| x = s * f^e - t - 1
|
| s = severness
| e = exponent
| t = time since first attempt
| f = failures
| x = time left
|
*/

$config['blocking_severeness']	= 1.0;
$config['blocking_exponent']	= 1.75;

/*
|--------------------------------------------------------------------------
| Login tracker cleaning probability
|--------------------------------------------------------------------------
|
| Option:  float, a percentage
| Default: 5 (%)
|
| The tracker keeps records in the database of the users.
| This will be the probability of the tracker cleaning the table of unused
| data.
|
*/

$config['tracker_cleaning_probability'] = 5; // %


/*
|--------------------------------------------------------------------------
| Tracker record life time
|--------------------------------------------------------------------------
|
| Option:  int, seconds
| Default: 86400 (24 h)
|
| When the tracker cleans the table, all records which have been created
| earlier than the number-of-seconds-set-here ago will be deleted.
|
*/

$config['tracker_clean_older_than'] = 86400;


/* End of file access.php */
/* Location: ./application/config/access.php */