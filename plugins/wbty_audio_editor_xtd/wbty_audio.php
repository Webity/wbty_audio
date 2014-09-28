<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Editor Article buton
 *
 * @package		Joomla.Plugin
 * @subpackage	Editors-xtd.article
 * @since 1.5
 */
class plgButtonWbty_audio extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}


	/**
	 * Display the button
	 *
	 * @return array A four element array of (article_id, article_title, category_id, object)
	 */
	function onDisplay($name)
	{
		/*
		 * Javascript to insert the link
		 * View element calls jSelectArticle when an article is clicked
		 * jSelectArticle creates the link tag, sends it to the editor,
		 * and closes the select frame.
		 */
		$js = "
		function wbty_audio(form_data) {
			console.log(form_data);
			var tag = '{' + form_data.extension;
			var wrap_selection = false;
			$.each(form_data, function(key, item) {
				if (key=='extension') {return;}
				if (key=='wrap_selection' && item == 'true') {wrap_selection = true; return;}
				
				console.log(item.length);
				if (item.length > 1) {
					key = key.replace('[]','');
				}
				tag = tag + ' '+key+'=\"'+item+'\"';
			});
			tag = tag + '}';
			
			if (wrap_selection) {
				node = jQuery(tinyMCE.activeEditor.selection.getNode());
				node.html('<p>' + tag + '</p>' + node.html() + '<p>{/' + form_data.extension + '}</p>');
			} else {
				jInsertEditorText(tag, '".$name."');
			}
			SqueezeBox.close();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);
		$doc->addScriptDeclaration("
	window.addEvent('load', function() {
		loadAudio();
	});
	function loadAudio() {
		if(!window.jQuery)
		{
		   var script = document.createElement('script');
		   script.type = \"text/javascript\";
		   script.src = \"//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\";
		   document.getElementsByTagName('head')[0].appendChild(script);
		}
	}
			");

		JHtml::_('behavior.modal');

		/*
		 * Use the built-in element view to select the article.
		 * Currently uses blank class.
		 */
		$link = 'index.php?option=com_wbty_audio_manager&view=audio_files&tmpl=component&amp;'.JSession::getFormToken().'=1&t='.time().'&article_id='.JRequest::getVar('id', 0);

		$button = new JObject();
		$button->set('modal', true);
		$button->set('link', $link);
		$button->set('text', JText::_('PLG_WBTY_AUDIO_BUTTON_WBTY_AUDIO'));
		$button->set('name', 'article');
		$button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

		return $button;
	}
}
