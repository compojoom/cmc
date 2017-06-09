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

$isVmInstalled = JFile::exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
?>

<div id="shops-to-sync" class="box-info full">
	<div id="shop-edit">
		<?php if (!$isVmInstalled) : ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_CMC_THERE_IS_NO_SUPPORTED_ECOMMERCE_SOFTWARE_INSTALLED'); ?>
			</div>
		<?php endif; ?>

		<p class="intro">
			<?php echo JText::_('COM_CMC_INITIAL_SHOP_SYNC'); ?>
		</p>
		<div class="control-group">
			<div class="form-group">
				<label for="shop_name"><?php echo JText::_('COM_CMC_SHOP_NAME'); ?></label>

				<input type="text" name="shop_name" id="shop_name" class="form-control"/>
			</div>

			<div class="form-group">
				<label for="shop_email"><?php echo JText::_('COM_CMC_SHOP_EMAIL'); ?></label>

				<input type="text" name="shop_email" id="shop_email" class="form-control"/>
			</div>

			<div class="form-group">
				<label for="shop_currency"><?php echo JText::_('COM_CMC_SHOP_CURRENCY_CODE'); ?></label>

				<input type="text" name="shop_currency" id="shop_currency" class="form-control" value="USD"/>
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

				if (empty($listSelect)): ?>
					<h3><?php echo JText::_('COM_CMC_PLEASE_SYNCHRONIZE_A_LIST_FROM_MAIL_CHIMP_FIRST'); ?></h3>
				<?php else : ?>
					<?php echo $listSelect; ?>
				<?php endif; ?>
			</div>
		</div>

		<button id="btnAddShop" class="btn btn-primary" data-toggle="modal"
		        href="#modal_sync"><?php echo JText::_('COM_CMC_START_INITIAL_SYNC'); ?></button>
	</div>

	<div id="modal_sync" class="modal fade" role="dialog" style="display: none">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo JText::_('COM_CMC_SHOP_SYNC_IN_PROGRESS'); ?></h4>
				</div>
				<div class="modal-body">
					<p id="shop-sync-intro">
						<?php echo JText::_('COM_CMC_DONT_CLOSE_THE_WINDOW'); ?>
					</p>
					<div id="shop-sync-done" style="display: none">
						<div class="alert alert-info">
							<?php echo JText::_('COM_CMC_INITIAL_SHOP_SYNC_DONE'); ?>
						</div>
					</div>

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
					<button type="button" class="btn btn-default"
					        data-dismiss="modal"><?php echo JText::_('COM_CMC_CLOSE'); ?></button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			var juri = '<?php echo JUri::root(); ?>';
			var itemCount = null;
			var globalLimit = 10;
			var shopId = '';
			var errors = [];

			var bars = {
				$productsProgress: $('#productProgress'),
				$customersProgress: $('#customerProgress'),
				$ordersProgress: $('#orderProgress'),
				$categoriesProgress: $('#categoryProgress'),
				$checkoutsProgress: $('#checkoutProgress')
			};

			function syncItems(type, list) {
				console.log('---Starting syncing for shop: ' + shopId + '---');
				console.log('Type: ' + type);
				console.log('List: ' + list);

				var actions = ['products', 'customers', 'orders', 'categories', 'checkouts'];

				// Start 5 in parallel BAD IDEA
				syncItem(type, list, actions.shift(), actions, 0, globalLimit);
			}

			function syncItem(type, list, action, actions, offset, limit) {
				var count = itemCount[action + 'Count'];

				// We are done
				if (count === 0 || offset > count) {
					bars['$' + action + 'Progress'].css({width: '100%'});
					bars['$' + action + 'Progress'].text(count);

					if (actions.length > 0) {
						action = actions.shift();

						console.log('Switching action to ' + action);

						return syncItem(type, list, action, actions, 0, globalLimit);
					}

					// We are done syncing (set shop syncing to done)
					finalizeSync();

					return true;
				}

				console.log('Syncing ' + count + ' ' + action + ' (offset ' + offset + ' , limit: ' + limit + ')');

				$.ajax(juri + 'administrator/index.php?option=com_cmc&task=ecommerce.sync', {
					method: 'POST',
					data: {shopId: shopId, action: action, type: type, list: list, offset: offset, limit: limit},
					dataType: 'json'
				}).done(function (json) {
					console.log('Sync for ' + action + ' for ' + shopId + ' done');
					console.log(json);

					if (json.success === false) {
						var message = {
							'error': ['Error syncing ' + action]
						};

						Joomla.renderMessages(message);
					}

					// Continue syncing
					return syncItem(type, list, action, actions, offset + limit, limit);
				}).fail(function () {
					console.log('Error syncing ' + action);

					var message = {
						'error': ['Error syncing ' + action]
					};

					Joomla.renderMessages(message);
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

				if (shopName.length < 2 || shopEmail.length < 2 || shopCurrency.length < 2) {
					var message = {
						'error': ['Please fill out shop name, email address and currency']
					};

					Joomla.renderMessages(message);

					$('#shop-edit').show();
					$('#modal_sync').modal('hide');

					return false;
				}

				$.ajax(juri + 'administrator/index.php?option=com_cmc&task=ecommerce.createShop', {
					method: 'POST',
					data: {list: list, type: type, title: shopName, currency: shopCurrency, email: shopEmail},
					dataType: 'json'
				}).done(function (json) {
					console.log('Shop creation done ' + json.shopId);

					shopId = json.shopId;
					console.log(json);

					return syncItems(type, list);
				}).fail(function () {
					console.log('Error syncing shop');
				});
			}

			function finalizeSync() {
				$.ajax(juri + 'administrator/index.php?option=com_cmc&task=ecommerce.finalizeShop', {
					method: 'POST',
					data: {id: shopId},
					dataType: 'json'
				}).done(function (json) {
					console.log('Shop finalization done');
					$('#shop-sync-intro').hide();
					$('#shop-sync-done').show(200);
				}).fail(function () {
					console.log('Error finalizing shop');
				});
			}

			$('#btnAddShop').click(function (e) {
				e.preventDefault();

				$('#shop-edit').hide();

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

<div class="box-info full">

	<form name="adminForm" id="adminForm" method="post" action="<?php echo JRoute::_('index.php?option=com_cmc&view=ecommerce'); ?>">
		<div class="table-responsive">
			<table class="table table-hover table-striped">
				<thead>
				<tr>
					<th width="5">#</th>
					<th width="5"><input type="checkbox" name="checkall-toggle" value=""
					                     title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
					                     onclick="Joomla.checkAll(this)"/></th>
					<th><?php echo JText::_('COM_CMC_SHOP_NAME'); ?></th>
					<th><?php echo JText::_('COM_CMC_SHOP_TYPE'); ?></th>
					<th><?php echo JText::_('COM_CMC_SHOP_ID'); ?></th>
					<th><?php echo JText::_('COM_CMC_ID'); ?></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<tr>
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td><?php echo JHTML::_('grid.id', $i, $item->id); ?>    </td>
						<td><?php echo $item->name; ?></td>
						<td><?php echo $item->type == 1 ? 'Virtuemart' : 'Other'; ?></td>
						<td><?php echo $item->shop_id; ?></td>
						<td><?php echo $item->id; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<input type="hidden" name="task" id="task" value="" />
		<input type="hidden" name="boxchecked" value="0"/>

	</form>
</div>
