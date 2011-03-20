<?php
/* ----------------------------------------------------------------------------------------------
 *	Ionize - EzFileManager config file
 *	Date : 16.02.2009 - 17:46:28
 *	Partikule 2009 
 *	--------------------------------------------------------------------------------------------- */ 

// Users files path
$file_path =				"files/";
$picture_path =				"files/pictures/";


/** $base_url
 *	Base URL of the website
 */
$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);  
$base_url = substr($base_url, 0, strpos($base_url, 'javascript'));


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



?>