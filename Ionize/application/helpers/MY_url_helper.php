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
 * Ionize URL Helpers
 *
 * @package		Ionize
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Ionize Dev Team
 *
 */


// ------------------------------------------------------------------------


/**
 * Variant of site_url(), but adds the module name - if any.
 *
 * @param  string|array
 * @return string
 */
if( ! function_exists('module_url'))
{
	function module_url($str = '')
	{
		global $RTR;

		// no module:
		if( ! $RTR->fetch_module_uri_seg())
		{
			return site_url($str);
		}

		// module, add segment:
		if( ! is_array($str))
		{
			return site_url($RTR->fetch_module_uri_seg() . '/' . $str);
		}
		else
		{
			return site_url(array_merge(array($RTR->fetch_module_uri_seg()), $str));
		}
	}
}


// ------------------------------------------------------------------------


/**
 * Variant of anchor() which takes the module into account - if any.
 *
 * @param  string|array
 * @param  string
 * @param  string|array
 * @return string
 */
if( ! function_exists('module_anchor'))
{
	function module_anchor($uri = '', $title = '', $attributes = '')
	{
		global $RTR;

		$title = (string) $title;

		if ( ! is_array($uri))
		{
			$site_url = ( ! preg_match('!^\w+://! i', $uri)) ? module_url($uri) : $uri;
		}
		else
		{
			$site_url = site_url($uri);
		}

		if ($title == '')
		{
			$title = $site_url;
		}

		if ($attributes != '')
		{
			$attributes = _parse_attributes($attributes);
		}

		return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
	}
}


// ------------------------------------------------------------------------


/**
 * Variant of redirect() which takes the module into account - if any.
 *
 * @param  string|array
 * @param  string
 * @param  int
 * @return string
 */
if( ! function_exists('module_redirect'))
{
	function module_redirect($uri = '', $method = 'location', $http_response_code = 302)
	{
		if ( ! preg_match('#^https?://#i', $uri))
		{
			$uri = module_url($uri);
		}

		// call "parent"
		redirect($uri, $method, $http_response_code);
	}
}


// ------------------------------------------------------------------------


/**
 * Creates an url to the specified uri for the specified language key.
 * 
 * @param  string
 * @param  string
 * @return string
 */
if( ! function_exists('lang_url'))
{
	function lang_url($lang_key, $uri = '')
	{
		global $LANG;
		
		return $LANG->lang_url($lang_key, $uri);
	}
}


// ------------------------------------------------------------------------


/**
 * Create URL Title compatible with all latin characters
 *
 * Takes a "title" string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the separator: dash, or underscore
 * @return	string
 */
if( ! function_exists('url_title'))
{
	function url_title($str, $separator = 'dash')
	{
		if($separator == 'underscore')
		{
			$separator = '_';
		}
		else
		{
			$separator = '-';
		}
	
		$charset 	= config_item('charset');
		$str 		= strtolower(htmlentities($str, ENT_COMPAT, $charset));
		$str 		= preg_replace('/&(.)(acute|cedil|circ|lig|grave|ring|tilde|uml);/', "$1", $str);
		$str 		= preg_replace('/([^a-z0-9]+)/', $separator, html_entity_decode($str, ENT_COMPAT, $charset));
		$str 		= trim($str, $separator);
	
		return $str;
	}
}


// ------------------------------------------------------------------------


/**
* Alternative languages helper
*
* Returns a string with links to the content in alternative languages
*
* version 0.2
* @author Luis <luis@piezas.org.es>
* @modified by Ionut <contact@quasiperfect.eu>
*/
if( ! function_exists('alt_site_url'))
{
	function alt_site_url($uri = '')
	{
	    $CI =& get_instance();
	//    $actual_lang=$CI->uri->segment(1);
	    
		global $RTR;
		$original_route = explode('/', $RTR->uri->_parse_request_uri());
		$actual_lang = ( ! isset($original_route[1]) )  ? false : $original_route[1];
	    
	    $languages=$CI->config->item('languages');
	    $languages_useimg=$CI->config->item('lang_useimg');
	    $ignore_lang=$CI->config->item('lang_ignore');
	    if (empty($actual_lang))
	    {
	        $uri=$ignore_lang.$CI->uri->uri_string();
	        $actual_lang=$ignore_lang;
	    }
	    else
	    {
	        if (!array_key_exists($actual_lang,$languages))
	        {
	            $uri=$ignore_lang.$CI->uri->uri_string();
	            $actual_lang=$ignore_lang;
	        }
	        else
	        {
	            $uri=$CI->uri->uri_string();
	            $uri=substr_replace($uri,'',0,1);
	        }
	    }
	
	
	    $alt_url='<ul>';
	    //i use ul because for me formating a list from css is easy
	    foreach ($languages as $lang=>$lang_desc)
	    {
	         if ($actual_lang!=$lang)
	         {
	            $alt_url.='<li><a href="'.config_item('base_url');
	            if ($lang==$ignore_lang)
	            {
	                $new_uri=ereg_replace('^'.$actual_lang,'',$uri);
	                $new_uri=substr_replace($new_uri,'',0,1);
	            }
	            else
	            {
	                $new_uri=ereg_replace('^'.$actual_lang,$lang,$uri);
	            }
	            $alt_url.=$new_uri.'">';
	            if ($languages_useimg){
	                //change the path on u'r needs
	                //in images u need to have for example en.gif and so on for every   
	                //language u use
	                //the language description will be used as alternative
	                $alt_url.= '<img src="'.base_url().'images/'.$lang.'.gif" alt="'.$lang_desc.'"></a></li>';
	            }
	            else
	            {
	                $alt_url.= $lang_desc.'</a></li>';
	            }
	         }
	    }
	    $alt_url.='</ul>';
	    return $alt_url;
	}
}


// ------------------------------------------------------------------------


/**
 * Auto-linker
 * 
 * Corrected so it takes URLs without space before (begining of line, for example).
 *
 * Adds the subject attribute in email.
 * Example : mailto:my.name@domain.tld?subject='My subject' will be linked correctly
 *
 * Automatically links URL and Email addresses.
 * Note: There's a bit of extra code here to deal with
 * URLs or emails that end in a period.  We'll strip these
 * off and add them after the link.
 *
 * @access	public
 * @param	string	the string
 * @param	string	the type: email, url, or both
 * @param	bool 	whether to create pop-up links
 * @return	string
 *
 */

function auto_link($str, $type = 'both', $popup = FALSE)
{
	if ($type != 'email')
	{
		if (preg_match_all("#(^|\s|\(|>)((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches))
		{
			$pop = ($popup == TRUE) ? " target=\"_blank\" " : "";

			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$period = '';
				if (preg_match("|\.$|", $matches['6'][$i]))
				{
					$period = '.';
					$matches['6'][$i] = substr($matches['6'][$i], 0, -1);
				}
	
				$str = str_replace($matches['0'][$i],
									$matches['1'][$i].'<a href="http'.
									$matches['4'][$i].'://'.
									$matches['5'][$i].
									$matches['6'][$i].'"'.$pop.'>http'.
									$matches['4'][$i].'://'.
									$matches['5'][$i].
									$matches['6'][$i].'</a>'.
									$period, $str);
			}
		}

	}

	if ($type != 'url')
	{
		if (preg_match_all("#([a-zA-Z0-9_\.\-\+]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)((\?subject\=)(\')(.*)(\'))*#i", $str, $matches))
		{
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				$period = '';
				if (preg_match("|\.$|", $matches['3'][$i]))
				{
					$period = '.';
					$matches['3'][$i] = substr($matches['3'][$i], 0, -1);
				}
				
				$comp_email = $matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i].$matches['5'][$i].$matches['7'][$i];
				$email = $matches['1'][$i].'@'.$matches['2'][$i].'.'.$matches['3'][$i];
	
				$str = str_replace($matches['0'][$i], safe_mailto($comp_email, $email).$period, $str);
			}
		}
	}

	return $str;
}




/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */