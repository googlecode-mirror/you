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
 * Ionize Category Controller
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Translation files management
 * @author		Ionize Dev Team
 *
 */

class Translation extends MY_admin 
{

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

//		$this->connect->restrict('editors');
	}


	// ------------------------------------------------------------------------


	/**
	 * Shows standard settings
	 *
	 */
	function index()
	{
	
		$this->template['views_terms'] = $this->_get_terms_from_views();
		sort($this->template['views_terms']['term'], SORT_STRING );

		// Get the already translated items from languages files
		$translated_items = $this->_get_translated_items();

		foreach(Settings::get_languages() as $language)
		{
			$lang = $language['lang'];
			
			foreach($this->template['views_terms']['term'] as $term)
			{					
				// translation not defined : set to ''
				if(!isset($translated_items[$lang][$term]))
				{
					$translated_items[$lang][$term] = '';
				}
			}
		}
		
		$this->template['translated_items'] = $translated_items;
		
		unset($translated_items);
	
		$this->output('translation');
	}


	// ------------------------------------------------------------------------

	/**
	 * Saves the translation language files
	 *
	 */
	function save()
	{

		// Get the item to translate from views
		$items = $this->_get_items();

		// Tries to creates each language file
		foreach(Settings::get_languages() as $language)
		{
			$lang = $language['lang'];
		
			// Creates the lang folder if it doesn't exists
			$path = $this->config->item('base_path').'/themes/'.Settings::get('theme').'/language/'.$lang;
			
			if ( ! is_dir($path) )
			{
				// Try creating the dir
				try 
				{	
					@mkdir($path);
				}
				catch (Exception $e)
				{
					// Send error message to user
					$this->error(lang('ionize_message_language_dir_creation_fail'));
				}
			}
			
			// Saves the languages files
			$data  = "<?php\n\n";
			
			foreach ($items as $item)
			{
				$data .= "\$lang['".$item."'] = \"".addslashes(htmlentities($this->input->post($item.'_'.$lang, TRUE), ENT_QUOTES, 'UTF-8'))."\";\n"; 
			}
			
			$data .= "\n".'?'.'>';
			
			// Try writing the language file
			try 
			{
				write_file($path.'/'.Settings::get('theme').'_lang.php', $data);
			}
			catch (Exception $e)
			{
				$this->error(lang('ionize_message_language_file_creation_fail'));
			}			
		}
		
		// If method arrive her, everything was OK
		$this->success(lang('ionize_message_language_files_saved'));
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the array of items to translate
	 * 
	 * @return array	A simple array of unique items to translate, used for saving
	 *
	 */
	function _get_items()
	{
		// File helper
		$this->load->helper('file');

		// Theme views folder
		$path = $this->config->item('base_path').'/themes/'.Settings::get('theme').'/views';
		
		// Returned items array
		$items = array();

		if (is_dir($path))
		{
			$dir_iterator = new RecursiveDirectoryIterator($path);
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
	
			foreach ($iterator as $file)
			{
				if ($file->isFile() && (substr($file->getFilename(), 0, 1) != ".") )
				{
					$content = read_file($file->getPath() . '/' . $file->getFilename());
					
					/*
					if (preg_match_all('%<'.$this->context_tag.':translation term=\"([ \w:]+?)\" *\/>%', $content, $matches))
					{
						foreach($matches[1] as $term)
						{
							if (!in_array($term, $items))
							{
								$items[] = $term;
							}
						}
					}
					*/
					if (preg_match_all('% term=\"([ \w:]+?)\" *\/>%', $content, $matches))
					{
						foreach($matches[1] as $term)
						{
							if (!in_array($term, $items))
							{
								$items[] = $term;
							}
						}
					}
				}
			}
		}
		
		return $items;
	}
	
	

	// ------------------------------------------------------------------------


	/**
	 * Get the array of items to translate, per file
	 * Parses the current theme views
	 *
	 * @return	array	Items to translate, by view
	 * 					Array(
	 *						'terms' => terms to translate,
	 *						'views' => array(
	 *									term => views list
	 *						)
	 *					)
	 *
	 */
	function _get_terms_from_views()
	{
		// File helper
		$this->load->helper('file');

		// Theme views folder
		$path = $this->config->item('base_path').'/themes/'.Settings::get('theme').'/views';
		
		// Returned items array
		$items = array (
			'term' => array(),		// array of terms and their translations
			'views' => array()		// array of view in which each term appears, key : term
		);

		// Only do something if dir exists !
		if (is_dir($path))
		{
			// Recursive walk in the views folder
			$dir_iterator = new RecursiveDirectoryIterator($path);
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
	
			foreach ($iterator as $file)
			{
				if ($file->isFile() && (substr($file->getFilename(), 0, 1) != ".") )
				{
					$content = read_file($file->getPath() . '/' . $file->getFilename());
					
					// Check for each <ion:translation term="something" /> tag
					/*
					if (preg_match_all('%<'.$this->context_tag.':translation term=\"([ \w:]+?)\" *\/>%', $content, $matches))
					{
						foreach($matches[1] as $term)
						{
							// Add the view to the term view list
							if ( ! isset($items['views'][$term]) || ! in_array($file->getFilename(), $items['views'][$term]))
								$items['views'][$term][] = $file->getFilename();

							// Add the term to the term array
							if (!in_array($term, $items['term']))
								$items['term'][] = $term;
						}
					}
					*/
					// Check for each term="something" in tags
					if (preg_match_all('% term=\"([ \w:]+?)\" *\/>%', $content, $matches))
					{
						foreach($matches[1] as $term)
						{
							// Add the view to the term view list
							if ( ! isset($items['views'][$term]) || ! in_array($file->getFilename(), $items['views'][$term]))
								$items['views'][$term][] = $file->getFilename();

							// Add the term to the term array
							if (!in_array($term, $items['term']))
								$items['term'][] = $term;
						}
					}
				}
			}
	
			// Make a string list from 'views' array
			foreach ($items['views'] as $term => $views)
			{
				$items['views'][$term] = implode(', ', $views);
			}
		}
		
		return $items;
	}


	// ------------------------------------------------------------------------


	/**
	 * Gets already translated items from language files
	 *
	 * @return	array	Array of already translated terms
	 */
	function _get_translated_items()
	{
		$items = array();
	
		$this->load->helper('file');

		// Theme folder
		$path = $this->config->item('base_path').'/themes/'.Settings::get('theme');

		$result = array();

		// Read the template language directory
		foreach(Settings::get_languages() as $language)
		{
			$lang_code = $language['lang'];
			
			// Translation file name. look like [theme_name]_lang.php
			$file = $path.'/language/'.$lang_code.'/'.Settings::get('theme').'_lang.php';

			// Include the file if it exists
			if (file_exists($file))
			{			
				include($file);

				if (isset($lang))
				{
					$items[$lang_code] = $lang;
					
					unset($lang);
				}
			}				
		}
		return $items;
	}	

}

/* End of file translation.php */
/* Location: ./application/controllers/admin/translation.php */