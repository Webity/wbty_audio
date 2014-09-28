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

<form action="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&view=audio_files'.$append); ?>" method="post" name="adminForm" id="adminForm">
<?php if (JRequest::getVar('tmpl') != 'component') : ?>
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<button class="btn" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button class="btn" type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

            
                <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                    <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true);?>
                </select>
                

		</div>
	</fieldset>
<?php else: ?>
	<a href="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&task=audio_file.add'.$append); ?>" class="btn btn-primary fltlft">Add New File</a>
    <div class="input-append fltrt">
    	<input type="text" name="search_text" class="span3" />
        <button class="btn" style="margin-top:0;" onclick="jQuery('input[name=task]').val('audio_files.search');jQuery('form').submit();">Search for File</button>
    </div>
<?php endif; ?>
	<div class="clr"> </div>
    <p></p>

	<table class="adminlist table table-striped table-bordered">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
                <th></th>
                
                
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_FILE_NAME', '.file_name', $listDirn, $listOrder); ?>
					</th>
					
					<th>
						<?php echo JHtml::_('grid.sort',  'COM_WBTY_AUDIO_MANAGER_FORM_LBL_AUDIO_FILES_TITLE', '.title', $listDirn, $listOrder); ?>
					</th>
                    <th>
                    	Include in List
                    </th>
                    <th> Plays </th>
                    <th> Completed </th>
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
                <td class="center">
                	<div class="btn-group">
                      <span class="btn dropdown-toggle" data-toggle="dropdown">Actions</span>
                      <span class="btn dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>&nbsp;
                      </span>
                      <ul class="dropdown-menu">
                        <?php
                        echo '<li><a href="'. JRoute::_('index.php?option=com_wbty_audio_manager&view=audio_file&layout=default&id='.$item->id.$append).'">View</a></li>';
						if ($canEdit) {
	                        echo '<li><a href="'. JRoute::_('index.php?option=com_wbty_audio_manager&task=audio_file.edit&id='.$item->id.$append).'">Edit</a></li>';
						}
                        ?>
                      </ul>
                    </div>
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
                        
                        <td>
                        	<?php if ($item->include_in_list) : ?>
                            	<a class="jgrid" href="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&task=audio_files.unincludeList&id='.$item->id); ?>"title="Publish Item"><span class="state publish"><span class="text">Published</span></span></a>
                            <?php else: ?>
                        		<a class="jgrid" href="<?php echo JRoute::_('index.php?option=com_wbty_audio_manager&task=audio_files.includeList&id='.$item->id); ?>" title="Unpublish Item"><span class="state unpublish"><span class="text">Unpublished</span></span></a>
                            <?php endif; ?>
                        </td>
                        
                        <td><?php echo $item->plays; ?></td>
                        <td><?php echo $item->endings; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php if (JRequest::getVar('tmpl')=='component') : ?>

    <a href="#" onclick="jQuery('input[name=task]').val('audio_files.remove_files');jQuery('form').submit();" class="btn btn-primary fltrt">Remove Checked Audio File(s) From Article</a>

<?php endif; ?>
	<div>
	    
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>