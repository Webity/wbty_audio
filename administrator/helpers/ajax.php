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

/**
 * Wbty_audio_manager helper.
 */
class Wbty_audio_managerHelperAjax
{
	private function getLinkModel($name) {
		require_once (JPATH_COMPONENT. '/models/'.$name.'.php' );
		$model = JModel::getInstance( $name, 'Wbty_audio_managerModel' );
		return $model;
	}
	
	private function link_html($link_name ='', $key = '{user_groups}', $link) {
		if (!$link_name) {
			return '';
		}
		$string .= '<li id="'.$link_name.$key.'"><fieldset>';
		foreach ($link->getFieldset('fields') as $field) {
			$string .= '
			<div' . ($field->__get('type')!='Hidden' ? ' class="control-group"': '') .'>
                '.str_replace('<label', '<label class="control-label"', $field->__get('label')).'
                <div class="controls">
                	'.$field->__get('input').'
                </div>
         	</div>';
		}
		$string .= '<div class="clr"></div><a href="#'.$key.'" class="link-remove btn btn-warning btn-small">Remove item</a></fieldset></li>';
		
		return $string;
	}
	
	function link () {
		$link_name = JRequest::getVar('link');
		$id = JRequest::getVar('id');
		$key = JRequest::getVar('key');
		
		$model = $this->getLinkModel($link_name);
		
		$this->link = $model->getForm(array(), false, $link_name."[{".$link_name."}]");
		return $this->link_html($link_name, '{' . $link_name . '}', $this->link);
	}
	
	function link_load($parent_key) {
		$link_name = JRequest::getVar('link');
		$id = JRequest::getVar('id');
		
		$model = $this->getLinkModel($link_name);
		
		$this->link = $model->getItems($id, $parent_key);
		
		if (is_array($this->link)) {
			foreach ($this->link as $key=>$link) {
				$return .= $this->link_html($link_name, $key, $link);
			}
		}
		$return .= '<a href="'.JRoute::_('index.php?option=com_wbty_audio_manager&view='.$link_name.'&layout=edit&tmpl=component').'" data-loader-url="'.JURI::root(true) . '/media/wbty_audio_manager/img/load.gif" id="'.$link_name.'add" class="link-add btn btn-primary">Add</a>';
		return $return;
	}
}
