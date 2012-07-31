<?php
/**
 * @package LiveUpdate
 * @copyright Copyright ©2011 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU LGPLv3 or later <http://www.gnu.org/copyleft/lesser.html>
 */

defined('_JEXEC') or die();

/**
 * Configuration class for your extension's updates. Override to your liking.
 */
class LiveUpdateConfig extends LiveUpdateAbstractConfig
{
	var $_extensionName			= 'com_cmc';
	var $_extensionTitle		= 'CMC - Mailchimp for Joomla!';
	var $_versionStrategy		= 'vcompare';
	var $_updateURL				= 'https://compojoom.com/index.php?option=com_ars&view=update&format=ini&id=13';
	var $_requiresAuthorization = false;
	/**
	var $_storageAdapter		= 'component';
	var $_storageConfig			= array(
			'extensionName'	=> 'com_akeebasubs',
			'key'			=> 'liveupdate'
		);
	*/
	
	public function __construct() {
		parent::__construct();
		
		// Dev releases use the "newest" strategy
		if(substr($this->_currentVersion,1,2) == 'ev') {
			$this->_versionStrategy = 'newest';
		}
	}
}