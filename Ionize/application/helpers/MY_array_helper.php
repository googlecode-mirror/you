<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Ionize, creative CMS
 *
 * @package		Ionize
 * @author		Ionize Dev Team
 * @license		http://ionizecms.com/doc-license
 * @link		http://ionizecms.com
 * @since		Version 0.92
 *
 */


/**
*  Sorts an array (you know the kind) by key
 * and by the comparison operator you prefer.
 * Note that instead of most important criteron first, it's
 * least important criterion first.
 * The default sort order is ascending, and the default sort
 * type is strnatcmp.
 * 
 * @param array		The array to sort
 *
 * Example of usage : multisort($a, "'name'", true, 0, "'id'", false, 2));
 */
if ( ! function_exists('multisort'))
{
	function multisort(&$array)
	{
		for($i = 1; $i < func_num_args(); $i += 3)
		{
			$key = func_get_arg($i);
		   
			$order = true;
			if($i + 1 < func_num_args())
				$order = func_get_arg($i + 1);
		   
			$type = 0;
			if($i + 2 < func_num_args())
				$type = func_get_arg($i + 2);
	
			switch($type)
			{
				case 1: // Case insensitive natural.
					$t = 'strnatcasecmp($a[' . $key . '], $b[' . $key . '])';
					break;
				case 2: // Numeric.
					$t = '$a[' . $key . '] - $b[' . $key . ']';
					break;
				case 3: // Case sensitive string.
					$t = 'strcmp($a[' . $key . '], $b[' . $key . '])';
					break;
				case 4: // Case insensitive string.
					$t = 'strcasecmp($a[' . $key . '], $b[' . $key . '])';
					break;
				default: // Case sensitive natural.
					$t = 'strnatcmp($a[' . $key . '], $b[' . $key . '])';
					break;
			}
	
			uasort($array, create_function('$a, $b', 'return ' . ($order ? '' : '-') . '(' . $t . ');'));
		}
	
		return $array;
	}		
}

/* End of file MY_array_helper.php */
/* Location: ./application/helpers/MY_array_helper.php */

