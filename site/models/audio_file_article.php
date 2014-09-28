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
		
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/fields');
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
		if (isset($this->data) && $this->data) {
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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = JRequest::getInt('id');
		$this->setState('audio_file_article.id', $pk);

		$offset = JRequest::getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$user		= JFactory::getUser();
		if ((!$user->authorise('core.edit.state', 'com_wbty_audio_manager')) &&  (!$user->authorise('core.edit', 'com_wbty_audio_manager'))){
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}
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
	
	protected function prepareTable(&$table)
	{
		$user =& JFactory::getUser();
		$table->state = 1;
		if (!$table->created_by) {
			$table->created_by = $user->id;
			$table->created_time = strftime('%Y-%m-%d %H:%M:%S');
		}
		if (JRequest::getVar('audio_file_id')) {
	$table->audio_file_id=JRequest::getVar('audio_file_id');
}
		$table->modified_by = $user->id;
		$table->modified_time = strftime('%Y-%m-%d %H:%M:%S');
		$this->table_id = $table->id;
	}
	
	public function save($data) {
		if (!parent::save($data)) {
			return false;
		}

		$table = $this->getTable();
		$this->table_db = $table->getDbo();
		
		if ($table->id) {
			$this->table_id = $table->id;
		} elseif($this->table_id==0) {
			$id = $this->table_db->insertid();
			$this->table_id = $id;
		}
		
		// manage link
		
		
		return $this->table_id;
	}
	
	protected function saveRelational($table, $key, $array) {
		if (!$this->table_id) {
			return false;
		}
		
		// Remove any old Management Indicators
		$query = 'DELETE FROM #__wbty_audio_manager_'.$table.' WHERE audio_file_articles_id='.$this->table_id;
		$this->_db->setQuery($query)->query();
		
		if ($array) {
			foreach ($array as $id) {
				$query = 'INSERT INTO #__wbty_audio_manager_'.$table.' SET '.$key.'='.$id.', audio_file_articles_id='.$this->table_id;
				$this->_db->setQuery($query)->query();
			}
		}
		
		return true;
	}
	
	public function getRelational($table, $key, $id) {
		$query = 'SELECT '.$key.' FROM #__wbty_audio_manager_'.$table.' WHERE audio_file_articles_id='.$id;
		return $this->_db->setQuery($query)->loadColumn();
	}
	
	protected function save_sub($name, $values, $parent_key) {
		require_once (JPATH_COMPONENT. '/models/'.$name.'.php' );
		$model = JModel::getInstance( $name, 'Wbty_audio_managerModel' );
		
		$ids = array();
		foreach($values as $key => $value) {
			$jform = array();
			foreach ($value as $k=>$v) {
				if ($v=='|set2id|') {
					$v = $this->table_id;
				}
				$jform[$k] = $v;
			}
			$jform[$parent_key] = $this->table_id;
			$ids[] = $model->save($jform);
		}
		return $this->clean_sub($name, $ids, $parent_key, $model);
	}
	
	protected function clean_sub($name, $ids, $parent_key, &$model) {
		if ($ids) {
			$where = 'id NOT IN ('.implode(',', $ids).') AND ';
		} else {
			$where = '';
		}
		$table_name = $model->getTable()->getTableName();
		$query = 'UPDATE '.$table_name.' SET state=-2 WHERE '.$where.$parent_key.'='.$this->_db->quote($this->table_id);
		return $this->_db->setQuery($query)->query();
	}
	
	protected function prep_link($data, $table, $prefix='#__wbty_audio_manager_') {
		$keep_ids = array();
		foreach ($data as $d) {
			if ($data['id']) {
				$keep_ids[] = $data['id'];
			}
		}
		
		$this->_db->setQuery('SELECT * FROM '.$prefix.$table.' WHERE audio_file_article_id='.$this->table_id.'');
		$db_data = $this->_db->loadAssocList();
		
		$db_ids = array();
		foreach ($db_data as $d) {
			if ($d['id']) {
				$db_ids[] = $d['id'];
			}
		}
		
		$to_delete = array_diff($db_ids, $keep_ids);
		$to_insert = array_unique($data);
		
		if (count($to_insert)) {
			$this->link_insert($to_insert, $table, $prefix);
		}
		if (count($to_delete)) {
			$this->link_delete($addressesto_delete, $table, $prefix);
		}
	}
	
	protected function link_insert($data, $table, $prefix) {
		if (!$this->table_id) {
			return false;
		}
		foreach ($data as $datum) {
			$value = '';
			foreach ($datum as $key=>$d) {
				$value .= $key . "='" . $d . "', ";
			}
			$value = rtrim($value, ", ");
			
			if ($datum['id']) {
				$q = "UPDATE ".$prefix.$table." SET ".$value." WHERE id = '{$datum['id']}' AND audio_file_article_id = '{$this->table_id}'";
			} else {
				$q = "INSERT INTO ".$prefix.$table." SET ".$value."";
			}
			$this->_db->setQuery($q);
			$this->_db->query();
		}
		return true;
	}
	
	protected function link_delete($data, $table, $prefix) {
		if (!$this->table_id) {
			return false;
		}
		foreach ($data as $d) {
			$q = "DELETE FROM ".$prefix.$table." WHERE id='{$d}'";
			$this->_db->setQuery($q);
			$this->_db->query();
		}
		return true;
	}

}