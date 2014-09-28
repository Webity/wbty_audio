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
class Wbty_audio_managerModelaudio_file_article extends WbtyModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'com_wbty_audio_manager';
	protected $com_name = 'wbty_audio_manager';
	protected $list_name = 'audio_file_articles';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'audio_file_articles', $prefix = 'Wbty_audio_managerTable', $config = array())
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
			$control = 'audio_file_article['.$key.']';
		}

		// Get the form.
		$form = $this->loadForm('com_wbty_audio_manager.audio_file_article.'.$control, 'audio_file_article', array('control' => $control, 'load_data' => $loadData));
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
				$return[$key+1] = $this->getForm(array(), true, 'audio_file_article['.$key.']');
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
		$data = JFactory::getApplication()->getUserState('com_wbty_audio_manager.edit.audio_file_article.data', array());

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
		
		if (JRequest::getVar('audio_file_id')) {
	$table->audio_file_id=JRequest::getVar('audio_file_id');
}
	}
	
	function save($data) {
		if (!parent::save($data)) {
			return false;
		}
		
		// manage link
		
		
		return $this->table_id;
	}
}