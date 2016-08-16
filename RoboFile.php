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
	use \Joomla\Jorobo\Tasks\loadTasks;
	use \joomla_projects\robo\loadTasks;

	/**
	 * File extension for executables
	 *
	 * @var    string
	 */
	private $executableExtension = '';

	/**
	 * Local configuration parameters
	 *
	 * @var    array
	 */
	private $configuration = array();

	/**
	 * Path to the local CMS root
	 *
	 * @var    string
	 */
	private $cmsPath = '';

	/**
	 * Initialize Robo
	 */
	public function __construct()
	{
		$this->configuration       = $this->getConfiguration();
		$this->cmsPath             = $this->getCmsPath();
		$this->executableExtension = $this->getExecutableExtension();

		// Set default timezone (so no warnings are generated if it is not set)
		date_default_timezone_set('UTC');
	}

	/**
	 * Get the executable extension according to Operating System
	 *
	 * @return  void
	 */
	private function getExecutableExtension()
	{
		if ($this->isWindows())
		{
			return '.exe';
		}
		return '';
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
	 * Build the joomla extension package
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
	 * @param   string  $user          Optional user to run the tests as
	 * @param   string  $seleniumPath  Optional path to selenium-standalone-server-x.jar
	 * @param   string  $suite         Optional, the name of the tests suite
	 *
	 * @return mixed
	 */
	public function runTests($user = 'www-data', $seleniumPath = null, $suite = 'acceptance')
	{
		if (!file_exists(JPATH_BASE . "/dist/current"))
		{
			$this->build(array('dev' => true));
		}

		$this->setExecExtension();

		$this->createTestingSite($user);
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
	 *
	 * @param   bool  $use_htaccess  (1/0) Rename and enable embedded Joomla .htaccess file
	 */
	public function createTestingSite($use_htaccess = false)
	{
		if (!empty($this->configuration->skipClone))
		{
			$this->say('Reusing Joomla CMS site already present at ' . $this->cmsPath);

			return;
		}

		// Caching cloned installations locally
		if (!is_dir('tests/cache') || (time() - filemtime('tests/cache') > 60 * 60 * 24))
		{
			if (file_exists('tests/cache'))
			{
				$this->taskDeleteDir('tests/cache')->run();
			}

			$this->_exec($this->buildGitCloneCommand());
		}

		// Get Joomla Clean Testing sites
		if (is_dir($this->cmsPath))
		{
			try
			{
				$this->taskDeleteDir($this->cmsPath)->run();
			}
			catch (Exception $e)
			{
				// Sorry, we tried :(
				$this->say('Sorry, you will have to delete ' . $this->cmsPath . ' manually. ');

				exit(1);
			}
		}

		$this->_copyDir('tests/cache', $this->cmsPath);

		// Copy current package
		if (!file_exists('dist/current'))
		{
			$this->build(true);
		}

		// Optionally change owner to fix permissions issues
		if (!empty($this->configuration->localUser) && !$this->isWindows())
		{
			$this->say('Changing owner of local cms directory to ' . $this->configuration->localUser);
			$this->_exec('chown -R ' . $this->configuration->localUser . ' ' . $this->cmsPath);
		}

		$this->say('Joomla CMS site created at ' . $this->cmsPath);
	}

	/**
	 * Get (optional) configuration from an external file
	 *
	 * @return \stdClass|null
	 */
	public function getConfiguration()
	{
		$configurationFile = __DIR__ . '/RoboFile.ini';

		if (!file_exists($configurationFile))
		{
			$this->say("No local configuration file");

			return null;
		}

		$configuration = parse_ini_file($configurationFile);

		if ($configuration === false)
		{
			$this->say('Local configuration file is empty or wrong (check is it in correct .ini format');

			return null;
		}

		return json_decode(json_encode($configuration));
	}

	/**
	 * Build correct git clone command according to local configuration and OS
	 *
	 * @return string
	 */
	private function buildGitCloneCommand()
	{
		$branch = empty($this->configuration->branch) ? 'staging' : $this->configuration->branch;
		return "git" . $this->executableExtension . " clone -b $branch --single-branch --depth 1 https://github.com/joomla/joomla-cms.git tests/cache";
	}
	/**
	 * Check if local OS is Windows
	 *
	 * @return bool
	 */
	private function isWindows()
	{
		return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
	}

	/**
	 * Get the correct CMS root path
	 *
	 * @return string
	 */
	private function getCmsPath()
	{
		if (empty($this->configuration->cmsPath))
		{
			return 'tests/joomla-cms3';
		}

		if (!file_exists(dirname($this->configuration->cmsPath)))
		{
			$this->say("Cms path written in local configuration does not exists or is not readable");

				return 'tests/joomla-cms3';
		}

		return $this->configuration->cmsPath;
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
			$insecure = $this->isWindows() ? ' --insecure' : '';
			$this->_exec('curl ' . $insecure . ' --retry 3 --retry-delay 5 -sS https://getcomposer.org/installer | php');
		}
	}
	/**
	 * Kills the selenium server running
	 *
	 * @param   string  $host  Web host of the remote server.
	 * @param   string  $port  Server port.
	 */
	public function killSelenium($host = 'localhost', $port = '4444')
	{
		$this->say('Trying to kill the selenium server.');
		$this->_exec("curl http://$host:$port/selenium-server/driver/?cmd=shutDownSeleniumServer");
	}

	/**
	 * Update copyright headers for this project. (Set the text up in the jorobo.ini)
	 *
	 * @return  void
	 */
	public function headers()
	{
		(new \Joomla\Jorobo\Tasks\CopyrightHeader())->run();
	}
}
