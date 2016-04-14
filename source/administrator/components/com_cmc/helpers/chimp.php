<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       28.08.13
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class cmcHelperChimp
 *
 * This class will work as a small abstraction over the MCAPI class.
 * I got too tired of typing the $key all the time :)
 *
 * @since  1.0
 */
class CmcHelperChimp extends \DrewM\MailChimp\MailChimp
{
	/**
	 * The MailChimp API Key
	 *
	 * @var    null|string
	 */
	public $api_key = null;

	/**
	 * The constructor
	 *
	 * @param   string  $key     - the mailchimp api key
	 * @param   string  $secure  - use secure connection
	 */
	public function __construct($key = '', $secure = '')
	{
		if (!$key)
		{
			$key = JComponentHelper::getParams('com_cmc')->get('api_key', '');
		}

		if (!$secure)
		{
			$config = JFactory::getConfig();
			$secure = $config->get('force_ssl', 0);
		}

		$this->api_key = $key;

		parent::__construct($key);
	}

	/**
	 * Get the account details (/) of the mailchimp account
	 *
	 * @return  array|false
	 */
	public function getAccountDetails()
	{
		return $this->get("/");
	}

	/**
	 * Get the lists information
	 *
	 * @param   string|null  $ids  The listid or null for all
	 *
	 * @return  array|false  The list information
	 */
	public function lists($ids = null)
	{
		if (!$ids)
		{
			$lists = $this->get('/lists');
		}
		else
		{
			$lists = array();

			if (is_array($ids))
			{
				foreach ($ids as $id)
				{
					$lists[] = $this->get('/lists/' . $id);
				}
			}
			else
			{
				$lists[] = $this->get('/lists/' . $ids);
			}
		}

		return $lists;
	}

	/**
	 * Get member details
	 *
	 * @param   string  $listid  The list  id
	 * @param   string  $status  The subscription status
	 * @param   int     $offset
	 * @param   int     $limit
	 *
	 * @return  array|false  The member details
	 */
	public function listMembers($listid, $status, $offset = 0, $limit = 50)
	{
		$args = array();

		if ($status)
		{
			$args["status"] = $status;
		}

		$args["offset"] = $offset;
		$args["count"] = $limit;

		return $this->get('/lists/' . $listid . '/members', $args);
	}

	/**
	 * Get the member info for the given email address and list
	 *
	 * @param   string  $listid  The list id
	 * @param   string  $email   The email
	 *
	 * @return  array|false
	 */
	public function listMemberInfo($listid, $email)
	{
		$subscriber_hash = $this->subscriberHash($email);

		return $this->get('/lists/' . $listid . '/members/' . $subscriber_hash);
	}

	/**
	 * Get the merge vars for the listid
	 *
	 * @param   string  $listid  The list id
	 *
	 * @return  mixed
	 */
	public function listMergeVars($listid)
	{
		// Normally this should be enough..
		$params = array('count' => 1000);

		$fields = $this->get('/lists/' . $listid . '/merge-fields', $params);

		return $fields['merge_fields'];
	}

	/**
	 * Get the interest groups
	 *
	 * @param   string  $listid  The list id
	 *
	 * @return  array|false
	 */
	public function listInterestGroupings($listid)
	{
		$params = array('count' => 1000);

		$interests = $this->get('/lists/' . $listid . '/interest-categories', $params);

		return isset($interests['categories']) ? $interests['categories'] : null;
	}

	public function listIntegerestGroupingsField($listId, $fieldId)
	{
		$params = array('count' => 1000);

		$fields = $this->get('/lists/' . $listId . '/interest-categories/' . $fieldId . "/interests", $params);

		return isset($fields['interests']) ? $fields['interests'] : null;
	}

	/**
	 * Unsubscribe from list
	 *
	 * @param   string  $listid  The list id
	 * @param   string  $email   The email
	 *
	 * @return  array|false
	 */
	public function listUnsubscribe($listid, $email)
	{
		$subscriber_hash = $this->subscriberHash($email);

		return $this->delete('/lists/' . $listid . '/members/' . $subscriber_hash);
	}

	/**
	 * Add ecommerce order
	 *
	 * @param  object  $order  The order details
	 *
	 * @throws  Exception

	 */
	public function ecommOrderAdd($order)
	{
		throw new Exception("Unimplemented", 500);
	}

	/**
	 * Unimplemented in v3 API!
	 * @param   string  $email   The email
	 *
	 * @deprecated  Not implemented in v3
	 *
	 * @throws Exception
	 */
	public function listsForEmail($email)
	{
		throw new Exception("Unimplemented not available in v3", 500);
	}

	/**
	 * @param   string  $id              The list id
	 * @param   string  $email_address   The email
	 * @param   array   $merge_vars      Merge vars
	 * @param   array   $interests       Merge vars
	 * @param string $email_type
	 * @param bool   $double_optin
	 * @param bool   $update_existing
	 * @param bool   $replace_interests
	 * @param bool   $send_welcome
	 *
	 * @return array|false
	 */
	public function listSubscribe($id, $email_address, $merge_vars = null, $interests = null, $email_type = 'html',
	                              $double_optin = true, $update_existing = false, $replace_interests = true,
	                              $send_welcome = false)
	{
		$result = $this->post("lists/$id/members", [
			'email_address' => $email_address,
			'status'        => 'subscribed',
			'merge_fields'  => $merge_vars,
			'interests'     => $interests,
			'email_type'    => $email_type
		]);

		return $result;
	}
}
