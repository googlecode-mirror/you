<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
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
 * Ionize Settings Controller
 *
 * @package		Ionize
 * @subpackage	Controllers
 * @category	Website users Settings
 * @author		Ionize Dev Team
 *
 */

class Setting extends MY_admin 
{
	/**
	 * Fields on wich no XSS filtering is done
	 * 
	 * @var array
	 */
	protected $no_xss_filter = array('google_analytics');


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
		$this->_get_settings();

		$this->output('setting');
	}


	// ------------------------------------------------------------------------


	/**
	 * Shows technical settings
	 * These settings are managed only by Super Admins
	 *
	 */
	function technical()
	{
		// Get settings from DB and put them to the Settings library
		// (Settings are displayed in view from this library)
		$this->_get_settings();

		/* 
		 * Filemanager list
		 */
		foreach($this->config->item('filemanagers') as $f)
		{
//			if (file_exists(APPPATH.'../themes/admin/javascript/tinymce/jscripts/tiny_mce/plugins/'.strtolower($f)))
//			{
				$this->template['filemanagers'][] = $f;
//			}
		}

		/* 
		 * Database settings
		 */
		$this->load->dbutil();

		// If the user is here, a valid database.php config file exists !
		include(APPPATH.'config/database'.EXT);

		$this->template['db_host'] = 	$db['default']['hostname'];
		$this->template['db_name'] = 	$db['default']['database'];
		$this->template['db_user'] = 	$db['default']['username'];
		$this->template['db_pass'] = 	'';

		$this->template['databases'] =		$this->dbutil->list_databases();


		/* 
		 * Website Email settings
		 */
		if (file_exists(APPPATH.'config/email'.EXT))
		{
			include(APPPATH.'config/email'.EXT);
		}

		$this->template['protocol'] = 		isset($config['protocol']) ? $config['protocol'] : 'mail';
		$this->template['mailpath'] = 		isset($config['mailpath']) ? $config['mailpath'] : '/usr/sbin/sendmail';
		$this->template['smtp_host'] = 		isset($config['smtp_host']) ? $config['smtp_host'] : '';
		$this->template['smtp_user'] = 		isset($config['smtp_host']) ? $config['smtp_user'] : '';
		$this->template['smtp_pass'] = 		isset($config['smtp_pass']) ? $config['smtp_pass'] : '';
		$this->template['smtp_port'] = 		isset($config['smtp_port']) ? $config['smtp_port'] : '25';
		$this->template['charset'] = 		isset($config['charset']) ? $config['charset'] : 'utf-8';
		$this->template['mailtype'] = 		isset($config['mailtype']) ? $config['mailtype'] : 'text';


		/*
		 * Thumbs settings
		 */
		$this->template['thumbs'] = $this->settings_model->get_list(array('name like' => 'thumb_%'));
		

		$this->output('setting_technical');
	}


	// ------------------------------------------------------------------------


	/**
	 * Shows themes settings
	 *
	 */
	function themes()
	{
		/* 
		 * Get Themes list
		 *
		 */
		$themes = $themes_admin = array();
		$handle = opendir(APPPATH.'../themes');
		if ($handle)
		{
			while ( false !== ($theme = readdir($handle)) )
			{
				// make sure we don't map silly dirs like .svn, or . or ..
				if (substr($theme, 0, 1) != "." && $theme != 'index.html' && substr($theme,0,5) != 'admin')
					$themes[] = $theme;
				else if(substr($theme,0,5) == 'admin')
					$themes_admin[] = $theme;
			}
		}
		$this->template['themes'] = $themes;
		$this->template['themes_admin'] = $themes_admin;


		/* 
		 * Get Current theme views list
		 *
		 */
		// Filesystem files list
		$files = $this->_get_view_files();

		// Recorded views definitions 
		if (is_file(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php'))
			require_once(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php');

		// Try to match each file with found config file
		foreach($files as $file)
		{
			// $views is set in the config file (auto writed by this class)
			if (isset($views))
			{
				foreach($views as $type => $def)
				{
					foreach($def as $key => $definition)
					{
						if ($key == ($file->path . $file->name))
						{
							$file->type = $type;
							$file->definition = $definition;
						}
					}
				}
			}
			if (! isset($file->type))
			{
					$file->type = '';
					$file->definition = '';
			}
		}
		$this->template['files'] = $files;

		
		/* 
		 * Get Special URI definition
		 *
		 */
		

		$this->output('setting_theme');
	}


	// ------------------------------------------------------------------------


	/**
	 * Edits one view file
	 * @param	string	Optionnal. Path of the view
	 * @param	string	View name.
	 *
	 */
	function edit_view()
	{
		// View sub-folder
		$path = '';
		
		// Functions argumets
		$args = func_get_args();
		
		// If path is defined, get the path
		// Only one sub-folder in views folder
		if (func_num_args() > 1)
		{
			$view = $args[func_num_args() - 1];
			array_pop($args);
			$path = implode('/', $args);
		}
		else 
			$view = $args[0];
		
		$this->template['path'] = $path;
		$this->template['view'] = $view;

		// file path
		$filepath = APPPATH.'../themes/'.Settings::get('theme').'/views/';

		// View sub-folder ?
		if ($path != '')
			$filepath .= $path.'/';

		// Get file content
		$content = file_get_contents($filepath.$view.'.php');
		$content = str_replace('<', '&lt;', $content);
		$content = str_replace('>', '&gt;', $content);

		$this->template['content'] = $content;

		$this->output('setting_edit_view');
	}

	// ------------------------------------------------------------------------


	/**
	 * Saves one view file
	 * 
	 *
	 */
	function save_view()
	{
		$view = $this->input->post('view');
		$path = $this->input->post('path');
		
		// Get the path if there is one
		$path = ($path) ? $path.'/'.$view : $view;
		
		// File Content
		$content = $this->input->post('content');

		$content = str_replace('&lt;', '<', $content);
		$content = str_replace('&gt;', '>', $content);

		// Writing problem
		if ( ! write_file(APPPATH.'../themes/'.Settings::get('theme').'/views/'.$path.'.php', $content))
		{
			$this->error(lang('ionize_message_error_writing_file'));				
		}
		else
		{
			$this->success(lang('ionize_message_view_saved'));				
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves settings
	 *
	 */
	function save()
	{
		// Settings to save
		$lang_settings = array('meta_keywords', 'meta_description');
		$settings = array('site_title', 'show_help_tips');

		// Save settings to DB
		$this->_save_settings($settings, $lang_settings);

		// Answer
		$this->success(lang('ionize_message_settings_saved'));
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves technical settings
	 *
	 */
	function save_technical()
	{
		// Settings to save
		$settings = array(	'filemanager', 'files_path', 'cache', 'cache_time', 
							'ftp_dir', 'ftp_host', 'ftp_user', 'ftp_password', 
							'google_analytics', 'system_thumb_list', 'system_thumb_edition', 
							'use_extend_fields');
							
		// Medias extensions to save			
		$settings_extension = array('media_type_picture', 'media_type_video', 'media_type_music', 'media_type_file');

		foreach ($settings_extension as $setting)
		{	
			if ($this->input->post($setting))
			{
				$this->input->set_post($setting, str_replace(' ', '', strtr(trim($this->input->post($setting), ",/\-_"), '/\-_', '')));
				$settings[] = $setting;
			}
		}

		// Get the old media path before saving
		$old_files_path = Settings::get('files_path');

		// Update the media table regarding to the new files path
		$new_files_path = $this->input->post('files_path');
		
		if ($new_files_path != '' && $new_files_path != '/' && ($old_files_path != $new_files_path))
		{
			$this->settings_model->update_media_path($old_files_path, $new_files_path);
		}
		else
		{
			// Preserve the old files_path value
			// $this->input->set_post() is a extended function from /application/libraries/MY_Input.php extended lib.
			$this->input->set_post('files_path', $old_files_path);
		}

		// Save settings to DB
		$this->_save_settings($settings);

		// Thumbs update
		$thumbs  = $this->settings_model->get_list(array('name like' => 'thumb_%'));
		foreach($thumbs as $thumb)
		{
			$sizeref = 	$this->input->post('thumb_sizeref_'.$thumb['id_setting']);
			$size = 	$this->input->post('thumb_size_'.$thumb['id_setting']);
			$square = 	$this->input->post('thumb_square_'.$thumb['id_setting']);
			$unsharp = 	$this->input->post('thumb_unsharp_'.$thumb['id_setting']);

			$data = array(
						'name'	=> 'thumb_'.$this->input->post('thumb_name_'.$thumb['id_setting']),
						'content' => $sizeref.','.$size.','.$square.','.$unsharp
					);
			$this->settings_model->update($thumb['id_setting'], $data);
		}
		
		// config/medias.php config file
		// 
		$this->load->helper('file');

		$conf  = "<?php \n\n";	 

		$conf .= "// Medias base folder. Used by all external plugin wich needs.\n";
		$conf .= "\$config['files_path'] = '".$this->input->post('files_path')."/';\n";

		// files end
		$conf .= "\n\n";
		$conf .= '/* End of file medias.php */'."\n";
		$conf .= '/* Auto generated by Themes Administration on : '.date('Y.m.d H:i:s').' */'."\n";
		$conf .= '/* Location: ' .APPPATH.'config/medias.php */'."\n";

		// Writing problem
		if ( ! write_file(APPPATH.'config/medias.php', $conf))
		{
			$this->error(lang('ionize_message_error_writing_medias_file'));				
		}
		
		// Update the main panel
		$this->update[] = array(
			'element' => 'mainPanel', 
			'url' => site_url('admin/setting/technical')
		);

		
		
		// Answer
		$this->success(lang('ionize_message_settings_saved'));
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves themes
	 *
	 */
	function save_themes()
	{
		// Settings to save
		$settings = array('theme', 'theme_admin');

		// Save settings to DB
		$this->_save_settings($settings);
		
		// Update Views table 
		$this->update[] = array(
			'element' => 'mainPanel',
			'url' =>  'admin/setting/themes'
		);
		
		// Answer
		$this->success(lang('ionize_message_settings_saved'));
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves views definition config file
	 * File located in current theme folder : config/views.php
	 *
	 */
	function save_views()
	{
		// Get the views informations
		$views = $this->_get_view_files();

		// Array of view types 
		$viewsTypes = array();

		// View Array
		$viewsArray = array();
		foreach($views as $view)
		{
			$key = $view->path.$view->name;

			

			// If type is defined
			if (isset($_POST['viewtype_'.$key]) && $_POST['viewtype_'.$key] != '')
			{
				$viewsArray[$_POST['viewtype_'.$key]][$view->path . $view->name] = $_POST['viewdefinition_'.$key];
				
				// Add the view type to the viewTypes array. View type is : "article", "page", etc....
				if ( ! in_array($_POST['viewtype_'.$key], $viewsTypes))	$viewsTypes[] = $_POST['viewtype_'.$key];
			}
		}

		// Sort each array of view type by logical name
		foreach($viewsTypes as $vt)
		{
			if ( ! empty($viewsArray[$vt]))
				asort($viewsArray[$vt]);	
		}

		
		$conf  = "<?php if ( ! defined('BASEPATH')){exit('Invalid file request');}\n\n";
	 
		$conf .= "\$views = " . (String) var_export($viewsArray, true) .";\n";
		
		// files end
		$conf .= "\n\n";
		$conf .= '/* End of file views.php */'."\n";
		$conf .= '/* Auto generated by Themes Administration on : '.date('Y.m.d H:i:s').' */'."\n";
		$conf .= '/* Location: ' .APPPATH.'../themes/'.Settings::get('theme'). '/config/views.php */'."\n";

		// Writing problem
		if ( ! write_file(APPPATH.'../themes/'.Settings::get('theme').'/config/views.php', $conf))
		{
			$this->error(lang('ionize_message_error_writing_file'));				
		}
		else
		{
			$this->success(lang('ionize_message_views_saved'));				
		}
		
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Saves one new thumb setting
	 *
	 */
	function save_thumb()
	{
		if(	$this->input->post('thumb_name_new') != "" && $this->input->post('thumb_size_new') != "" )
		{
			$sizeref = 	$this->input->post('thumb_sizeref_new');
			$size = 	$this->input->post('thumb_size_new');
			$square = 	($this->input->post('thumb_square_new')) ? $this->input->post('thumb_square_new') : 'false';
			$unsharp = 	($this->input->post('thumb_unsharp_new')) ? $this->input->post('thumb_unsharp_new') : 'false';

			$data = array(
						'name'	=> 'thumb_'.$this->input->post('thumb_name_new'),
						'content' => $sizeref.','.$size.','.$square.','.$unsharp
					);

			// If this thumb doesn't exists : Save to DB
			if ( ! $this->settings_model->exists(array('name'=>$data['name'])) )
			{
				// DB insert
				$this->settings_model->insert($data);

				// UI panel to update after saving
				$this->update[] = array(
					'element' => 'mainPanel',
					'url' => site_url('admin/setting/technical')
				);

				// Answer
				$this->success(lang('ionize_message_thumb_saved'));				
				
				// Exit method
				exit();
			}
		}
		
		// If the method arrive her, something fails
		$this->error(lang('ionize_message_thumb_not_saved'));				
	}


	// ------------------------------------------------------------------------


	/**
	 * Delete one thumb setting
	 *
	 * @param	boolean		if true, the transport is through XHR
	 *
	 */
	function delete_thumb($id)
	{
		if ($this->settings_model->delete($id) > 0)
		{
			// UI panel to update after saving
			$this->update[] = array(
				'element' => 'mainPanel',
				'url' =>  site_url('admin/setting/technical')
			);

			$this->success(lang('ionize_message_thumb_deleted'));				
		}		
		else
		{
			$this->error(lang('ionize_message_thumb_not_deleted'));				
		}
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Saves database settings
	 *
	 */
	function save_database()
	{
		// DB Config
		$db_config = array(
			'hostname'    =>  $_POST['db_host'],
			'username'    =>  $_POST['db_user'],
			'password'    =>  $_POST['db_pass'],
			'database'    =>  $_POST['db_name'],
			'dbdriver'    =>  $_POST['db_driver'],
			'dbprefix'    =>  '',
			'pconnect'    =>  true,
			'db_debug'    =>  true,
			'cache_on'    =>  false,
			'cachedir'    =>  '',
			'char_set'    =>  'utf8',
			'dbcollat'    =>  'utf8_unicode_ci'
		);

		// If data are missing : Redirect || error
		if ($db_config['hostname'] == '' ||
			$db_config['dbdriver'] == '' ||
			$db_config['database'] == '' ||
			$db_config['username'] == '')
		{
			$this->error(lang('ionize_message_database_not_saved'));				
		}
		
		// Try to connect to the DB
		$dsn = $db_config['dbdriver'].'://'.$db_config['username'].':'.$db_config['password'].'@'.$db_config['hostname'].'/'.$db_config['database'];
		$db = DB($dsn, true, true);
		$db->db_connect();
		
		// Check if database exists !
		if ( ! $db->db_select()  )
		{
			$this->error(lang('ionize_message_database_connection_error'));
		}
		
		// Everything OK : Saving data to database config file
		else
		{
			// Write the config/database.php file
			$this->load->helper('file');

			$conf  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
		 
			$conf .= "\$active_group = \"default\";\n";
			$conf .= "\$active_record = TRUE;\n\n";
		 
			foreach ($db_config as $key => $val)
			{
				if ( ! is_bool($val))
					$val = '"'.$val.'"';
				else
				{
					$val = ($val === true ) ? "true" : "false";
				}
				
				$conf .= "\$db['default']['".$key."'] = ".$val.";\n";        
			} 
			
			// files end
			$conf .= "\n\n";
			$conf .= '/* End of file database.php */'."\n";
			$conf .= '/* Auto generated by Settings Administration on : '.date('Y.m.d H:i:s').' */'."\n";
			$conf .= '/* Location: ./application/config/database.php */'."\n";
				 
			// Writing problem
			if ( ! write_file(APPPATH.'config/database.php', $conf))
			{
				$this->error(lang('ionize_message_error_writing_database_file'));				
			}
			else
			{
				// UI panel to update after saving : Structure panel
				$this->update[] = array(
					'element' => 'structurePanel',
					'url' => site_url('admin/core/get_structure')
				);

				$this->success(lang('ionize_message_database_saved'));				
			}
		}
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Saves SMTP settings
	 *
	 */
	function save_smtp()
	{
		/*
		 * Save the website email
		 *
		 */
		$settings = array('site_email');

		// Save settings to DB
		$this->_save_settings($settings);
	
	
		/*
		 * Save email sending settings
		 *
		 */
		$data = array(
			'smtp_host'		=> '',
			'smtp_user'		=> '',
			'smtp_pass'		=> '',
			'smtp_port'		=> '',
			'protocol'		=> '',
			'mailpath'		=> '',
			'charset'		=> '',
			'mailtype'		=> ''
		);
		
		// Post data
		foreach ($_POST as $key => $val)
		{
			if (isset($data[$key]))
				$data[$key] = $val;
		}
		
		// If data are missing : Redirect || error
		if ($data['protocol'] == '' )
		{
			$this->error(lang('ionize_message_smtp_not_saved'));				
		}
		// Everything OK : Saving data to database config file
		else
		{
			// Write the config/database.php file
			$this->load->helper('file');
			
			$db_config = array(
				'protocol'    =>  '"'.$data['protocol'].'"',
				'mailpath'    =>  '"'.$data['mailpath'].'"',
				'smtp_host'    =>  '"'.$data['smtp_host'].'"',
				'smtp_user'    =>  '"'.$data['smtp_user'].'"',
				'smtp_pass'    =>  '"'.$data['smtp_pass'].'"',
				'smtp_port'    =>  '"'.$data['smtp_port'].'"',
				'mailtype'    =>  '"'.$data['mailtype'].'"',
				'charset'    =>  '"'.$data['charset'].'"'
			);	
			
			$conf  = "<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n\n";
		 
			foreach ($db_config as $key => $val)
			{
				$conf .= "\$config['".$key."'] = ".$val.";\n";        
			} 
			
			// files end
			$conf .= "\n\n";
			$conf .= '/* End of file email.php */'."\n";
			$conf .= '/* Auto generated by Settings Administration on : '.date('Y.m.d H:i:s').' */'."\n";
			$conf .= '/* Location: ./application/config/email.php */'."\n";
				 
			// Writing problem
			if ( ! write_file(APPPATH.'config/email.php', $conf))
			{
				$this->error(lang('ionize_message_error_writing_email_file'));				
			}
			else
			{
				$this->success(lang('ionize_message_smtp_saved'));				
			}
		}
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Get settings and put them to the Settings library
	 *
	 */
	function _get_settings()
	{
		$settings = $this->settings_model->get_list();

		/* Lang settings to Settings
		 */
		$callback = create_function('$v', 'return ($v["lang"]!="") ? true : false;');
		$lang_settings = array_filter($settings, $callback );

		Settings::set_lang_settings($lang_settings, 'name', 'content');
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the views file list as an array
	 *
	 * @return array	Files names list
	 *
	 */
	function _get_view_files()
	{
		$views = array();
		
		$theme_path = APPPATH.'../themes/'.Settings::get('theme').'/views';

		if (is_dir($theme_path))
		{
			$dir_iterator = new RecursiveDirectoryIterator($theme_path);
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
			
			foreach ($iterator as $file)
			{
				if ($file->isFile() && (substr($file->getFilename(), 0, 1) != ".") )
				{
					// Set a human readable path
					$path = str_replace($theme_path, '', $file->getPath());
					$path = str_replace('\\', '/', $path) . '/';
					$path = substr($path,1);
					
					// Set the path
					$file->path = $path;
					
					// Set the view ame (filename without .php extension)
					$file->name = str_replace('.php', '', $file->getFilename());
					
					$views[] = $file;
				}
			}
		}
		return $views;
	}


	// ------------------------------------------------------------------------


	/**
	 * Saves settings according to the passed settings tables
	 *
	 * @param	array	Settings keys array
	 * @param	array	Lang Settings keys array
	 *
	 */
	function _save_settings($settings, $lang_settings=false)
	{
		/* 
		 * Save the lang settings first 
		 */
		if ($lang_settings != false)
		{
			foreach(Settings::get_languages() as $language)
			{
				foreach ($lang_settings as $setting)
				{
					$data = array(
								'name' => $setting,
								'content' => ($content = $this->input->post($setting.'_'.$language['lang'])) ? $content : '',
								'lang' => $language['lang']
							);
					$this->settings_model->save_setting($data);
				}
			}
		}
		
		/*
		 * Saves settings
		 */
		foreach ($settings as $setting)
		{
			$content = '';
			
			if ($this->input->post($setting))
			{
				// Avoid or not security XSS filter
				if ( ! in_array($setting, $this->no_xss_filter))
					$content = $this->input->post($setting);
				else
				{
					$content = stripslashes($_REQUEST[$setting]);
				}
			}				
		
			$data = array(
						'name' => $setting,
						'content' => $content
					);
			
			$this->settings_model->save_setting($data);
		}
	}

}

/* End of file setting.php */
/* Location: ./application/controllers/admin/setting.php */