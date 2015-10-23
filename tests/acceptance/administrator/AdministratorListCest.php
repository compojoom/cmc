<?php
/**
 * @package    CMC
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       23.10.15
 *
 * @copyright  Copyright (C) 2008 - 2015 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use \AcceptanceTester;

class AdministratorListCest
{
	public function __construct()
	{
		$this->faker      = Faker\Factory::create();
	}

	/**
	 * @param \AcceptanceTester $I
	 */
	public function synchronizeList(AcceptanceTester $I)
	{
		$I->am('Administrator');
		$I->wantToTest('Synchronize CMC Lists in /administrator/');
		$I->doAdministratorLogin();
		$I->amGoingTo('Navigate to CMC List page in /administrator/');
		$I->amOnPage('administrator/index.php?option=com_matukio&view=lists');
		$I->waitForText('Lists', '30', ['css' => 'h1']);
		$I->expectTo('see CMC List page');
		$I->checkForPhpNoticesOrWarnings();

		// TODO
	}
}
