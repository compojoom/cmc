<?php
/**
 * Cmc
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );


class CmcControllersettings extends CmcController
{
    function __construct() {
        parent::__construct();
        $this->registerTask( 'apply'  , 'save' );
    }

    function save() {
        $post	  = JRequest::get('post');
        $tilesSet = JRequest::getVar( 'cmcset', array(0), 'post', 'array' );
//        var_dump($tilesSet);
//        die("asdf");

        require_once(JPATH_COMPONENT.DS.'models'.DS.'settings.php');
        $model=new CmcModelSettings;

        switch ( JRequest::getCmd('task') ) {
            case 'apply':

                if ($model->store($tilesSet)) {
                    $msg = JText::_( 'COM_CMC_CHANGES_TO_TILES_SETTINGS_SAVED' );
                } else {
                    $msg = JText::_( 'COM_CMC_ERROR_SAVING_TILES_SETTINGS' );
                }
                $this->setRedirect( 'index.php?option=com_cmc&view=settings', $msg );
                break;

            case 'save':
            default:
                if ($model->store($tilesSet)) {
                    $msg = JText::_( 'COM_CMC_SETTINGS_SAVED' );
                } else {
                    $msg = JText::_( 'COM_CMC_ERROR_SAVING_CMC_SETTINGS' );
                }
                $this->setRedirect( 'index.php?option=com_cmc', $msg );
                break;
        }

        $model->checkin();
    }

    public function display($cachable = false, $urlparams = false)
    {
        $document = JFactory::getDocument();
        $viewName = JRequest::getVar( 'view', 'settings' );


        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        require_once(JPATH_COMPONENT.DS.'models'.DS.'settings.php');
        $model=new CmcModelSettings;

        //$model =& $this->getModel( 'settings', 'HotspotsSettings' );
        //$model->checkin();

        $view->setModel($model, true);

        $view->setLayout('default');
        $view->display();
    }


    function cancel() {

        $this->setRedirect( 'index.php?option=com_cmc' );
    }

}
?>