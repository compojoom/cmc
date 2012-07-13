<?php
/**
 * CmC
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 stable $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');


class CmcControllerWebhook extends JController
{
    public function display()
    {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar('view', 'webhook');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $model = $this->getModel('webhook', 'CmcModel');
        $view->setModel($model, true);
        $view->setLayout('default');
        $view->display();
    }
}

