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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$document = &JFactory::getDocument();
$document->addScript(JURI::root(true) . "/media/wbty_audio_manager/js/linked_tables.js");
//$document->addScript(JURI::root(true) . "/media/wbty_audio_manager/js/table_select.js");


ob_start();
// start javascript output -- script
?>
window.addEvent('domready', function(){
    // save validator, getting overwritten by AJAX call
    document.audio_filevalidator = document.formvalidator;
    jQuery('#audio_file-form .toolbar-list a').each(function() {
    	$(this).attr('data-onclick', $(this).attr('onclick')).attr('onclick','');
    });
    jQuery('#audio_file-form .toolbar-list a').click(function() { 
    	Joomla.submitbutton = document.audio_filesubmitbutton;
        
        // clean up hidden subtables
        jQuery('.subtables:hidden').remove();
        
        eval($(this).attr('data-onclick'));
    });
});
Joomla.submitbutton = function(task)
{
    if (jQuery('#sbox-window').attr('aria-hidden')==true) {
    	Joomla.submitform = defaultsubmitform;
    }
    
    if (task == 'audio_file.cancel' || document.audio_filevalidator.isValid(document.id('audio_file-form'))) {
        Joomla.submitform(task, document.getElementById('audio_file-form'));
    }
    else {
        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
    }
}
document.audio_filesubmitbutton = Joomla.submitbutton;
<?php
// end javascript output -- /script
$script=ob_get_contents();
ob_end_clean();
$document->addScriptDeclaration($script);
?>

<form action="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="audio_file-form" class="form-validate form-horizontal">
<?php echo $this->addToolbar(); ?>
<div class="clr"></div>
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_WBTY_AUDIO_MANAGER_LEGEND_AUDIO_FILE'); ?></legend>
            
            <div class="control-group">
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('id')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('id'); ?>
                </div>
         	</div>
            
            
			<div<?php if (strpos($this->form->getInput('file_name'), 'type="hidden')===FALSE) { echo ' class="control-group"'; } ?>>
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('file_name')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('file_name'); ?>
                </div>
         	</div>
			<div<?php if (strpos($this->form->getInput('title'), 'type="hidden')===FALSE) { echo ' class="control-group"'; } ?>>
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('title')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('title'); ?>
                </div>
         	</div>
			<div<?php if (strpos($this->form->getInput('description'), 'type="hidden')===FALSE) { echo ' class="control-group"'; } ?>>
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('description')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('description'); ?>
                </div>
         	</div>

            

            <div<?php if (strpos($this->form->getInput('state'), 'type="hidden')===FALSE) { echo ' class="control-group"'; } ?>>
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('state')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('state'); ?>
                </div>
         	</div>
            <div<?php if (strpos($this->form->getInput('checked_out'), 'type="hidden')===FALSE) { echo ' class="control-group"'; } ?>>
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('checked_out')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('checked_out'); ?>
                </div>
         	</div>
            <div<?php if (strpos($this->form->getInput('checked_out_time'), 'type="hidden')===FALSE) { echo ' class="control-group"'; } ?>>
                <?php echo str_replace('<label', '<label class="control-label"', $this->form->getLabel('checked_out_time')); ?>
                <div class="controls">
                	<?php echo $this->form->getInput('checked_out_time'); ?>
                </div>
         	</div>

		</fieldset>
        
	</div>
        
	<?php // fieldset for each linked table  ?>
    <div class="width-50 fltlft">
<?php
// Add hidden form fields so as to run neccesary scripts for any modals, ect.
require_once(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/ajax.php');
$helper = new wbty_audio_managerHelperAjax;
?>
		<fieldset class="adminform">
        	<legend>Audio File Articles</legend>
        	<ul id="audio_file_article" >
				<?php
				JRequest::setVar('link', 'audio_file_article');
				echo $helper->link_load('audio_file_id');
				?>
            </ul>
		</fieldset></div>


	<input type="hidden" name="task" value="" />
    <input type="hidden" name="option" id="option" value="com_wbty_audio_manager" />
    <input type="hidden" name="form_name" id="form_name" value="audio_file" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>