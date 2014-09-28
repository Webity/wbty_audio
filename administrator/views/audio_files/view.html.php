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
 * View class for a list of Wbty_audio_manager.
 */
class Wbty_audio_managerViewAudio_Files extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		
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
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'wbty_audio_manager.php';

		$state	= $this->get('State');
		$canDo	= Wbty_audio_managerHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_WBTY_AUDIO_MANAGER_TITLE_AUDIO_FILES'), 'audio_files.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'audio_file';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('audio_file.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit')) {
			    JToolBarHelper::editList('audio_file.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('audio_files.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('audio_files.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'audio_files.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('audio_files.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('audio_files.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'audio_files.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('audio_files.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }


	}
}
