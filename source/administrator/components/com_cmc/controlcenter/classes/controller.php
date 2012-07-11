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

defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

class ControlCenterController extends JController {
    private $jversion = '15';

    /**
     * Object contructor
     * @param array $config
     *
     * @return ControlCenterController
     */
    public function __construct($config = array())
    {
        parent::__construct();

        // Do we have Joomla! 1.6?
        if( version_compare( JVERSION, '1.6.0', 'ge' ) ) {
            $this->jversion = '16';
        }

        $basePath = dirname(__FILE__);
        if($this->jversion == '15') {
            $this->_basePath = $basePath;
        } else {
            $this->basePath = $basePath;
        }

        $this->registerDefaultTask('overview');
    }

    /**
     * Runs the eventlist page task
     */
    public function overview()
    {
        $this->display();
    }

    /**
     * Displays the current view
     * @param bool $cachable Ignored!
     */
    public final function display($cachable = false)
    {
        $viewLayout	= JRequest::getCmd( 'layout', 'overview' );

        $view = $this->getThisView();

        // Get/Create the model
        //$model = $this->getThisModel();
        //$view->setModel($model, true);

        // Assign the FTP credentials from the request, or return TRUE if they are required
        // jimport('joomla.client.helper');
        // $ftp	= $this->setCredentialsFromRequest('ftp');
        // $view->assignRef('ftp', $ftp);

        // Set the layout
        $view->setLayout($viewLayout);

        // Display the view
        $view->display();
    }

    public final function getThisView()
    {
        static $view = null;

        if(is_null($view))
        {
            $basePath = ($this->jversion == '15') ? $this->_basePath : $this->basePath;
            $tPath = dirname(__FILE__).'/tmpl';

            require_once('view.php');
            $view = new ControlCenterView(array('base_path'=>$basePath, 'template_path'=>$tPath));
        }

        return $view;
    }

}
