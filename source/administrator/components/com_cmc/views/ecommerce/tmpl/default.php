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

$doc = JFactory::getDocument();

$doc->addStyleDeclaration('
	div.modal {background: none;}
	#shops-to-sync {padding: 15px}
');
?>

<div id="shops-to-sync" class="box-info full">
	<p class="intro">
		<?php echo JText::_('COM_CMC_INITIAL_SHOP_SYNC'); ?>
	</p>
	<div class="control-group">
		<div class="form-group">
			<label for="shop_name"><?php echo JText::_('COM_CMC_SHOP_NAME'); ?></label>

			<input type="text" name="shop_name" id="shop_name" class="form-control" />
		</div>

		<div class="form-group">
			<label for="shop"><?php echo JText::_('COM_CMC_SHOP_EMAIL'); ?></label>

			<input type="text" name="shop_email" id="shop_email" class="form-control" />
		</div>

		<div class="form-group">
			<label for="shop"><?php echo JText::_('COM_CMC_SHOP_CURRENCY_CODE'); ?></label>

			<input type="text" name="shop_currency" id="shop_currency" class="form-control" value="USD" />
		</div>

		<div class="form-group">
			<label for="shop"><?php echo JText::_('COM_CMC_SHOP_SOFTWARE'); ?></label>

			<select name="shop" id="shop" class="form-control">
				<option value="1">VirtueMart</option>
			</select>
		</div>

		<div class="form-group">
			<label for="list_id"><?php echo JText::_('COM_CMC_LIST'); ?></label>
			<?php
			$listSelect = CmcHelperBasic::getListSelect();

			if (empty($listSelect)):
				?>
				<h3><?php echo JText::_('COM_CMC_PLEASE_SYNCHRONIZE_A_LIST_FROM_MAIL_CHIMP_FIRST'); ?></h3>
			<?php else: ?>
				<?php echo $listSelect; ?>
			<?php endif; ?>
		</div>
	</div>

	<button id="btnAddShop" class="btn btn-primary" data-toggle="modal"
	        href="#modal_sync"><?php echo JText::_('COM_CMC_START_INITIAL_SYNC'); ?></button>

	<div id="modal_sync" class="modal fade" role="dialog" style="display: none">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo JText::_('COM_CMC_SHOP_SYNC_IN_PROGRESS'); ?></h4>
				</div>
				<div class="modal-body">
					<p>
						<?php echo JText::_('COM_CMC_DONT_CLOSE_THE_WINDOW'); ?>
					</p>

					<div id="sync-progress">
						<div class="sync-item">
							<label for="productProgress">Products (<span id="productTotal"></span>)</label>
							<div class="progress">
								<div id="productProgress" class="progress-bar" role="progressbar" aria-valuenow="0"
								     aria-valuemin="0" aria-valuemax="100" style="width: 0">
									0
								</div>
							</div>
						</div>
						<div class="sync-item">
							<label for="customerProgress">Customers (<span id="customerTotal"></span>)</label>
							<div class="progress">
								<div id="customerProgress" class="progress-bar" role="progressbar" aria-valuenow="0"
								     aria-valuemin="0" aria-valuemax="100" style="width: 0">
									0
								</div>
							</div>
						</div>
						<div class="sync-item">
							<label for="orderProgress">Orders (<span id="orderTotal"></span>)</label>
							<div class="progress">
								<div id="orderProgress" class="progress-bar" role="progressbar" aria-valuenow="0"
								     aria-valuemin="0" aria-valuemax="100" style="width: 0">
									0
								</div>
							</div>
						</div>
						<div class="sync-item">
							<label for="categoryProgress">Product Categories (<span id="categoryTotal"></span>)</label>
							<div class="progress">
								<div id="categoryProgress" class="progress-bar" role="progressbar" aria-valuenow="0"
								     aria-valuemin="0" aria-valuemax="100" style="width: 0">
									0
								</div>
							</div>
						</div>
						<div class="sync-item">
							<label for="checkoutProgress">Carts / Checkouts (<span id="checkoutTotal"></span>)</label>
							<div class="progress">
								<div id="checkoutProgress" class="progress-bar" role="progressbar" aria-valuenow="0"
								     aria-valuemin="0" aria-valuemax="100" style="width: 0">
									0
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"
					        disabled="disabled"><?php echo JText::_('COM_CMC_CLOSE'); ?></button>
				</div>
			</div>

		</div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			var juri = '<?php echo JUri::root(); ?>';
			var itemCount = null;
			var globalLimit = 5;
			var shopId = 'vm_8';

			var bars = {
				$productsProgress: $('#productProgress'),
				$customersProgress: $('#customerProgress'),
				$ordersProgress: $('#orderProgress'),
				$categoriesProgress: $('#categoryProgress'),
				$checkoutsProgress: $('#checkoutProgress')
			};

			function syncItems(type, list) {
				console.log('---Starting syncing---');
				console.log('Type: ' + type);
				console.log('List: ' + list);

				var actions = ['products', 'customers', 'orders', 'categories', 'checkouts'];

				for (var i = 0; i < actions.length; i++)	{
					syncItem(type, list, actions[i], 0, globalLimit);
				}
			}

			function syncItem(type, list, action, offset, limit) {
				var count = itemCount[action + 'Count'];

				// We are done
				if (count === 0 || offset > count) {
					bars['$' + action + 'Progress'].css({width: '100%'});
					bars['$' + action + 'Progress'].text(count);
					return true;
				}

				console.log('Syncing ' + count + ' ' + action + ' (offset ' + offset + ' , limit: ' + limit + ')');

				$.ajax(juri + 'administrator/index.php?option=com_cmc&task=ecommerce.sync', {
					method: 'POST',
					data: {shopId: shopId, action: action, type: type, list: list, offset: offset, limit: limit},
					dataType: 'json'
				}).done(function(json) {
					console.log('Sync for ' + action + ' done');
					console.log(json);

					// Continue syncing
					return syncItem(type, list, action, offset + limit, limit);
				}).fail(function(){
					console.log('Error syncing ' + action);
				});

				// Done with syncing task
				return true;
			}

			function createShop() {
				var type = $('#shop').val();
				var list = $('#list_id').val();
				var shopName = $('#shop_name').val();
				var shopCurrency = $('#shop_currency').val();
				var shopEmail = $('#shop_email').val();

				$.ajax(juri + 'administrator/index.php?option=com_cmc&task=ecommerce.createShop', {
					method: 'POST',
					data: {list: list, type: type, title: shopName, currency: shopCurrency, email: shopEmail},
					dataType: 'json'
				}).done(function(json) {
					console.log('Shop creation done ' + json.shopId);

					shopId = json.shopId;
					console.log(json);

					return syncItems(type, list);
				}).fail(function(){
					console.log('Error syncing shop');
				});
			}

			$('#btnAddShop').click(function (e) {
				e.preventDefault();

				var $productTotal = $('#productTotal');
				var $customerTotal = $('#customerTotal');
				var $orderTotal = $('#orderTotal');
				var $categoryTotal = $('#categoryTotal');
				var $checkoutTotal = $('#checkoutTotal');

				// Get the count
				$.ajax(juri + 'administrator/index.php?option=com_cmc&task=ecommerce.getsynctotalcount', {
					dataType: 'json'
				}).done(function (json) {
					itemCount = json;
					$productTotal.text(json.productsCount);
					$customerTotal.text(json.customersCount);
					$orderTotal.text(json.ordersCount);
					$categoryTotal.text(json.categoriesCount);
					$checkoutTotal.text(json.checkoutsCount);

					createShop();
				}).fail(function () {
					console.log('error');
				});
			})
		});
	</script>
</div>

<h3><?php echo JText::_('COM_CMC_SHOPS'); ?></h3>

<div class="table-responsive">
	<table class="table table-hover table-striped">
		<thead>
		<tr>
			<th></th>
			<th><?php echo JText::_('COM_CMC_SHOP_NAME'); ?></th>
			<th><?php echo JText::_('COM_CMC_SHOP_TYPE'); ?></th>
			<th><?php echo JText::_('COM_CMC_SHOP_ID'); ?></th>
		</tr>
		</thead>
	</table>
</div>
