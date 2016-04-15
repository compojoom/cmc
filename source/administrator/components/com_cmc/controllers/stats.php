<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Protect from unauthorized access
defined('_JEXEC') or die;

/**
 * The updates provisioning Controller
 *
 * @since  4.0
 */
class CmcControllerStats extends CompojoomControllerStats
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 */
	public function getModel($name = 'Stats', $prefix = 'CmcModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}
