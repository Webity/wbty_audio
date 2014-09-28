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
 * View for the control panel
 */
class Wbty_audio_managerViewControlPanel extends JView
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

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and hide the toolbar.
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'wbty_audio_manager.php';

		$state	= $this->get('State');
		$canDo	= Wbty_audio_managerHelper::getActions($state->get('filter.category_id'));
		
		JToolBarHelper::title(JText::_('COM_WBTY_AUDIO_MANAGER_TITLE_CONTROLPANEL'), 'controlpanel.png');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_wbty_audio_manager');
		}

	}
}
