<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Module path
|--------------------------------------------------------------------------
| Placed here to not modify the CI index.php original file.
| Do not change this config setting.
|
*/
$base_path = str_replace("\\", "/", realpath(dirname(BASEPATH)));

$modules_folder = $base_path.'/modules';

define('MODPATH', $modules_folder.'/');


/*
|--------------------------------------------------------------------------
| Ionize Version
|--------------------------------------------------------------------------
|
*/
$config['version'] = '0.9.5 rev.7';


/*
|--------------------------------------------------------------------------
| Ionize Base path
|--------------------------------------------------------------------------
|
| Complete PATH to the website.
| WITHOUT trailing slash:
|
|	E:/Server_web_folder/your_site_folder
|
*/
$config['base_path'] =		str_replace("\\", "/", realpath(dirname(BASEPATH)));


/*
|--------------------------------------------------------------------------
| Ionize Installer protection
|--------------------------------------------------------------------------
|
| NOTICE : Possible security hole, so deactivated.
|
| Protects the website from beeing view until the /intall folder is deleted.
| Default to true.
|
| You can change this value once the install folder is deleted.
|
| Be careful : With this value to FALSE, the installer AND the website can be reachable
| at the same time !
|
|
*/
// $config['protect_installer'] = FALSE;


/* 
|--------------------------------------------------------------------------
| Available filemanagers
|--------------------------------------------------------------------------
| Javascript filemanagers.
| Must be useable with tinyMCE and idealy in standalone mode.
| In standalone mode, the filemanager is used by /javascript/ionizeMediaManager.js (addMedia method).
| 2 filemanagers are currently supported :
| - filemanager :		Moxiecode MceFilemanager / ImageManager (licensed module, not provided with ionize)
| - tinyBrowser :		http://www.lunarvis.com/
|
| A third filemanager implementation is in study : 
| - ezFilemanager :		http://www.webnaz.net/ezfilemanager/
|
| All the filemanagers must be put in the directory :
| /javascript/tinymce/jscripts/tiny_mce/plugins
|
| If you wish to add another one, look at /javascript/ionizeMediaManager.js to the methods : 
| - toggleFileManager()
| - toggleImageManager
*/
$config['filemanagers'] = array('tinybrowser', 'filemanager');


/*
|--------------------------------------------------------------------------
| Ionize Default Language code
|--------------------------------------------------------------------------
|
| Nothing to do with CI default language : This is the really default language code
| used by Ionize when no other is available.
| For example, if user use a swedish browser with only swedish and has set 'oz' as default DB language,
| the admin section can only use this very default language code 
|
*/

$config['default_lang'] = "en";


/*
|--------------------------------------------------------------------------
| Ionize Special URI definition
|--------------------------------------------------------------------------
|
| Special URI setup
| Usee this array to define which URI segment to use for special URIs
| These URI are used for dedicated function like 
| - getting articles by category,
| - limit the number of displayed articles on one page (pagination)
| _ Getting articles by time period (acrhives)
|
| Array ( 'user_choosen_uri' => 'internal_uri' );
|
| Notice : Don't change the 'internal_uri' on standard functionnalities without knowing what you do ! 
*/
$config['special_uri'] = array(	'category' => 'category',
								'page' => 'pagination',
								'archive' => 'archives',
								'post' => 'one_article'
							  );


/**
|--------------------------------------------------------------------------
| Antispam key
| Wil be written by JS in one hidden field of the form
| If not present when form post : spam
|--------------------------------------------------------------------------
|
*/
$config['form_antispam_key'] = "yourAntiSpamKey_ShouldContainsNumbersAndChars";






/* End of file ionize.php */
/* Location: ./application/config/ionize.php */