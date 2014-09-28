<?php
/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('wbty_components.controllers.wbtycontrollerform');

/**
 * audio_file controller class.
 */
class Wbty_audio_managerControllerAudio_File extends WbtyControllerForm
{

    function __construct() {
        $this->view_list = 'audio_files';
        parent::__construct();
		
		$this->_model = $this->getModel();
    }
	
	function back() {
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_list
				. $this->getRedirectToListAppend(), false
			)
		);
	}
	
	function link () {
		echo parent::link();
		exit();
	}
	
	function link_load() {
		echo parent::link_load('article_id');
		exit();
	}
	
	function ajax_save() {
		$this->model = $this->getModel();
		if ($id = $this->model->save(JRequest::getVar('jform'), array())) {
			echo $id;
		} else {
			echo "error";
		}
		exit();
	}
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$append = parent::getRedirectToItemAppend($recordId);
		$append .= '&article_id=' . JRequest::getVar('article_id');
		if ($tmpl = JRequest::getVar('tmpl')) {
			$append .='&tmpl=' . $tmpl;
		}

		return $append;
	}
	
	protected function getRedirectToListAppend()
	{
		$append = parent::getRedirectToListAppend();
		$append .= '&article_id=' . JRequest::getVar('article_id');
		if ($tmpl = JRequest::getVar('tmpl')) {
			$append .='&tmpl=' . $tmpl;
		}

		return $append;
	}
}