<?php

/**
 * @version     1.0.0
 * @package     com_wbty_audio_manager
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Webity <david@makethewebwork.com> - http://www.makethewebwork.com
 */
defined('_JEXEC') or die;

jimport('wbty_components.models.wbtymodellist');

/**
 * Methods supporting a list of Wbty_audio_manager records.
 */
class Wbty_audio_managerModelaudio_file_articles extends WbtyModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();
		
		$this->audio_file_id=JRequest::getVar('audio_file_id',0,'get');
		
		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
        $this->setState('list.start', $limitstart);

        // List state information.
        parent::populateState();
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        $query->from('`#__wbty_audio_manager_audio_file_articles` AS a');

        
		
        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = '.(int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }
		
		$search = JRequest::getVar('audio_file_articles', array(), 'post', 'ARRAY');
		if ($search) {
			$this->processSearch($query, $search, array());
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
                $query->where('( a.article LIKE '.$search.' )');
			}
		}
		
		if ($this->audio_file_id) {
			$query->where('a.audio_file_id = '.(int)$this->audio_file_id);
		}
        


        return $query;
    }

}
