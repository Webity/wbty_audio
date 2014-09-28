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

jimport('joomla.application.component.view');

/**
 * View to edit
 */
class Wbty_audio_managerViewAudio_File extends JView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
        if (isset($this->item->checked_out)) {
		    $checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
        } else {
            $checkedOut = false;
        }
		$canDo		= Wbty_audio_managerHelper::getActions();
		$isForm		= (JRequest::getVar('layout')=='edit' ? true : false);

		JToolBarHelper::title(JText::_('COM_WBTY_AUDIO_MANAGER_TITLE_AUDIO_FILE'), 'audio_file.png');

		// If not checked out, can save the item.
		if (!$checkedOut && $isForm && ($canDo->get('core.edit')||($canDo->get('core.create'))))
		{

			JToolBarHelper::apply('audio_file.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('audio_file.save', 'JTOOLBAR_SAVE');
		}
		if (!$checkedOut && $isForm && ($canDo->get('core.create'))){
			JToolBarHelper::custom('audio_file.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $isForm && $canDo->get('core.create')) {
			JToolBarHelper::custom('audio_file.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id)) {
			JToolBarHelper::cancel('audio_file.cancel', 'JTOOLBAR_CANCEL');
		}
		elseif (!$isForm) {
			JToolBarHelper::back('JTOOLBAR_BACK');
		}
		else {
			JToolBarHelper::cancel('audio_file.cancel', 'JTOOLBAR_CLOSE');
		}

	}
}
