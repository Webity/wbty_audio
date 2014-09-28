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
}