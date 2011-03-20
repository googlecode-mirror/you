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
 * @category	Media management
 * @author		Ionize Dev Team
 *
 */

class Media extends MY_admin 
{

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

//		$this->connect->restrict('editors');

		// Models
		$this->load->model('media_model');
		if (Settings::get('use_extend_fields') == '1')
		{
			$this->load->model('extend_field_model', '', true);
		}

		// Librairies
		$this->load->library('image_lib');
	}


	// ------------------------------------------------------------------------


	/**
	 * Display the filemanager regarding to the choosen one
	 *
	 */
	function get_media_manager($mode = NULL)
	{
		// Open the file manager regarding to settings
		switch(Settings::get('filemanager'))
		{
			// TinyMCE FileManager
			case 'filemanager' :
				
				// Filemanager view
				$this->output('filemanager/filemanager');
				break;
				
			case 'tinybrowser' :
				
				$this->template['mode'] = (is_null($mode)) ? 'file' : 'image';
				
				$this->output('filemanager/tinybrowser');
				break;
				
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Returns the media list depending on the media type
	 *
	 * @param	string	Media type. Can be 'picture', 'music', 'video', 'file'
	 * @param	string	parent. Example : 'article', 'page'
	 * @param	string	Parent ID
	 *
	 */
	function get_media_list($type, $parent, $id_parent)
	{
		$data['items'] = $this->media_model->get_list($parent, $id_parent, $type);
		
		// To set data relative to the parent
		$data['parent'] = $parent;
		$data['id_parent'] = $id_parent;

		$data['type'] = $type;
		
		if (empty($data['items']))
		{
			// Addon data to the answer
			$output_data = array('type' => $type);

			// Answer send
			$this->notice(lang('ionize_message_no_'.$type), $output_data);
		}
		else
		{
			// Media List view
			if ($type == 'picture')
				$view = $this->load->view('media_picture_list', $data, true);
			else
				$view = $this->load->view('media_list', $data, true);
			
			// Addon data to the answer			
			$output_data = array('type' => $type, 'content' => $view);
			
			// Answer send
			$this->success(null, $output_data);
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Add one media to a parent
	 * Creates also thumbnails for picture if type = 'picture'
	 *
	 * @param	string	Media type. Can be 'picture', 'music', 'video', 'file'
	 * @param	string	parent. Example : 'article', 'page'
	 * @param	string	Parent ID
	 * @param	string	Deprecated.
	 *					The path is send through post
	 *					Complete path, including media file name, to the medium
	 *
	 */
	function add_media($type, $parent, $id_parent, $path=null) 
	{
		/*
		 * Some path cleaning
		 * The media path should start at the root media dir.
		 * Adding base_url() to the media path gives the complete media path
		 * Example : files/pictures/my_picture.jpg
		 */
		$path = $this->input->post('path');
		 
		// Replace the path separators with '/'
		$path = str_replace("~", "/", $path);
		
		// First, try to cut the complete base_url() path in the picture path
		// ex : http://my_domain/ionize_install_path/ => ''
		$path = str_replace(base_url(), '', $path);
		
		// If not protocol prefix, the base URL has to be cut
		$host = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
		$host .= "://".$_SERVER['HTTP_HOST'];

		// Get the base URL as /ionize_install_path
		$base_url = str_replace($host, '', base_url());

		// Clean the first '/'
		$base_url = preg_replace('/^[\/]/', '', $base_url);

		$path = str_replace($base_url, '', $path);
		
		// Clean the first '/'
		$path = preg_replace('/^[\/]/', '', $path);


		/*
		 * Database insert
		 */
		$id = $this->media_model->insert_media($type, $path);

		/*
		 * Thumbnail creation for picture media type
		 */
		if ($type == 'picture')
		{
			try 
			{
				$this->_init_thumbs($id);
			}
			catch (Exception $e)
			{
				$this->error($e->getMessage());

				return;
			}
		}

		/*
		 * Parent linking
		 */
		$data = '';		
		if (!$this->media_model->attach_media($type, $parent, $id_parent, $id)) 
		{
			$this->error(lang('ionize_message_media_already_attached'));
		}
		else 
		{
			// Addon answer data
			$output_data = array('type' => $type);
		
			$this->success(lang('ionize_message_media_attached'), $output_data);
		}
	}


	// ------------------------------------------------------------------------


	/** 
	 * Init all the thumbs fo a given parent
	 * @param	string	parent type
	 * @param	string	parent ID
	 *
	 * @TODO : Improve the errors management
	 *
	 */
	function init_thumbs_for_parent($parent, $id_parent)
	{
		$pictures =	$this->media_model->get_list($parent, $id_parent, 'picture');

		$return = true;

		foreach($pictures as $picture)
		{
			try
			{
				$this->_init_thumbs($picture['id_media']);
			}
			catch(Exception $e)
			{
				// Fail message
				$this->error($e->getMessage());

				return;
			}
		}
		
		// Everything's OK
		$this->success(lang('ionize_message_operation_ok'));
	}


	// ------------------------------------------------------------------------


	/** 
	 * Init the thumbs for one picture
	 *
	 * @param	string	Picture ID
	 *
	 */
	function init_thumbs($id)
	{
		try
		{
			// Thumbs init
			$this->_init_thumbs($id);
			
			// Confirmation message
			$this->success(lang('ionize_message_operation_ok'));
		}
		catch(Exception $e)
		{
			// Fail message
			$this->error($e->getMessage());
		}
	}


	// ------------------------------------------------------------------------


	/** 
	 * Detach media from a parent element
	 *
	 * @param	string		Media type. Transmitted to send it back to the javascript onSuccess (disposeMedia)
	 * @param	string		parent type. Ex : 'page', 'article'
	 * @param	string		parent ID
	 * @param	string		medium ID
	 *
	 */
	function detach_media($type, $parent, $id_parent, $id_media) 
	{
		if ($parent !== false && $id_parent !== false && $id_media !== false)
		{			
			// Delete succeed : Message to user
			if ($this->media_model->delete_joined_key('media', $id_media, $parent, $id_parent) > 0)
			{
				// Used by answer callback to delete HtmlDomElement item
				$this->id = $id_media;
				
				// Addon data
				$output_data = array('type' => $type);
				
				// Answer
				$this->success(lang('ionize_message_media_detached'), $output_data);
			}
			// Error Message
			else
			{
				$this->error(lang('ionize_message_media_not_detached'));
			}
		}
	}


	// ------------------------------------------------------------------------

	
	/**
	 * Detach all media depending on the type for a given parent
	 *
	 * @param 	string	parent type
	 * @param	string	Parent ID
	 * @param	string 	parent ID
	 *
	 */
	function detach_media_by_type($parent, $id_parent, $type = false)
	{
		if ($parent !== false && $id_parent !== false && $type !== false)
		{
			// Delete succeed : Message to user
			if ($this->media_model->detach_media_by_type($parent, $id_parent, $type) > 0)
			{
				$this->success(lang('ionize_message_operation_ok'));
			}
			// Notice message : No media to detach
			else
			{
				$this->error(lang('ionize_message_no_media_to_detach'));
			}
		}	
	}
	

	// ------------------------------------------------------------------------


	/** 
	 * Saves media order for one parent
	 * 
	 * @param	string	parent type. Can be 'page', 'article'
	 * @param	string	parent ID
	 *
	 */
	function save_ordering($parent, $id_parent) {

		if( $order = $this->input->post('order') )
		{
			// Saves the new ordering
			$this->media_model->save_ordering($order, $parent, $id_parent);
			
			// Answer
			$this->success(lang('ionize_message_operation_ok'));
		}
		else 
		{
			$this->error(lang('ionize_message_operation_nok'));
		}
	}
	
	
	// ------------------------------------------------------------------------

	
	/** 
	 * Shows one media meta data
	 *
	 * @param string	Media type
	 * @param string	Media ID
	 *
	 */
	function edit($type, $id)
	{
		$this->media_model->feed_template($id, $this->template);

		$this->media_model->feed_lang_template($id, $this->template);
			
		// Get the thumbs to check each thumb status
		$this->template['thumbs'] = $this->settings_model->get_list(array('name like' => 'thumb_%'));

		/*
		 * extend fields
		 *
		 */
		$this->template['extend_fields'] = array();
		if (Settings::get('use_extend_fields') == '1')
		{
			$this->template['extend_fields'] = $this->extend_field_model->get_element_extend_fields('media', $id);
		}


		$this->output('media_edit');	
	}
	

	// ------------------------------------------------------------------------

	
	/**
	 * Saves one media metadata
	 *
	 */
	function save()
	{
		// Standard data;
		$data = array();
		
		// Standard fields
		$fields = $this->db->list_fields('media');

		foreach ($fields as $field)
		{
			if ( $this->input->post($field) !== false)
			{
				$data[$field] = $this->input->post($field);
			}
		}

		// Lang data
		$lang_data = array();

		$fields = $this->db->list_fields('media_lang');
		
		foreach(Settings::get_languages() as $language)
		{
			foreach ($fields as $field)
			{
				if ( $this->input->post($field.'_'.$language['lang']) !== false)
					$lang_data[$language['lang']][$field] = $this->input->post($field.'_'.$language['lang']);
			}
		}

		// Database save
		$this->id = $this->media_model->save($data, $lang_data);

		// Save extend fields data
		if (Settings::get('use_extend_fields') == '1')
			$this->extend_field_model->save_data('media', $this->id, $_POST);

		
		if ( $this->id !== false )
		{
			$this->success(lang('ionize_message_media_data_saved'));
		}
		else
		{
			$this->success(lang('ionize_message_media_data_not_saved'));
		}
	}


	// ------------------------------------------------------------------------


	/** 
	 * Init the thumbs for one picture
	 * @access	private
	 *
	 * @param	string	Picture ID
	 *
	 * Thumb settings : Array(
	 *						max_width : max width
	 *						square : 	is the thumbs cropped to square (true, false)
	 *						unsharp : 	unsharp filter on thumb (true, false)
	 *					 )
	 */
	function _init_thumbs($id)
	{
		// Base path : Path to the folder before files.
		$basepath = config_item('base_path').'/';
		
		// Pictures data from database
		$picture = $this->media_model->get($id);

		// Thumbs settings
		$this->base_model->set_table('setting');
		$thumbs = $this->base_model->get_list(array('name like' => 'thumb_%'));

		// If no thumbs settings : exception
		if (empty($thumbs))
		{
			throw new Exception(lang('ionize_exception_no_thumbs_settings'));			
		}

		// Check if source file exists
		if ( ! is_file($basepath.$picture['path']) )
		{
			throw new Exception( lang('ionize_exception_no_source_file').' : '. $picture['file_name'] );						
		}

		// Create thumbs for each thumbs
		foreach($thumbs as $thumb)
		{
			// Thumb settings : from DB.
			$settings = explode(",", $thumb['content']);
			$setting = array(
							'dir' =>		$thumb['name'],
							'sizeref' => 	$settings[0],
							'size' => 		$settings[1],
							'square' => 	$settings[2],
							'unsharp' => 	$settings[3]
						);
			
			// Create directory is not exists
			if( ! is_dir($basepath.$picture['base_path'].$setting['dir']) )
			{
				// If MKDIR impossible : exception
				if ( ! @mkdir($basepath.$picture['base_path'].$setting['dir'], 0777) )
				{
					throw new Exception(lang('ionize_exception_folder_creation').' : '.$setting['dir']);
				}
			}

			// Thumbnail creation
			$thumb_path = $basepath.$picture['base_path'].$setting['dir']."/".$picture['file_name'];

			try
			{
				$this->_create_thumbnail($basepath.$picture['path'], $thumb_path, $setting);
			}
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Create one thumbnail
	 *
	 * @param	string	Full path to the source image, including image file name
	 * @param	string	Full path to the destination image, including image file name
	 * @param	array	Thumb settings array
	 *
	 */
	function _create_thumbnail($source_image, $new_image, $settings)
	{
		// Get images data : sizes
		$imgData = array();
		
		// Max memory needed
		$max_size = 0;
		
		// Get the image dimensions
		$dim = $this->get_image_dimensions($source_image);
		$imgData['image_width'] = $dim['width'];
		$imgData['image_height'] = $dim['height'];
		
		// CI Image_lib config array
		$config['source_image'] =	$source_image;
		$config['new_image'] =		$new_image;
		$config['quality'] =		'90';
		$config['maintain_ratio'] = true;
		$config['unsharpmask'] =	$settings['unsharp'];
		
		$config2 = array();
		
		// Non square picture
		if($settings['square'] != 'true' )
		{
			// Master dim as choosen size ref
			$config['master_dim'] =		$settings['sizeref'];
		}
		// Square picture
		else 
		{
			if ($imgData['image_width'] >= $imgData['image_height']) 
				$config['master_dim'] =	$config2['master_dim'] = 'height';
			else 
				$config['master_dim'] =	$config2['master_dim'] = 'width';
				
		}
		
		// Delete existing thumb
		if (is_file($new_image))
		{
			// Change the file rights
			if ( ! @chmod($new_image, 0777))
			{
	//			throw new Exception(lang('ionize_exception_chmod') );
			}
			
			// Delete the old thumb file
			if ( ! @unlink($new_image))
			{
	//			throw new Exception(lang('ionize_exception_unlink') );
			}
		}
			
		// Resize only if image size greather than thumb wished size
		// If greather, copy the source to the thumb destination folder
		if ($imgData['image_'.$config['master_dim']] >= $settings['size'])
		{
			$config['width'] =	$config['height'] =	$settings['size']; 		// Resize on master_dim. Used to keep ratio.

// trace($config);
		
			$this->image_lib->clear();
			$this->image_lib->initialize($config);

			// Thumbnail creation
			if ( ! $this->image_lib->resize() )
			{
				throw new Exception(lang('ionize_exception_image_resize') );
			} 
			
			// Crop to square if necessary
			if($settings['square'] == 'true') 
			{
				// CI Image_lib config array
				$config2['source_image'] =	$this->image_lib->full_dst_path;
				
				// Calculate x and y axis
				$config2['x_axis'] = $config2['y_axis'] = '0';
				
				// Get image dimension before crop
				$dim = $this->get_image_dimensions($this->image_lib->full_dst_path);

				// Center the scare
				if ($dim['width'] > $dim['height'])
				{
					$config2['x_axis'] = ($dim['width'] - $config['width']) / 2;
				}
				else
				{
					$config2['y_axis'] = ($dim['height'] - $config['height']) / 2;
				}

				$config2['new_image'] =		'';
				$config2['unsharpmask'] =	false;
				$config2['maintain_ratio'] = false;
				$config2['height'] =		$settings['size'];
				$config2['width'] =			$settings['size'];
				$this->image_lib->clear();
				$this->image_lib->initialize($config2);
				
				if ( true !== $this->image_lib->crop() )
				{
					throw new Exception(lang('ionize_exception_image_crop') );
				}
			}
			
			// Change the mod of the generated file
			if ( ! @chmod($new_image, 0777))
			{
//				throw new Exception(lang('ionize_exception_chmod') . ' : ' . $new_image);
			}
		}
		else 
		{
			if ( ! @copy($source_image, $new_image) )
			{
				throw new Exception(lang('ionize_exception_copy') . ' : ' . $source_image);
			}
		}
	}


	// ------------------------------------------------------------------------


	/**
	 * Get the dimensions of a picture
	 *
	 * @param	string	Complete path to the image file
	 * @return	array	Array of dimension.
	 *					'width' : contains the width
	 *					'height' : contains the height
	 *
	 */
	private function get_image_dimensions($path)
	{
		$dim = array();
		
		if (function_exists('getimagesize'))
		{
			if ($d = @getimagesize($path))
			{
				$dim['width']	= $d['0'];
				$dim['height']	= $d['1'];
				return $dim;
			}
			else
			{
				throw new Exception(lang('ionize_exception_getimagesize_get'). ' : '.$path);
			}
		}
		else
		{
			throw new Exception(lang('ionize_exception_getimagesize'));
		}
	}
	
}


/* End of file media.php */
/* Location: ./application/controllers/admin/media.php */