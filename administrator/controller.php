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

class Wbty_audio_managerController extends JController
{
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 * @since	1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
		require_once JPATH_COMPONENT.'/helpers/wbty_audio_manager.php';

		// Load the submenu.
		Wbty_audio_managerHelper::addSubmenu(JRequest::getCmd('view', 'controlpanel'));

		$view		= JRequest::getCmd('view', 'controlpanel');
        JRequest::setVar('view', $view);

		parent::display();

		return $this;
	}
}
