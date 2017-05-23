<?php

defined('_JEXEC') or die;

require_once('OAuth2Client.php');
require_once('OAuth2Exception.php');

/**
 * @package     ${NAMESPACE}
 *
 * @since       version
 */
class MC_OAuth2Client extends OAuth2Client {

    public $access_token = null;
    public $refresh_token = null;
    public $instance_url = null;

	const CLIENT_ID = '841352726332';
	const SECRET = '';

	public function __construct() {

        //basically, your redirect_url
        $base =  '';   //your host
        $path = '/path/onyourhost/complete.php'; //the path on your host
            
        $config = array('client_id'=>MC_OAuth2Client::CLIENT_ID, 'client_secret'=>MC_OAuth2Client::SECRET,
                        'authorize_uri'=>'https://login.mailchimp.com/oauth2/authorize',
                        'access_token_uri'=>'https://login.mailchimp.com/oauth2/token',
                        'redirect_uri'=>'https://compojoom.com/cmc/auth.php',
                        'cookie_support'=>false,
	                    'file_upload'=>false,
                        'token_as_header'=>true,
                        'base_uri'=>'https://login.mailchimp.com/oauth2/'
                        );
   
        parent::__construct($config);
    }
    
    /**
    * Get a Login URL for use with redirects. A full page redirect is currently
    * required.
    *
    * @param $params
    *   Provide custom parameters.
    *
    * @return
    *   The URL for the login flow.
    */
    public function getLoginUri($params = array()) {
        $def_params = array(
                        'response_type' => 'code',
                        'client_id' => $this->getVariable('client_id'),
                        'redirect_uri' => $this->getVariable('redirect_uri'),
                    );
        $params = array_merge($params, $def_params);
        return $this->getUri( $this->getVariable('authorize_uri'), $params);
    }

    public function api($path, $method = 'GET', $params = array()) {
        try {
            return parent::api($path, $method, $params);
        } catch (OAuth2Exception $e){
            //once and only once, try to get use the refresh token to get a fresh token
            if ($e->getMessage()=='INVALID_SESSION_ID'){
                $this->refreshToken();
                return parent::api($path, $method, $params);
            } else {
                throw $e;
            }
        }

    }


    /**
    * Ignore this, MailChimp is not using refresh tokens. However, this is working code we used for SalesForce
    *
    * @param $params
    *   Provide custom parameters.
    *
    * @return
    *   The URL for the login flow.
    */
    public function refreshToken(){
        $auth = ExternalAuth::load(ExternalSystem::SALESFORCE,true);
        $orig_data = $auth->getInfo();
        
        $session = $this->getVariable('_session');

        if (!$orig_data['oauth']['refresh_token']) throw new OAuth2Exception( array('Invalid refresh token, please re-authorize your account') );
        $params = array(
                    'grant_type'=>'refresh_token',
                    'client_id'=>$this->getVariable('client_id'),
                    'client_secret'=>$this->getVariable('client_secret'),
                    'refresh_token'=>$orig_data['oauth']['refresh_token']
                    );

        $this->setVariable('token_as_header', false);
        $data = $this->makeRequest($this->getVariable('access_token_uri'), 'POST', $params);
        $this->setVariable('token_as_header', true);
        
        $data = json_decode($data,true);

        if (!$data['access_token']) throw new OAuth2Exception( array('Invalid Session, please re-authorize your account') );

        $data['refresh_token'] = $orig_data['oauth']['refresh_token'];
        
        $session = $this->getSessionObject($data);
        $orig_data['oauth'] = $session;
        
        $auth->setInfo($orig_data);
        $auth->username = null;
        $auth->password = null;
        $auth->api_key  = null;
        $auth->save();

        $this->setSession($session,false);
    }

}
