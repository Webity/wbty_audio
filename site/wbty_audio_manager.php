<?php
/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

// Include base css and javascript files for the component
// Import CSS
$document = &JFactory::getDocument();
//$document->addStyleSheet(JURI::root(true) . "/media/wbty_audio_manager/css/wbty_audio_manager.css");
//$document->addStyleSheet(JURI::root(true) . "/media/wbty_audio_manager/css/site.css");
//$document->addStyleSheet(JURI::root(true) . "/media/wbty_audio_manager/css/redmond/jquery-ui-1.8.19.custom.css");
//$document->addStyleSheet(JURI::root(true) . "/media/wbty_audio_manager/css/bootstrap.min.css");

// Import Javascript
//$document->addScript(JURI::root(true) . "/media/wbty_audio_manager/js/jquery.min.js");
//$document->addScript(JURI::root(true) . "/media/wbty_audio_manager/js/jquery-ui-1.8.19.custom.min.js");
//$document->addScript(JURI::root(true) . "/media/wbty_audio_manager/js/bootstrap.min.js");

//include backend language file for jfield labels and descriptions
$lang =& JFactory::getLanguage();
$extension = '';
$base_dir = JPATH_ADMINISTRATOR;
$language_tag = 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);

// Execute the task.
$controller	= JController::getInstance('Wbty_audio_manager');
$controller->execute(JRequest::getVar('task',''));
$controller->redirect();
