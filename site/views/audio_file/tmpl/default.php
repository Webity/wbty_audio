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

//echo "<h2>Recorded Segments from WBTY</h2>";

JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js',false,true);

$db =& JFactory::getDbo();

$query = $db->getQuery(true);

$query->select('*');
$query->from('#__wbty_audio_manager_audio_files as f');

$query->where('f.state=1');
if ($id = JRequest::getVar('audio_id')) {
	$query->where('id='.(int)$id);
}
$query->order('f.created_time DESC');

$files = $db->setQuery($query, 0, 1)->loadObjectList();


if($files) :

JFactory::getDocument()->addScript(JURI::root(true). '/media/wbty_audio_manager/js/jquery.min.js');
JFactory::getDocument()->addScript(JURI::root(true). '/media/wbty_audio_manager/jquery.jplayer.min.js');
if (count($files) > 1) {
	JFactory::getDocument()->addScript(JURI::root(true). '/media/wbty_audio_manager/jplayer.playlist.min.js');
}
JFactory::getDocument()->addStyleSheet(JURI::root(true). '/media/wbty_audio_manager/blue.monday/jplayer.blue.monday.css');

ob_start();
?>

$(document).ready(function(){
<?php if (count($files) > 1) : ?>
	new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_<?php echo $files[0]->id; ?>",
		cssSelectorAncestor: "#jp_container_<?php echo $files[0]->id; ?>"
	}, [
    	<?php foreach ($files as $key=>$file): ?>
		{
			title:"<?php echo $file->title; ?>",
			mp3:"<?php echo JURI::root(true).$file->file_name; ?>"
		}<?php if ($key+1 < count($files)) {echo ',';} ?>
		<?php endforeach; ?>
	], {
		swfPath: "<?php echo JURI::root(true).'/media/wbty_audio_manager/Jplayer.swf'; ?>",
		supplied: "mp3",
		wmode: "window"
	});
<?php else: ?>
  $("#jquery_jplayer_<?php echo $files[0]->id; ?>").jPlayer({
    ready: function () {
      $(this).jPlayer("setMedia", {
        mp3: "<?php echo JURI::root(true).$files[0]->file_name; ?>",
      });
    },
	cssSelectorAncestor: '#jp_container_<?php echo $files[0]->id; ?>',
    swfPath: "<?php echo JURI::root(true).'/media/wbty_audio_manager/Jplayer.swf'; ?>",
    supplied: "mp3"
  });
<?php endif; ?>
	var played, ended = false;
	$("#jquery_jplayer_<?php echo $files[0]->id; ?>").bind($.jPlayer.event.play, function (event) {
    	if (played) { return; }
        played = true;
    	$.ajax({
        	url: '<?php echo JURI::root(true).'/index.php?option=com_wbty_audio_manager&task=audio_file.play'; ?>',
            data: {id: event.jPlayer.status.src},
            type: 'POST',
            success: function(resp) {
            	console.log(resp);
            }
        });
    });
	$("#jquery_jplayer_<?php echo $files[0]->id; ?>").bind($.jPlayer.event.ended, function (event) {
    	if (ended) { return; }
        ended = true;
    	$.ajax({
        	url: '<?php echo JURI::root(true).'/index.php?option=com_wbty_audio_manager&task=audio_file.endings'; ?>',
            data: {id: event.jPlayer.status.src},
            type: 'POST',
            success: function(resp) {
            	console.log(resp);
            }
        });
    });
});

<?php
$script = ob_get_contents();
ob_end_clean();

JFactory::getDocument()->addScriptDeclaration($script);
?>
<div id="jquery_jplayer_<?php echo $files[0]->id; ?>" class="jp-jplayer"></div>
<div id="jp_container_<?php echo $files[0]->id; ?>" class="jp-audio">
<div class="jp-type-single">
  <div class="jp-gui jp-interface">
    <ul class="jp-controls">
      <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
      <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
      <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
      <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
      <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
      <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
    </ul>
    <div class="jp-progress">
      <div class="jp-seek-bar">
        <div class="jp-play-bar"></div>
      </div>
    </div>
    <div class="jp-volume-bar">
      <div class="jp-volume-bar-value"></div>
    </div>
    <div class="jp-time-holder">
      <div class="jp-current-time"></div>
      <div class="jp-duration"></div>
      <ul class="jp-toggles">
        <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
        <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
      </ul>
    </div>
  </div>
  <div class="jp-title">
    <ul>
      <li><?php echo $files[0]->title; ?></li>
    </ul>
  </div>
  <div class="jp-playlist">
    <ul>
      <li></li>
    </ul>
  </div>
  <div class="jp-no-solution">
    <span>Update Required</span>
    To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
  </div>
</div>
</div>

<?php else: ?>
<p>Audio file failed to load...</p>

<?php endif; ?>