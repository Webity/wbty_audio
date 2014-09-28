<?php
/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Wbty_audio_manager.
 */
class Wbty_audio_managerViewaudio_file_articles extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
    protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $app                = JFactory::getApplication();
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
        $this->params       = $app->getParams('com_wbty_audio_manager');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

        $this->_prepareDocument();
        $this->addToolbar();
		
		parent::display($tpl);
	}


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_wbty_audio_manager_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}    

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'wbty_audio_manager.php';

		$state	= $this->get('State');
		$canDo	= Wbty_audio_managerHelper::getActions($state->get('filter.category_id'));

		//load the JToolBar library and create a toolbar
		jimport('joomla.html.toolbar');
		$bar = new JToolBar( 'toolbar' );

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.'audio_file_article';
	
		if (file_exists($formPath)) {
            if ($canDo->get('core.create')) {
				$bar->appendButton( 'Standard', 'new', 'New', 'audio_file_article.add', false );
			   // JToolBarHelper::addNew('routine.add','JTOOLBAR_NEW');
		    }
		    if ($canDo->get('core.edit')) {
				$bar->appendButton( 'Standard', 'edit', 'Edit', 'audio_file_article.edit', false );
				
				if (isset($this->items[0]->state)) {
					//$bar->appendButton( 'Standard', 'archive', 'Archive', 'audio_file_articles.archive', false );
				}
				if (isset($this->items[0]->checked_out)) {
					$bar->appendButton( 'Standard', 'checkin', 'Check In', 'audio_file_articles.checkin', false );
				}
		    }
			
			if ($canDo->get('core.edit.state')) {
				$bar->appendButton( 'Standard', 'trash', 'Trash', 'audio_file_articles.trash', false );
			}
			
        }
	
		if ($canDo->get('core.admin')) {
			//JToolBarHelper::preferences('com_fsfitness');
		}
		
		return $bar->render();
	}
    	
}
