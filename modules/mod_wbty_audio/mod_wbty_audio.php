<?php defined('_JEXEC') or die('Restricted access');

$db =& JFactory::getDbo();
	
$query = $db->getQuery(true);
	
$query->select('*');
$query->from('#__wbty_audio_manager_audio_files as f');
$query->where('f.state = 1');
if($params->get('file') == 'newest')
{
	$query->order('f.modified_time DESC');
}
else if($params->get('file') == 'oldest')
{
	$query->order('f.modified_time ASC');
}
else if($params->get('file') == 'random')
{
	$query->order('RAND()');
}
else
{
	$query->where('f.id =' . (int)$params->get('file'));
}

$files = $db->setQuery($query, 0 , 1)->loadObjectList();
	
// echo '<pre>';
// var_dump($files);
// exit();

require JModuleHelper::getLayoutPath('mod_wbty_audio', 'default');
?>