<?php
/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('wbty_components.models.wbtymodeladmin');

/**
 * Wbty_audio_manager model.
 */
class Wbty_audio_managerModelaudio_file extends WbtyModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'com_wbty_audio_manager';
	protected $com_name = 'wbty_audio_manager';
	protected $list_name = 'audio_files';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'audio_files', $prefix = 'Wbty_audio_managerTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true, $control='jform')
	{
		// Initialise variables.
		$app	= JFactory::getApplication();
		
		$key=JRequest::getVar('subtable_key');
		if ($control=='jform' && $key && JRequest::getVar('tmpl')=='component') {
			$control = 'audio_file['.$key.']';
		}

		// Get the form.
		$form = $this->loadForm('com_wbty_audio_manager.audio_file.'.$control, 'audio_file', array('control' => $control, 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	public function getItems($parent_id, $parent_key) {
		$query = $this->_db->getQuery(true);
		
		$query->select('id');
		$query->from($this->getTable()->getTableName());
		$query->where($parent_key . '=' . (int)$parent_id);
		
		$data = $this->_db->setQuery($query)->loadObjectList();
		if (count($data)) {
			$this->getState();
			$key=0;
			foreach ($data as $key=>$d) {
				$this->data = null;
				$this->setState($this->getName() . '.id', $d->id);
				$return[$key+1] = $this->getForm(array(), true, 'audio_file['.$key.']');
			}
		}
		
		return $return;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		if ($this->data) {
			return $this->data;
		}
		
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_wbty_audio_manager.edit.audio_file.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed
			
			
		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');

		parent::prepareTable($table);
		
		$data = JRequest::getVar('jform');
		if (!$data['include_in_list']) { $table->include_in_list = 0; }
	}
	
	function save($data) {
		if ($_FILES['jform']['name']['file_name']) {
			$data['file_name'] = $this->fileSave('jform', array('file_name'));
		} else {
			unset($data['file_name']);
		}
		
		if (!parent::save($data)) {
			return false;
		}
		
		// manage link
		
		if (JRequest::getVar('tmpl') == 'component') {
			$article_id = JRequest::getVar('article_id');
			$this->save_sub('audio_file_article', array(0 => array('article'=>$article_id)), 'audio_file_id');
		} else {
			$audio_file_article = JRequest::getVar('audio_file_article', array(), 'post', 'ARRAY');
			$this->save_sub('audio_file_article', $audio_file_article, 'audio_file_id');
		}
		
		return $this->table_id;
	}
	
	function fileSave($base, $array_item) {
		require_once(JPATH_COMPONENT .'/helpers/fileuploader.php');
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$allowedExtensions = array('mp3', 'ogg');
		// max file size in bytes
		$sizeLimit = 60 * 1024 * 1024;
		$user_id = JFactory::getUser()->id;
		
		$uploader = new qqFileUploader($allowedExtensions, $base, $array_item, $sizeLimit);

		// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
		if (!JFolder::exists(JPATH_ROOT.'/media/wbty_audio_manager/audio_files/')) {
			JFolder::create(JPATH_ROOT.'/media/wbty_audio_manager/audio_files/');
		}
		$directory = JPATH_ROOT.'/media/wbty_audio_manager/audio_files/';
		$result = $uploader->handleUpload($directory);
		
		if (!is_array($result)) {
			return '/media/wbty_audio_manager/audio_files/'.$result;
		} else {
			return '';
		}
	}
}