<?php
/**
 * @package    Matukio
 * @author     Yves Hoppe <yves@compojoom.com>
 * @date       20.09.15
 *
 * @copyright  Copyright (C) 2008 - 2015 Yves Hoppe - compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// PSR-4 Autoload by composer
require_once __DIR__ . '/vendor/autoload.php';

define('JPATH_BASE', __DIR__);

/**
 * RoboFile for CMC
 *
 * @since  5.3
 */
class RoboFile extends \Robo\Tasks
{
	use \JBuild\Tasks\loadTasks;
	use \joomla_projects\robo\loadTasks;

	private $extension = '';

	/**
	 * Initialize Robo
	 */
	public function __construct()
	{
		$this->stopOnFail(true);
	}

	/**
	 * Map into Joomla installation.
	 *
	 * @param   String   $target    The target joomla instance
	 * @param   boolean  $override  Override existing mappings?
	 *
	 * @return  void
	 */
	public function map($target, $override = true)
	{
		$this->taskMap($target)->run();
	}

	/**
	 * Build the Joomla extension package
	 *
	 * @param   array  $params  Additional params
	 *
	 * @return  void
	 */
	public function build($params = ['dev' => false])
	{
		$this->taskBuild($params)->run();
	}

	/**
	 * Set the Execute extension for Windows Operating System
	 *
	 * @return void
	 */
	private function setExecExtension()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			$this->extension = '.exe';
		}
	}

	/**
	 * Executes all the Selenium System Tests in a suite on your machine
	 *
	 * @param   string  $seleniumPath  Optional path to selenium-standalone-server-x.jar
	 * @param   string  $suite         Optional, the name of the tests suite
	 *
	 * @return mixed
	 */
	public function runTests($seleniumPath = null, $suite = 'acceptance')
	{
		$this->setExecExtension();

		if (!file_exists(JPATH_BASE . "/dist/current"))
		{
			$this->say('Please create a Matukio package for testing first - run robo build!');

			return false;
		}

		$this->createTestingSite();
		$this->getComposer();
		$this->taskComposerInstall()->run();
		$this->runSelenium();
		$this->_exec('php' . $this->extension . ' vendor/bin/codecept build');
		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->arg('tests/acceptance/install/')
			->run()
			->stopOnFail();
		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->arg('tests/acceptance/administrator/')
			->run()
			->stopOnFail();
		$this->taskCodecept()
			->arg('--steps')
			->arg('--debug')
			->arg('--fail-fast')
			->arg('tests/acceptance/frontend/')
			->run()
			->stopOnFail();
	}

	/**
	 * Executes a specific Selenium System Tests in your machine
	 *
	 * @param string $seleniumPath   Optional path to selenium-standalone-server-x.jar
	 * @param string $pathToTestFile Optional name of the test to be run
	 * @param string $suite          Optional name of the suite containing the tests, Acceptance by default.
	 *
	 * @return mixed
	 */
	public function runTest($pathToTestFile = null, $suite = 'acceptance')
	{
		$this->runSelenium();

		// Make sure to Run the Build Command to Generate AcceptanceTester
		$this->_exec("php vendor/bin/codecept build");

		if (!$pathToTestFile)
		{
			$this->say('Available tests in the system:');
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					'tests/' . $suite,
					RecursiveDirectoryIterator::SKIP_DOTS
				),
				RecursiveIteratorIterator::SELF_FIRST
			);
			$tests = array();
			$iterator->rewind();
			$i = 1;

			while ($iterator->valid())
			{
				if (strripos($iterator->getSubPathName(), 'cept.php')
					|| strripos($iterator->getSubPathName(), 'cest.php'))
				{
					$this->say('[' . $i . '] ' . $iterator->getSubPathName());
					$tests[$i] = $iterator->getSubPathName();
					$i++;
				}

				$iterator->next();
			}

			$this->say('');
			$testNumber	= $this->ask('Type the number of the test  in the list that you want to run...');
			$test = $tests[$testNumber];
		}

		$pathToTestFile = 'tests/' . $suite . '/' . $test;
		$this->taskCodecept()
			->test($pathToTestFile)
			->arg('--steps')
			->arg('--debug')
			->run()
			->stopOnFail();
		// Kill selenium server
		// $this->_exec('curl http://localhost:4444/selenium-server/driver/?cmd=shutDownSeleniumServer');
	}

	/**
	 * Creates a testing Joomla site for running the tests (use it before run:test)
	 */

	public function createTestingSite()
	{
		// Get Joomla Clean Testing sites
		if (is_dir('/tests/www/joomla-cms3'))
		{
			$this->taskDeleteDir('/tests/www/joomla-cms3')->run();
		}

		$this->_exec('git' . $this->extension . ' clone -b staging --single-branch --depth 1 https://github.com/joomla/joomla-cms.git /tests/www/joomla-cms3');

		$this->say('Joomla CMS site created at tests/joomla-cms3');

		$this->_exec('chown -R joomla:joomla /tests');
	}

	/**
	 * Runs Selenium Standalone Server.
	 *
	 * @return void
	 */
	public function runSelenium()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
		{
			$this->_exec("vendor/bin/selenium-server-standalone >> selenium.log 2>&1 &");
		}
		else
		{
			$this->_exec("START java.exe -jar .\\vendor\\joomla-projects\\selenium-server-standalone\\bin\\selenium-server-standalone.jar");
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
		{
			sleep(10);
		}
		else
		{
			$this->taskWaitForSeleniumStandaloneServer()
				->run()
				->stopOnFail();
		}
	}

	/**
	 * Downloads Composer
	 *
	 * @return void
	 */
	private function getComposer()
	{
		// Make sure we have Composer
		if (!file_exists('./composer.phar'))
		{
			$this->_exec('curl --retry 3 --retry-delay 5 -sS https://getcomposer.org/installer | php');
		}
	}
}
