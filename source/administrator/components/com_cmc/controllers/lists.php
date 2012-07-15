<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

class CmcControllerLists extends CmcController {

    public function __construct() {
        parent::__construct();
        // Register Extra tasks
//        $this->registerTask('unpublish', 'publish');
//        // Register Extra tasks
//        $this->registerTask('addList', 'editList');
//        $this->registerTask('apply', 'save');
    }

    /**
     * @param bool $cachable
     * @param bool $urlparams
     */
    public function display($cachable = false, $urlparams = false) {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'lists');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('Lists', 'CmcModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }

    function cancel() {
        $link = 'index.php?option=com_cmc&view=lists';
        $this->setRedirect($link);
    }

}