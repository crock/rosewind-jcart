<?php
	define('PAGE_TITLE', 'Home');
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
			<?php if (isset($_GET['atype']) && isset($_GET['alert'])) { ?>
				<div class="alert <?php echo ($_GET['atype'] == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert"><?php echo urldecode($_GET['alert']); ?></div>
			<?php } ?>

			<div id="feat-slider" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				<ol class="carousel-indicators">
					<li data-target="#feat-slider" data-slide-to="0" class="active"></li>
					<?php for ($i = 1; $i < FEATURE_NUM; $i++) { ?>
					<li data-target="#feat-slider" data-slide-to="<?php echo FEATURE_NUM; ?>"></li>
					<?php } ?>
				</ol>

				<!-- Wrapper for slides -->
				<div class="carousel-inner" role="listbox">
				<?php foreach (get_products("WHERE status > '1' ORDER BY RAND() LIMIT " . FEATURE_NUM) as $product) { ?>
					<div class="item">
						<a href="product.php?product=<?php echo $product['product_id']; ?>">
							<img class="img-responsive" src="<?php echo $product['img']; ?>" alt="<?php echo $product['product_name']; ?>">
							<div class="carousel-caption">
								<h3><?php echo $product['product_name']; ?><span class="label label-success">$<?php echo $product['price']; ?></span></h3>
							</div>
						</a>
					</div>
				<?php } ?>
				</div>

				<!-- Controls -->
				<a class="left carousel-control" href="#feat-slider"  data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
				<span class="sr-only">Previous</span></a>
				<a class="right carousel-control" href="#feat-slider" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
				<span class="sr-only">Next</span></a>
			</div><!-- end #feat-slider -->

			<div class="row">
				<h1 class="col-md-9">Find your comfort zone in the outdoors</h1>
				<div class="col-xs-8 col-sm-6 col-md-3 form-group">
					<label for="sel1">Search by category:</label>
					<select class="form-control cat-select" id="sel1">
						<?php foreach ($all_categories as $category) { ?>
							<option value="<?php echo $category['category_slug']; ?>"><?php echo $category['category_name']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<br/>
			<div class="row home-row">
				<div class="col-sm-6 col-lg-4 card-block home-card">
					<div class="panel panel-default panel-home">
						<div class="panel-body">
							<!-- Placeholder for destinations -->
							<img class="gallery_img" src="img/gibraltar-1351696_640.jpg" alt="home_gallery1" width="300" height="220"/>
							<h4 class="card-title">Discover Affordable Destinations</h4>
							<p class="card-text">Would you believe that there might be an area around you that's perfect for outdoor travel and trekking that you might not know about? We'll be happy to let you know where they are and how to get there.</p>
							<hr/>
							<img class="profile-img" alt="profile one" src="http://lorempixel.com/100/100/people/"/>
							<p class="cart-text quote">"We were wondering just how we were going to afford to head on an adventure trip out of state, but Rosewind Paths was able to coordinate an affordable plan for us, as well as give us discounts on essentials we needed."<br> - Adrian Robinson</p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-4 card-block home-card">
					<div class="panel panel-default panel-home">
						<div class="panel-body">
							<!-- Placeholder for destinations -->
							<img class="gallery_img" src="img/fall-1432252_640.jpg" alt="home_gallery2" width="320" height="220"/>
							<h4 class="card-title">From Casual to Challenging</h4>
							<p class="card-text">We have different branches of excursions, from enjoying a stroll through a local park or backpacking through the mountains on a long excursion. You'll be able to see adventures of all levels near you, and choose what's best for your lifestyle.</p>
							<hr/>
							<img class="profile-img" alt="profile two" src="http://lorempixel.com/g/100/100/people/"/>
							<p class="cart-text quote">"I was concerned that my family and I weren't going to be able to make the trip because of how bad my back was. I wanted to still travel with them but quite a few travel plans included too many strenuous activites. Rosewind Paths was able to give us a better and more casual alternative at a lower price that's in the same state. I highly recommend this business."<br> - Jesse Matthews</p>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-4 card-block home-card">
					<div class="panel panel-default panel-home">
						<div class="panel-body">
							<!-- Placeholder for destinations -->
							<img class="gallery_img" src="img/new-zealand-1805932_640.jpg" alt="home_gallery2" width="320" height="220"/>
							<h4 class="card-title">Never Go Unprepared</h4>
							<p class="card-text">Want to travel to the ends of the earth but not sure just what to take? We'll make sure you get there with everything you need for the activities and adventure that you'll have on your travels. From food to full equipment, our catalog has an array of several products and provisions.</p>
							<hr/>
							<img class="profile-img" alt="profile three" src="http://lorempixel.com/100/100/sports/"/>
							<p class="cart-text quote">"Since this was going to be our first big travle trip outside of the state, we were worried about forgetting something important. Especially because we were planning to go rock climbing. Rosewind Paths was able to make sure that we had a list of all the essentials so we weren't caught without something we needed."<br> - Julian Smith</p>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-2">
				</div>
				<div class="col-md-10">
					<!-- <img class="img-anim-left img-full" src="" alt="img_left">-->
				</div>
			</div>
		</div><!-- end .container -->

		<?php include("models/footer.php"); ?>
	</body>
</html>
