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

<ul class="itemlist">
            
	
					<li><?php echo JText::_('COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILE_ARTICLES_ARTICLE'); ?>: <?php echo $this->item->article; ?></li>

</ul>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&audio_file_id='.JRequest::getCmd('audio_file_id').'&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="audio_file_article-form" class="form-validate form-horizontal">
	<input type="hidden" name="task" value="" />
    <input type="hidden" name="option" id="option" value="com_wbty_audio_manager" />
    <input type="hidden" name="form_name" id="form_name" value="audio_file_article" />
    <?php echo JHtml::_('form.token'); ?>
</form>