<?php
	define('PAGE_TITLE', 'Client');
	include_once('jcart/jcart.php');
	require('controllers/controller.php');

	if (!isset($_SESSION['username']) || $_SESSION['username'] == 'guest') {
		header("Location: signin.php?atype=danger&alert=" . urlencode("Please sign in to access this page."));
	}

	$customer = safe_query("SELECT * FROM users WHERE username = '{$_SESSION['username']}'");
	$customer = $customer[0];

	$orders = safe_query("SELECT * FROM orders WHERE customer_info_id = '{$customer['id']}'");
?>

<!DOCTYPE html>
<html>
	<?php echo rwp_head(PAGE_TITLE); ?>

	<body>
		<?php include_once("controllers/tracking.php") ?>
		<?php include("models/header.php"); ?>

		<div class="container">
			<!-- Modal -->
			<form method="post" action="client.php">
				<div class="modal fade" id="edit-profile" tabindex="-1" role="dialog" aria-labelledby="editProfile">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="myModalLabel">Edit Profile</h4>
							</div>
			
							<div class="modal-body">
								<div class="form-group">
									<label for="shipping_address">Shipping Address</label>
									<input type="text" class="form-control" name="shipping_address" />
								</div>
								
								<div class="form-group">
									<label for="shipping_country">Shipping Country</label>
									<select class="form-control" name="shipping_country" />
										<option value="United States">United States</option>
									</select>
								</div>
								
								<div class="form-group">
									<label for="shipping_state">Shipping State</label>
									<select class="form-control" name="shipping_state" />
										<option value="FL">Florida</option>
										<option value="CA">California</option>
									</select>
								</div>
			
								<div class="form-group">
									<label for="billing_address">Billing Address</label>
									<input type="text" class="form-control" name="billing_address" />
								</div>
								
								<div class="form-group">
									<label for="billing_country">Billing Country</label>
									<select class="form-control" name="billing_country" />
										<option value="United States">United States</option>
									</select>
								</div>
								
								<div class="form-group">
									<label for="billing_state">Billing State</label>
									<select class="form-control" name="billing_state" />
										<option value="FL">Florida</option>
										<option value="CA">California</option>
									</select>
								</div>
						
								<input type="hidden" name="edit-profile-form" />
							</div>
			
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success">Save</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			
			
			<h2>Welcome, <?php echo $customer['username']; ?>!</h2>
			
			<!-- Button trigger modal -->
			<button type="button" id="edit-profile-btn" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#edit-profile">
				<i class="fa fa-edit"></i> Edit Profile
			</button>
			
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-5">
							<h3>Shipping Information</h3>
							<br>
							<!-- Shipping Address -->
							<div class="ship_address">
								<h4>Shipping address: <?php echo $customer['shipping_address']; ?></h4>
								<h4>Country: United States</h4>
								<h4>State: Florida</h4>
							</div>
							<br>
							<h3>Billing Information</h3>
							<br>
							<!-- Billing Address -->
							<div class="bill_address">
								<h4>Billing address: <?php echo $customer['billing_address']; ?></h4>
								<h4>Country: United States</h4>
								<h4>State: Florida</h4>
							</div>
						</div>
						<div class="col-sm-7">
							<h3>Recent Orders</h3>
							<?php if (sizeof($orders) > 0) { ?>
							<table class="table">
								<thead>
									<tr>
										<td>Order Placed</td>
										<td>Total Cost</td>
										<td>Status</td>
									</tr>
								</thead>
								<tbody>
							<?php foreach ($orders as $order) { ?>
									<tr>
										<td><?php echo $order['order_placed']; ?></td>
										<td>$<?php echo money($order['total']); ?></td>
										<td>Placed</td>
									</tr>
							<?php } ?>
								</tbody>
							</table>
							<?php } else { ?>
								<h4>No recent orders.</h4>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div><!-- end .container -->
		<?php include("models/footer.php"); ?>
	</body>
</html>
