<?php
	define('PAGE_TITLE', 'Cart');
	include_once('jcart/jcart.php');
	require('controllers/controller.php');
?>

<!DOCTYPE html>
<html>
	<?php echo rwp_head(PAGE_TITLE); ?>

	<body>
		<?php include_once("controllers/tracking.php") ?>
		<?php include("models/header.php"); ?>

		<div class="container">
			<div id="jcart"><?php $jcart->display_cart('all');?></div>
		</div>

		<?php include("models/footer.php"); ?>
	</body>
</html>
