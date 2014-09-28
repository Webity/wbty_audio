<?php
/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php if (JRequest::getVar('tmpl')=='component') {echo JToolbar::getInstance('toolbar')->render();} ?>
<ul class="itemlist">
            
	
					<li><?php echo JText::_('COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_FILE_NAME'); ?>: <?php echo $this->item->file_name; ?></li>
					<li><?php echo JText::_('COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_TITLE'); ?>: <?php echo $this->item->title; ?></li>
					<li><?php echo JText::_('COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_DESCRIPTION'); ?>: <?php echo $this->item->description; ?></li>

</ul>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager{parent_url}&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="audio_file-form" class="form-validate form-horizontal">
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="option" id="option" value="com_wbty_audio_manager" />
    <input type="hidden" name="form_name" id="form_name" value="audio_file" />
    <?php echo JHtml::_('form.token'); ?>
</form>