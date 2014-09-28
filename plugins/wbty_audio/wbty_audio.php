<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Email cloack plugin class.
 *
 * @package		Joomla.Plugin
 * @subpackage	Content.wbty_audio
 */
class plgContentWbty_audio extends JPlugin
{
	var $script_added = false;
	
	public function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		if (is_object($row)) {
			return $this->_scan($row->text);
		}
		return $this->_scan($row);
	}

	protected function _scan(&$text)
	{
		for ($i=0; $i<8; $i++) {
			$text = preg_replace_callback('/{wbty_audio(.*?)}/si',
					  array(get_class($this),'_buildAudio'),
					  $text);
		}
		return true;
	}
	
	protected function _buildAudio($matches) {
		
		if (!$matches[1]) {
			return '';
		}
		$matches[1] = str_replace('prompts[]', 'prompts', $matches[1]);
		
		try {
			$attr = new SimpleXMLElement("<element ".$matches[1]." />");
		} catch(Exception $e) {
			$attr = array();
		}

		// required attribute is either category or prompts
		if (!$attr['audio_id']) {
			return '';
		}
		
		$id = $attr['audio_id'] ? (string)$attr['audio_id'] : 0;
		
		$saveid = JRequest::getVar('id');
		JRequest::setVar('audio_id', $id);
		
		ob_start();
		require(JPATH_ROOT . '/components/com_wbty_audio_manager/views/audio_file/tmpl/default.php');
		$output = ob_get_contents();
		ob_end_clean();
		
		if ($saveid) {
			JRequest::setVar('id', $saveid);
		}
		return $output;
	}
}
