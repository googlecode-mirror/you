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
 * @category	Lang management
 * @author		Ionize Dev Team
 *
 */

class Lang extends MY_admin
{

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model('lang_model', '', true);
	}


	// ------------------------------------------------------------------------


	/**
	 * Shows the existing languages
	 *
	 */
	function index()
	{

		$languages = $this->lang_model->get_list($where = false, $orderby = 'ordering ASC');

		$this->template['languages'] = $languages;

		foreach($languages as $lang)
		{
			$this->template['online_'.$lang['lang']] = $lang['online'];
		}

		$this->output('lang');
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves a new language
	 *
	 */
	function save()
	{
		if( $this->input->post('lang_new') != "" && $this->input->post('name_new') != "" )
		{
			$data = array(
						'lang' => $this->input->post('lang_new'),
						'name' => $this->input->post('name_new'),
						'online' => $this->input->post('online_new')
					);

			// Save to DB
			if ($this->lang_model->exists( array( 'lang' => $this->input->post('lang_new') ) ) )
			{
				$this->lang_model->update($this->input->post('lang_new'), $data);
			}
			else
			{
				$this->lang_model->insert($data);
				
				/* Insert in lang tables (page_lang, article_lang) the basic lang data for this new created lang
				 * see lang_model->insert_lang_data() for more info.
				 */
				$this->lang_model->insert_lang_data(array('page', 'article'), $fields = array('url'), $from = Settings::get_lang('default'), $to = $this->input->post('lang_new'));
			}
			
			// Update the language config file
			if ( false == $this->_update_config_file())
			{
				$this->error(lang('ionize_message_lang_file_not_saved'));
			}
	
			// UI panel to update after saving
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' => site_url('admin/lang')
			);
			
			// Answer send
			$this->success(lang('ionize_message_lang_saved'));
		}
		else
		{
			$this->error(lang('ionize_message_lang_not_saved'));
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Cleans content lang tables from non existing language.
	 * To be used after a lang delete, if these lang data will not be used anymore
	 *
	 */
	function clean_tables()
	{
		$tables = array('page', 'article', 'media');
	
		$deleted_rows = $this->lang_model->clean_lang_tables($tables);
	
		// Answer send
		$this->success(lang('ionize_message_lang_tables_cleaned'));
		
	}


	// ------------------------------------------------------------------------


	/**
	 * Copy one language content to another
	 * Let the user choose wich content will be copied
	 * 
	 * @TODO...
	 * 
	 */
	function copy_lang_content()
	{
		// Get "from" table
		
		// Get all the "from" table fields
		
		// Call the model function to copy the data
//		$this->lang_model->copy_lang_content($table, $fields, $from, $to);
		
	}


	// ------------------------------------------------------------------------


	/**
	 * Updates all the existing languages
	 *
	 */
	function update()
	{
		foreach(Settings::get_languages() as $lang)
		{
			// Update existing languages
			$data = array(
						'lang' =>		$this->input->post('lang_'.$lang['lang']),
						'name' =>		$this->input->post('name_'.$lang['lang']),
						'online' =>		$this->input->post('online_'.$lang['lang'])
					);

			($this->input->post('default_lang') == $lang['lang']) ? $data['def'] = '1' : $data['def'] = '0';

			// Check if the 

			if (($lang['lang'] != $data['lang']) && $this->lang_model->exists( array( 'lang' =>  $data['lang'] ) ) )
			{
				$this->error(lang('ionize_message_lang_code_already_exists'));
			}
			
			// If the default lang is different from the current one, pages need to be checked
			
			// Update the lang
			$this->lang_model->update($lang['lang'], $data);
			
			// If the lang code changed, update all the pages and articles content translations
			if ($lang['lang'] != $data['lang'])
			{
				$tables  = array('article','page', 'media', 'category');
				$this->lang_model->update_lang_tables($tables, $from = $lang['lang'], $to = $data['lang']);
			}
		}

		// Update the language config file
		if ( false == $this->_update_config_file())
		{
			$this->error(lang('ionize_message_lang_file_not_saved'));
		}
		
		// UI update panels
		$this->update[] = array(
			'element' => 'mainPanel',
			'url' => site_url('admin/lang')
		);

		$this->success(lang('ionize_message_lang_updated'));
	}


	// ------------------------------------------------------------------------


	/** 
	 * Saves ordering
	 * 
	 */
	function save_ordering() {

		if( $order = $this->input->post('order') )
		{
			// Saves the new ordering
			$this->lang_model->save_ordering($order);
			
			// Answer send
			$this->success(lang('ionize_message_lang_ordered'));
		}
		else 
		{
			// Answer send
			$this->error(lang('ionize_message_operation_nok'));
		}
	}
	
	
	// ------------------------------------------------------------------------


	/** 
	 * Delete a lang
	 *
	 * @param	string		lang code
	 * @param	boolean		if true, the transport is through XHR
	 *
	 */
	function delete($lang)
	{
		$affected_rows = $this->lang_model->delete($lang);

		if ($affected_rows > 0)
		{
			$this->id = $lang;
			
			// Update array
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' => site_url('admin/lang')
			);
			
			// Answer send
			$this->success(lang('ionize_message_lang_deleted'));
		}
		else
		{
			// Answer send
			$this->error(lang('ionize_message_lang_not_deleted'));			
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Updates the language config file
	 *
	 */
	function _update_config_file()
	{
		$languages = $this->lang_model->get_list($where = false, $orderby = 'ordering ASC');

		// Default language
		$def_lang = '';
		
		// Available languages array
		$lang_uri_abbr = array();
		
		foreach($languages as $l)
		{
			// Set defualt lang code
			if ($l['def'] == '1')
				$def_lang = $l['lang'];
			
			$lang_uri_abbr[$l['lang']] = $l['name'];
		}

		// Files begin
		$conf  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
		
		$conf .='/*'."\n";
		$conf .='| -------------------------------------------------------------------'."\n";
		$conf .='| IONIZE LANGUAGES'."\n";
		$conf .='| -------------------------------------------------------------------'."\n";
		$conf .='| Contains the available languages definitions for the front-end.'."\n";
		$conf .='| Auto-generated by Ionizes Language administration.'."\n";
		$conf .='|'."\n";
		$conf .='| IMPORTANT : '."\n";
		$conf .='| This file has no impact on ionizes admin languages.'."\n";
		$conf .='| For Admin languages modification, see application/languages/  '."\n";
		$conf .='|'."\n";
		$conf .='*/'."\n\n";		
		
		$conf .= "// default language abbreviation\n";
		$conf .= "\$config['language_abbr'] = '".$def_lang."';\n\n";
		
		$conf .= "// available languages\n";
		$conf .= "\$config['lang_uri_abbr'] = ".dump_variable($lang_uri_abbr)."\n\n";
		
		$conf .= "// ignore these language abbreviation : not used for the moment \n";
		$conf .= "\$config['lang_ignore'] = array();\n";

		// files end
		$conf .= "\n\n";
		$conf .= '/* End of file language.php */'."\n";
		$conf .= '/* Auto generated by Language Administration on : '.date('Y.m.d H:i:s').' */'."\n";
		$conf .= '/* Location: ./application/config/language.php */'."\n";

		$ret = @file_put_contents(APPPATH . 'config/language' . EXT, $conf);
		
		if ($ret)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	

}


/* End of file lang.php */
/* Location: ./application/controllers/admin/lang.php */