<?php
/**
 * Compojoom User Plugin
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::discover('cmcHelper', JPATH_ADMINISTRATOR . '/components/com_cmc/helpers/');

/**
 * Class plgUserCmc
 */
class plgUserCmc extends JPlugin
{

    /**
     * @param $context
     * @param $data
     * @return bool
     */
    function onContentPrepareData($context, $data)
    {
        // Check we are manipulating a valid form.
        if (!in_array($context, array('com_users.profile', 'com_users.user', 'com_users.registration', 'com_admin.profile'))) {
            return true;
        }

        if (is_object($data)) {

        }

        return true;
    }

    /**
     * @param $form
     * @param $data
     */
    function onContentPrepareForm($form, $data)
    {
        if (!($form instanceof JForm)) {
            $this->_subject->setError('JERROR_NOT_A_FORM');
            return false;
        }

        // Check we are manipulating a valid form.
        $name = $form->getName();
        if (!in_array($name, array('com_admin.profile', 'com_users.user', 'com_users.profile', 'com_users.registration'))) {
            return true;
        }



        return true;
    }

    /**
     * @param $user
     * @param $isNew
     * @param $data
     */

    function onUserBeforeSave($user, $isNew, $data)
    {
      //  var_dump($data);



    //    die("onsave");
    }


    /**
     * @param $data
     * @param $isNew
     * @param $result
     * @param $error
     */
    function onUserAfterSave($data, $isNew, $result, $error)
    {
        $userId	= JArrayHelper::getValue($data, 'id', 0, 'int');

        if ($userId && $result && isset($data['profile']) && (count($data['profile']))) {
            // Save data
        }

//        var_dump($data);

 //       die("asdf");

        return true;
    }



    /**
     * Remove all Cmc information for the given user ID
     *
     * Method is called after user data is deleted from the database
     *
     * @param	array		$user		Holds the user data
     * @param	boolean		$success	True if user was succesfully stored in the database
     * @param	string		$msg		Message
     */
    function onUserAfterDelete($user, $success, $msg)
    {
        if (!$success) {
            return false;
        }

        $userId	= JArrayHelper::getValue($user, 'id', 0, 'int');

        if ($userId) {
            // Delete User from mailing list?
        }

        return true;
    }
}