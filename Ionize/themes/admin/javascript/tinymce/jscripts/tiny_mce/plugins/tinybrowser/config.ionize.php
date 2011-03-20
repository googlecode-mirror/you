<?php
/* ----------------------------------------------------------------------------------------------
 *	Ionize - TinyBrowser config file
 *	Last update : 10.03.2010
 *	Partikule Studio 2010 
 *	--------------------------------------------------------------------------------------------- */ 



/** $base_url
 *	Base URL of the website
 */
$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);  
$base_url = substr($base_url, 0, strpos($base_url, 'themes'));


/** $site_path
 *	Base path of the website
 */
if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE)
{
	$site_path = realpath(pathinfo(__FILE__, PATHINFO_DIRNAME));
}
else 
{
	$site_path = pathinfo(__FILE__, PATHINFO_DIRNAME);
}
$site_path = substr($site_path, 0, strpos($site_path, 'themes'));


/** Files base folder
 */
$file_path = "files/";
if (is_file($site_path.'application/config/medias.php'))
{
	include_once($site_path.'application/config/medias.php');
	if ($config['files_path'] != '' )
	{
		$file_path = $config['files_path'];
	}
}


/** tinyBrowser path and URL
 *	Don't forget to uncomment these config items in config_tinybrowser.php
 */
$tinybrowser['docroot'] = 		$site_path;

$tinybrowser['path']['image'] = $file_path; 		// Image files location - also creates a 'thumb' subdirectory within this path to hold the image thumbnails
$tinybrowser['path']['media'] = $file_path; 		// Media files location
$tinybrowser['path']['file']  = $file_path; 		// Other files location

$tinybrowser['link']['image'] = $base_url.$tinybrowser['path']['image']; // Image links
$tinybrowser['link']['media'] = $base_url.$tinybrowser['path']['media']; // Media links
$tinybrowser['link']['file']  = $base_url.$tinybrowser['path']['file']; // Other file links

$tinybrowser['thumbdir'] = 'thumb';
$tinybrowser['show_ionize_thumb_dir'] = false;

?>
