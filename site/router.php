<?php
/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */

/**
 * @param	array	A named array
 * @return	array
 */
function Wbty_audio_managerBuildRoute(&$query)
{
	$segments = array();
	
	$app =& JFactory::getApplication();
	$menu		= $app->getMenu();
	if (empty($query['Itemid'])) {
		$items = $menu->getMenu();
		$match = false;
		foreach ($items as $item) {
			if ($item->component == 'com_wbty_audio_manager') {
				$match = $item;
				break;
			}
		}
		
		if ($match) {
			$query['Itemid'] = $item->id;
			$menuItem = $menu->getItem($item->id);
			$menuItemGiven = true;
		} else {
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		}
	}
	else {
		$menuItem = $menu->getItem($query['Itemid']);
		$menuItemGiven = true;
	}
	
	if (isset($query['view'])&& strpos($query['view'],'.')===FALSE) {
		// by supporting tasks we do not support views with a period in them. Don't do it.
		$segments[] = $query['view'];
		unset($query['view']);
	} elseif (isset($query['task']) && strpos($query['task'],'.')!==FALSE) {
		// we can place a task in the view's position as long as it has a period in it to distinguish. Also you can't set a view and task without the task not being parsed
		$segments[] = $query['task'];
		unset($query['task']);
	} else {
		// skip parsing if no view or task is set. View is required
		return $segments;
	}
	if (isset($query['layout'])) {
		$segments[] = $query['layout'];
		unset($query['layout']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}
	if (isset($query['format'])) {
		$segments[] = $query['format'];
		unset($query['format']);
	}

	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/banners/task/id/Itemid
 *
 * index.php?/banners/id/Itemid
 */
function Wbty_audio_managerParseRoute($segments)
{
	$vars = array();

	// view is always the first element of the array
	$count = count($segments);

	if ($count)
	{
		$count--;
		$segment = array_shift($segments);
		if (strpos($segment, '.')===FALSE) {
			$vars['view'] = $segment;
		} else {
			$vars['task'] = $segment;
		}
	}

	while ($count)
	{
		$count--;
		$segment = array_shift($segments) ;
		if (is_numeric($segment)) {
			$vars['id'] = $segment;
		} else {
			if (!isset($vars['id'])) {
				$vars['layout'] = $segment;
			} else {
				$vars['format'] = $segment;
			}
		}
	}

	return $vars;
}
