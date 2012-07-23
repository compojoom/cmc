<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 23.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class CmcControllerSubscription extends JController {

    public function save() {
        $chimp = new cmcHelperChimp();

        $elements = JRequest::get( 'post');
        $elements = (object)($elements);

        $listId = $elements->listid;
        $email = $elements->EMAIL;
        $userId = $elements->userId;
        if(isset($elements->FNAME)){
            $merge['FNAME'] = $elements->FNAME;
        }
        if(isset($elements->LNAME)){
            $merge['LNAME'] = $elements->LNAME;
        }
        $merge['OPTINIP'] = $elements->ip;

        $thankyouMsg = $elements->thankyouMsg;
        $updateMsg = $elements->updateMsg;

        $merges = $elements->merges;
        $mergesArray = array_filter(explode('|', $merges));
        foreach($mergesArray as $m){
            if( stristr( $m, '#*#' ) ){
                $mArray = explode('#*#', $m);
                $mergeVars[$mArray[0]][$mArray[1]] = $elements->{$m};
            } else if( stristr( $m, '*#*' ) ){
                $mArray = explode('*#*', $m);
                if( isset($mergeVars[$mArray[0]]) ){
                    $mergeVars[$mArray[0]] .= '-'.$elements->{$m};
                } else {
                    $mergeVars[$mArray[0]] = $elements->{$m};
                }
            } else if( stristr( $m, '***' ) ){
                $mArray = explode('***', $m);
                $mergeVars[$mArray[0]][$mArray[1]] = $elements->{$m};
            } else {
                $mergeVars[$m] = $elements->{$m};
            }
        }

        $groups = $elements->groups;
        $groupsArray = array_unique(array_filter(explode('|', $groups)));

        foreach($groupsArray as $g){
            if($elements->{$g}){
                if($elements->{$g}[strlen($elements->{$g})-1] == ',') { $elements->{$g} = substr($elements->{$g}, 0, -1); }
                $mergeVars['GROUPINGS'][] = array( 'id' => $g, 'groups' => $elements->{$g});
            }
        }

        $userlists = $chimp->listsForEmail($email);
        if($userlists && in_array($listId,$userlists)) {
            $updated = true;
        } else {
            $updated = false;
        }

        $subscribe = $chimp->listSubscribe( $listId, $email, $mergeVars, 'html', true, true, true, false );


        if ( $chimp->errorCode ) {
            $response['html'] = $chimp->errorMessage;
            $response['error'] = true;
        } else {
            $db = & JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->insert('#__cmc_users')->columns('list_id,email')->values($db->quote($listId).','.$db->quote($email));
            $db->setQuery($query);
            $db->Query();
            $response['html'] = ($updated) ? $updateMsg : $thankyouMsg;
            $response['error'] = false;
        }

        echo json_encode( $response );
    }
}

