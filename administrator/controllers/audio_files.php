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

jimport('wbty_components.controllers.wbtycontrolleradmin');

/**
 * Audio_Files list controller class.
 */
class Wbty_audio_managerControllerAudio_Files extends WbtyControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'audio_file', $prefix = 'Wbty_audio_managerModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	public function remove_files() {
		$db =& JFactory::getDbo();
		
		$query = 'UPDATE #__wbty_audio_manager_audio_file_articles SET state=-2 WHERE article='.(int)JRequest::getVar('article_id').' AND audio_file_id IN ('.implode(',', JRequest::getVar('cid')).')';
		$db->setQuery($query)->query();
		
		$append = '&article_id='.JRequest::getVar('article_id');
		$append .= (JRequest::getVar('tmpl')=='component' ? '&tmpl=component' : '');
		JFactory::getApplication()->redirect('index.php?option=com_wbty_audio_manager&view=audio_files'.$append);
		exit();
	}
	
	public function add_files() {
		$db =& JFactory::getDbo();
		
		$ids = JRequest::getVar('cid', array());
		
		foreach ($ids as $id) {
			$query = 'INSERT INTO #__wbty_audio_manager_audio_file_articles SET state=1, article='.(int)JRequest::getVar('article_id').', audio_file_id = '.$id;
			$db->setQuery($query)->query();
		}
		
		$append = '&article_id='.JRequest::getVar('article_id');
		$append .= (JRequest::getVar('tmpl')=='component' ? '&tmpl=component' : '');
		JFactory::getApplication()->redirect('index.php?option=com_wbty_audio_manager&view=audio_files'.$append);
		exit();
	}
	
	public function search() {
		$input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'audio_files'));
        $input->set('layout', 'search');
		
		$this->display();
	}
	
	public function display($cachable = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', $this->default_view);
		$viewLayout = JRequest::getCmd('layout', 'default');

		$view = $this->getView($viewName, $viewType, '', array('base_path' => $this->basePath, 'layout' => $viewLayout));

		// Get/Create the model
		if ($model = $this->getModel($viewName))
		{
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}

		$view->assignRef('document', $document);

		$conf = JFactory::getConfig();

		// Display the view
		if ($cachable && $viewType != 'feed' && $conf->get('caching') >= 1)
		{
			$option = JRequest::getCmd('option');
			$cache = JFactory::getCache($option, 'view');

			if (is_array($urlparams))
			{
				$app = JFactory::getApplication();

				$registeredurlparams = $app->get('registeredurlparams');

				if (empty($registeredurlparams))
				{
					$registeredurlparams = new stdClass;
				}

				foreach ($urlparams as $key => $value)
				{
					// Add your safe url parameters with variable type as value {@see JFilterInput::clean()}.
					$registeredurlparams->$key = $value;
				}

				$app->set('registeredurlparams', $registeredurlparams);
			}

			$cache->get($view, 'display');

		}
		else
		{
			$view->display();
		}

		return $this;
	}
	
	public function includeList() {
		$this->updateList(1);	
	}
	
	public function unincludeList() {
		$this->updateList(0);
	}
	
	protected function updateList($status = 0) {
		$id = JRequest::getVar('id');
		
		$query = "UPDATE #__wbty_audio_manager_audio_files SET include_in_list=".(int)$status." WHERE id=".(int)$id;
		JFactory::getDbo()->setQuery($query)->query();
		
		JFactory::getApplication()->redirect('index.php?option=com_wbty_audio_manager&view=audio_files');
		exit();
	}
}