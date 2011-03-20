<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.90
 *
 */


// ------------------------------------------------------------------------


/**
 * Ionize Navigation Helpers
 *
 * @package		Ionize
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Ionize Dev Team
 *
 */


// ------------------------------------------------------------------------


/**
 * Returns a HTML UL formatted nested tree from a pages nested array
 * Used by controllers/page::get_tree_navigation() to print out a nested navigation
 *
 * @param	Array		Array of pages
 * @param	Array		Array of container UL (first one) attributes. Can contains 'id' and 'class'
 *
 * @return	String		HTML UL formatted string
 *
 */
if( ! function_exists('get_tree_navigation'))
{
	function get_tree_navigation($items, $lang_url=false, $id=NULL, $class=NULL)
	{
		// HTML Attributes
		$id = ( ! is_null($id) ) ? ' id="' . $id . '" ' : '';
		$class = ( ! is_null($class) ) ? ' class="' . $class . '" ' : '';
	
		$tree = '<ul' . $id . $class . '>';
		
		foreach($items as $key => $page)
		{

			$url = ($lang_url !== false) ? base_url() . Settings::get_lang(). '/' . $page['url'] : base_url() . $page['url'];

			// Adds the suffix if defined in /application/config.php
			if ( config_item('url_suffix') != '' ) $url .= config_item('url_suffix');

			$tree .= '<li><a class="' . $page['active_class'] . '" href="' . $url . '">'.$page['title']. '</a>';
	
			if (!empty($page['children']))
				 $tree .= get_tree_navigation($page['children'], $lang_url);
			
			$tree .= '</li>';
			
		}
		
		$tree .= '</ul>';
		
		return $tree;
	}
}


/**
 * Returns a HTML UL formatted nested tree from a pages nested array
 * Used by controllers/page::get_tree_navigation() to print out a nested navigation
 *
 * @param	Array		Array of pages
 * @param	Array		Array of container UL (first one) attributes. Can contains 'id' and 'class'
 *
 * @return	String		HTML UL formatted string
 *
 */
if( ! function_exists('get_nested_navigation'))
{
	function get_nested_navigation($items, $lang_url=false, $id=NULL, $class=NULL)
	{
		// HTML Attributes
		$id = ( ! is_null($id) ) ? ' id="' . $id . '" ' : '';
		$class = ( ! is_null($class) ) ? ' class="' . $class . '" ' : '';
	
		$tree = '<ul' . $id . $class . '>';
		
		foreach($items as $key => $page)
		{
			$subclass = '';
			
			$url = ($lang_url !== false) ? base_url() . Settings::get_lang(). '/' . $page['name'] : base_url() . $page['name'];

			// Adds the suffix if defined in /application/config.php
			if ( config_item('url_suffix') != '' ) $url .= config_item('url_suffix');

	
			$tree .= '<li>
						<span class="background"></span>
						<a class="' . $page['active_class'] . '" href="' . $url . '">'
							.$page['title'].
							'<span class="subtitle">'. $page['subtitle'] .'</span>
						</a>';
	
			if (!empty($page['children']))
				 $tree .= get_nested_navigation($page['children'], $lang_url);
			
			$tree .= '</li>';
			
		}
		
		$tree .= '</ul>';
		
		return $tree;
	}
}



/* End of file navigation_helper.php */
/* Location: .application/helpers/navigation_helper.php */