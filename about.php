<?php
	define('PAGE_TITLE', 'Client');
	require('controllers/controller.php');
?>

<!DOCTYPE html>
<html>
	<?php echo rwp_head(PAGE_TITLE); ?>

	<body>
		<?php include_once("controllers/tracking.php") ?>
		<?php include("models/header.php"); ?>

		
		<div class="container">
			<h1>About Us</h1>
			<br>
			<div class="row home-row">
					<img class="col-sm-6 col-lg-4 gallery_img" src="img/skiing-1650760_640.jpg" alt="about_gallery1" width="300" height="213"/>
					<img class="col-sm-6 col-lg-4 gallery_img" src="img/fall-1432252_640.jpg" alt="about_gallery2" width="300" height="213"/>
					<img class="col-sm-6 col-lg-4 gallery_img" src="img/gibraltar-1351696_640.jpg" alt="about_gallery3" width="300" height="213"/>		
			</div>
			<h2>Who We Are</h2>
				<p id="intro_paragraph">
					Rosewind Paths is a source for people to discover places they have never yet encountered. Beaches and amusement parks are fun, but why not do something that is a little out of the ordinary?  We are here to help you find exactly just that and show that an adventure can be found in the least expected of places. This will be done with a map and compass to help guide you through your exploration that will lead you into peace, calm, and serenity. For those who want to take a break away from the stresses of the world, Rosewind Paths is the best route to relieve that tension. Our application is also beneficial for families that want to spend a weekend together and bond on a deeper level.
				</p>
			<hr/>
			<h2> Meet the Team </h2>
			<div class="row team">
				<div class="col-sm-6">
					<ul class="profiles">
						<li><img class="profile_img" src="http://www.wowescape.com/images/games/live-escape/tangle-forest-escape/tangle-forest-escape.jpg" alt="member" width="200" height="200"></li>
						<li>
							<h3>Kara Gwin</h3>
							<p class="profile_text">
								Kara is a student at UCF and one of the back-end developers.
							</p>
						</li>
					</ul>
					<ul class="profiles2">
						<li><img class="profile_img" src="https://frontierscientists.com/wp-content/uploads/2016/04/ForestBoreal_ProjectSquareFogTreetops-200x200.png" alt="member" width="200" height="200"></li>
						<li>
							<h3>Tiffany Brown</h3>
							<p class="profile_text">
								Tiffany is a student at UCF and the content developer.
							</p>
						</li>
					</ul>
				</div>
				<div class="col-sm-6">
					<ul class="profiles3">
						<li><img class="profile_img" src="http://rs337.pbsrc.com/albums/n390/CBCUDDLES/Forest.jpg~c200" alt="member" width="200" height="200"></li>
						<li>
							<h3>Nani Jones</h3>
							<p class="profile_text">
								Nani is a student at UCF and one of the front-end developers.
							</p>
						</li>
					</ul>
					<ul class="profiles4">
						<li><img class="profile_img" src="http://rs249.pbsrc.com/albums/gg203/jahdapac/forest.jpg~c200" alt="member" width="200" height="200"></li>
						<li>
							<h3>Sean McMenamin</h3>
							<p class="profile_text">
								Sean is a student at UCF and one of the front-end developers.
							</p>
						</li>
					</ul>
				</div>
				</div>
			<div class="row team">
				<div class="col-sm-12">
					<ul class="profiles5">
						<li><img class="profile_img" src="https://i0.wp.com/bossfight.co/wp-content/uploads/2016/10/boss-fight-free-high-quality-stock-images-photos-photography-trees-forest.jpg?fit=1200%2C675&ssl=1&resize=200%2C200" alt="member" width="200" height="200"></li>
						<li>
							<h3>Alexander Crocker</h3>
							<p class="profile_text">
								Alex is a student at UCF and one of the back-end developers.
							</p>
						</li>
					</ul>
				</div>
			</div>
			<br/>
			<div id="contact">
				<hr/>
				<h2>Contact Us</h2>
				<form method="get" action="about.php">
					<div class="form-group">	
						<label for="Name">First name:</label>
						<input class="form-control" type="text" name="first" id="first" value="<?php if(isset($first)) echo $first; ?>">
					</div>
					
					<div class="form-group">
						<label for="City">Last name:</label>
						<input class="form-control" type="text" name="last" id="last" value="<?php if(isset($last)) echo $last; ?>">
					</div>
		
					<div class="form-group">
						<label for="Email">Email:</label>
						<input class="form-control" type="text" name="email" id="email" value="<?php if(isset($email)) echo $email; ?>">
					</div>
					
					<div class="form-group">
						<label for="Message">Questions:</label><br>
						<textarea class="form-control" name="qcc" rows="10" id="qcc" value="<?php if(isset($qcc)) echo $qcc; ?>"></textarea>
					</div>
	
					<input type="submit" name="submit" value="Submit" class="btn btn-success">
				</form>
			</div>
		</div><!-- end .container -->
		<?php include("models/footer.php"); ?>
	</body>
</html>
