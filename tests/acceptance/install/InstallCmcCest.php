<?php
/**
 * @package    CMC
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       23.10.15
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

class InstallCmcCest
{
	public function installJoomla(\AcceptanceTester $I)
	{
		$I->am('Administrator');
		$I->installJoomla();
		$I->doAdministratorLogin();
		$I->setErrorReportingToDevelopment();
	}

	/**
	 * @depends installJoomla
	 */
	public function installCmc(\AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
		$I->comment('get cmc package from acceptance.suite.yml (see _support/AcceptanceHelper.php)');
		$path = $I->getConfiguration('repo_folder');
		$I->installExtensionFromFolder($path);
		$I->doAdministratorLogout();
	}

	/**
	 * @depends installCmc
	 */
	public function initializeCmcSettings(\AcceptanceTester $I)
	{
		$I->doAdministratorLogin();
		$I->comment('Mailchimp API Key from acceptance.suite.yml (see _support/AcceptanceHelper.php)');
		$key = $I->getConfiguration('api_key');

		$I->amGoingTo('Navigate to CMC page in /administrator/');
		$I->amOnPage('administrator/index.php?option=com_cmc&view=cpanel');

		// Wait for text dashboard - wait for text not working
		$I->waitForElementVisible('#ctitle');

		$I->checkForPhpNoticesOrWarnings();

		// Options
		$I->click('Options');
		$I->waitForText('Configuration', '30', ['css' => 'h1']);

		$I->fillField(['id' => 'jform_api_key'], $key);
		$I->click('Save & Close');

		// Basck in the dashboard
		$I->waitForElementVisible('#ctitle');

		$I->doAdministratorLogout();
	}
}
