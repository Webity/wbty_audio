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
JHTML::_('script','system/multiselect.js',false,true);

$append = '&article_id='.JRequest::getVar('article_id');
$append .= (JRequest::getVar('tmpl')=='component' ? '&tmpl=component' : '');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_wbty_audio_manager');
$saveOrder	= $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&task=audio_files'.$append); ?>" method="post" name="adminForm" id="adminForm">
	<a href="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&view=audio_files'.$append); ?>" class="btn btn-primary fltlft">Cancel</a>
	<a href="#" class="btn btn-primary fltrt" onclick="jQuery('input[name=task]').val('audio_files.add_files');jQuery('form').submit();">Add to Article</a>
    <div class="clr"></div>
	<table class="adminlist table table-striped table-bordered">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
                
                
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_FILE_NAME', '.file_name', $listDirn, $listOrder); ?>
					</th>
					
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_TITLE', '.title', $listDirn, $listOrder); ?>
					</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'a.ordering');
			$canCreate	= $user->authorise('core.create',		'com_wbty_audio_manager');
			$canEdit	= $user->authorise('core.edit',			'com_wbty_audio_manager');
			$canCheckin	= $user->authorise('core.manage',		'com_wbty_audio_manager');
			$canChange	= $user->authorise('core.edit.state',	'com_wbty_audio_manager');
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
                
                
						<td>
							<?php if (isset($item->checked_out) && $item->checked_out) : ?>
								<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'audio_files.', $canCheckin); ?>
							<?php endif; ?>
							<?php echo $this->escape($item->file_name); ?>
						</td>
						
						<td>
							<?php echo $item->title; ?>
						</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
	    
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>