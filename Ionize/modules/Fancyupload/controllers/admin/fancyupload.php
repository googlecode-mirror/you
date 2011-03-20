<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Fancyupload extends Module_Admin 
{
	/**
	 * Constructor
	 *
	 */
	function construct(){}


	// ------------------------------------------------------------------------


	/**
	 * Admin panel 
	 *
	 */
	function index()
	{

		/*
		 * Get the media folders
		 */
		$path = config_item('base_path').'/'.Settings::get('files_path');

		$iter = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::KEY_AS_FILENAME), RecursiveIteratorIterator::SELF_FIRST);
		
		// Build the folder select drop down
		$folder_select = array();
		foreach ($iter as $file=>$entry)
		{
		    if ($entry->isDir() and substr($entry->getFileName(), 0, 5) != 'thumb')
		    {
		        $pre = ($iter->getDepth() > 0) ? str_repeat("&nbsp;", 2*$iter->getDepth()). ' &raquo; ' : '';
		    	$folder_select[$path.'/'.$entry->getFileName().'/'] = $pre.$entry->getFileName();
		    }
		}

		// Get groups list filtered on level <= current_user level
		$this->template['groups'] = array_filter(Connect()->model->get_groups(), array($this, '_filter_groups'));


		// Folders to template
		$this->template['fancyupload_folder'] = form_dropdown('fancyupload_folder', $folder_select, config_item('fancyupload_folder'), 'class="w160"');

		// Admin view
		$this->output('admin/fancyupload');
	}
	
	
	// ------------------------------------------------------------------------


	/**
	 * Saves Fancyupoad settings in /modules/Fancyupoad/config/config.php file
	 *
	 */	
	function save()
	{
		$this->load->helper('file');

		// Settings to save
		$keys = array
		(
			'fancyupload_active',
			'fancyupload_max_upload',
			'fancyupload_type',
			'fancyupload_folder',
			'fancyupload_file_prefix',
			'fancyupload_send_alert',
			'fancyupload_send_confirmation',
			'fancyupload_email',
			'fancyupload_group'
		);

		$settings = array_fill_keys($keys, '');

		// Feed array with Post data
		foreach ($_POST as $key => $val)
		{
			if (isset($settings[$key]))
				$settings[$key] = $val;
		}

		if ($settings['fancyupload_max_upload'] == '')
		{
			$settings['fancyupload_max_upload'] = 0;
		}

		/*
		 * Saving the file
		 */
		$conf  = "<?php \n\n";	 
		foreach ($settings as $key=>$val)
		{
			$conf .= "\$config['".$key."'] = '".$val."';\n";
		}

		// files end
		$conf .= "\n\n";
		$conf .= '/* End of file config.php */'."\n";
		$conf .= '/* Auto generated by FancyUpload Administration on : '.date('Y.m.d H:i:s').' */'."\n";
		$conf .= '/* Location: ' .$this->config->item('module_path').'Fancyupload/config/config.php */'."\n";

		// Writing problem
		if ( ! write_file($this->config->item('module_path').'Fancyupload/config/config.php', $conf))
		{
			$this->error(lang('ionize_message_error_writing_file'));				
		}
		else
		{
			$this->success(lang('module_fancyupload_message_options_save'));				
		}
	}

	/**
	 * Groups filter callback function
	 *
	 */
	function _filter_groups($row)
	{
		// Current connected user level
		$user = Connect()->get_current_user();
		$this->current_user_level = $user['group']['level'];

		return ($row['level'] <= $this->current_user_level) ? true : false; 
	}


}
/* End of file fancyupload.php */
/* Location: ./modules/Fancyupload/controllers/admin/fancyupload.php */