<?php
/**
 * @package    CMC
 * @author     Compojoom <contact-us@compojoom.com>
 * @date       2016-04-15
 *
 * @copyright  Copyright (C) 2008 - 2016 compojoom.com - Daniel Dimitrov, Yves Hoppe. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

// TODO move to autoloader
require_once  JPATH_ADMINISTRATOR . "/components/com_cmc/libraries/drewm/mailchimp-api/MailChimp.php";
require_once  JPATH_ADMINISTRATOR . "/components/com_cmc/libraries/drewm/mailchimp-api/Batch.php";

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

	public $verify_ssl = false;

	/**
	 * The constructor
	 *
	 * @param   string  $key     - the mailchimp api key
	 */
	public function __construct($key = '')
	{
		if (!$key)
		{
			$key = JComponentHelper::getParams('com_cmc')->get('api_key', '');
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
	 * @param   int     $offset  The offset where to begin with
	 * @param   int     $limit   The limit
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

		$members = $this->get('/lists/' . $listid . '/members', $args);
		$members = $members['members'];

		// Add email address to merge vars
		for ($i = 0; $i < count($members); $i++)
		{
			$members[$i]['merge_fields'] = array_merge(array("EMAIL" => $members[$i]['email_address']), $members[$i]['merge_fields']);

			// Always get interests
			if (!isset($members[$i]['interests']))
			{
				$members[$i]['interests'] = array();
			}
		}

		return $members;
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

		// We need to merge the email field here
		$email = array(
			'merge_id' => 1,
			'tag' => 'EMAIL',
			'name' => JText::_('COM_CMC_EMAIL'),
			'type' => 'text',
			'required' => true,
			'public' => true,
			'display_order' => 1,
			'list_id' => $listid
		);

		$fields = $this->get('/lists/' . $listid . '/merge-fields', $params);

		$fields = array_merge(array($email), $fields['merge_fields']);

		return $fields;
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

	/**
	 * Get field details (options etc.)
	 *
	 * @param   string  $listId   The list id
	 * @param   string  $fieldId  The field id
	 *
	 * @return  array|null
	 */
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
	 * 
	 * @return  array
	 */
	public function ecommOrderAdd($order)
	{
		return $this->post('/ecommerce/stores/' . $order['store_id'] . '/orders', $order);
	}

	/**
	 * Unimplemented in v3 API!
	 *
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
	 * Is the user subscribed
	 *
	 * @param   string  $listId  The list id
	 * @param   string  $email   The mail address
	 *
	 * @return  bool true if yes
	 *
	 * @since   3.0.0
	 */
	public function isSubscribed($listId, $email)
	{
		$subscriber_hash = $this->subscriberHash($email);

		$result = $this->get('/lists/' . $listId . "/members/" . $subscriber_hash);

		// Not existing member
		if ($result['status'] == 404)
		{
			return false;
		}

		return $result['status'] == 'subscribed' ? true : false;
	}

	/**
	 * Subscribe / Update some one to the list
	 *
	 * @param   string  $listId          The list id
	 * @param   string  $email_address   The email
	 * @param   array   $merge_vars      Merge vars
	 * @param   array   $interests       Merge vars
	 *
	 * @return array|false
	 */
	public function listSubscribe($listId,
	                              $email_address,
	                              $merge_vars = null,
	                              $interests = null,
	                              $email_type = 'html',
	                              $double_optin = true,
	                              $update_existing = false,
	                              $replace_interests = true,
	                              $send_welcome = false)
	{
		$subscriber_hash = $this->subscriberHash($email_address);
		$status = 'subscribed';

		if ($double_optin)
		{
			$status = 'pending';
		}

		// We need to unset that
		if (isset($merge_vars['GROUPINGS']))
		{
			unset($merge_vars['GROUPINGS']);
		}

		$args = array(
			'email_address' => $email_address,
			'status'        => $status,
			'merge_fields'  => $merge_vars,
			'interests'     => count($interests) ? $interests : new stdClass,
			'email_type'    => $email_type
		);

		if ($update_existing && $this->isSubscribed($listId, $email_address))
		{
			// Update user ..
			return $this->listUpdateSubscribe(
				$listId,
				$email_address,
				$merge_vars,
				$interests,
				$email_type
			);
		}

		$result = $this->post("/lists/" . $listId . "/members", $args);

		return $result;
	}

	/**
	 * Update to subscribtion
	 *
	 * @param   string  $listId        The list id
	 * @param   string  $email_address The email
	 * @param   array   $merge_vars    Merge vars
	 * @param   array   $interests     Merge vars
	 * @param   string  $email_type    HTML or Text?
	 * @param   string  $status        subscribed or pending??
	 *
	 * @return array|false
	 */
	public function listUpdateSubscribe($listId,
										$email_address,
										$merge_vars = null,
										$interests = null,
										$email_type = 'html',
										$status = 'subscribed'
										)
	{
		$subscriber_hash = $this->subscriberHash($email_address);

		$args = array(
			'email_address' => $email_address,
			'status' => $status,
			'merge_fields'  => $merge_vars,
			'interests'     => $interests,
			'email_type'    => $email_type
		);

		// The updated object
		$result = $this->put('/lists/' . $listId . "/members/" . $subscriber_hash, $args);

		return $result;
	}

	/**
	 * Send Ecommerce request
	 *
	 * @param       $mcId
	 * @param       $storeId
	 * @param       $orderId
	 * @param       $currencycode
	 * @param       $total
	 * @param       $tax
	 * @param array $lines
	 * @param array $customer
	 *
	 * @return array|false
	 *
	 * @since version
	 */
	public function addEcomOrder(
		$mcId,
		$storeId,
		$orderId,
		$currencycode,
		$total,
		$tax,
		$lines = array(),
		$customer = array())
	{
		$args = array(
			'id' => $orderId,
			'customer' => $customer,
			'campaign_id' => $mcId,
			'checkout_url' => '',
			'currency_code' => $currencycode,
			'order_total' => $total,
			'tax_total' => $tax,
			'lines' => $lines
		);

		$result = $this->post('/ecommerce/stores/' . $storeId . "/orders", $args);

		return $result;
	}
}
