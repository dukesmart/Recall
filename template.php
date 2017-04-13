<?php
/**
 * This file is used as a template and structure for designing other pages. Template: https://v4-alpha.getbootstrap.com/examples/dashboard/
 * @package template.php
 */

$template_header = 
'<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<meta name="description" content="" />
		<meta name="author" content="Dustin Steadman, Weylin MacCalla, Isabelle Larson, David Krumrey, David Coffield, Courtney Thurston" />
		<link rel="icon" href="../../favicon.ico" />
		
		<title>Recall</title>

		<!-- Bootstrap core CSS -->
		<link href="https://v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet" />

		<!-- Custom styles for Dashboard template -->
		<link href="https://v4-alpha.getbootstrap.com/examples/dashboard/dashboard.css" rel="stylesheet" />
	</head>
	<body>' . PHP_EOL;

$template_footer =
'	<!-- Bootstrap core JavaScript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
	<script>window.jQuery || document.write(\'<script src="../../assets/js/vendor/jquery.min.js"><\/script>\')</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="../../dist/js/bootstrap.min.js"></script>
	<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>';

/**
 * This function is used to return the proper navigation panes and sidebar. The highlighted option varies based on which page is open. 
 * @param String $page Describes which page is currently selected.
 * @param boolean $isadmin Describes if a user is an admin or not.
 */
function get_nav_sidebar($page, $isadmin) {
	$navbar_item_before = '					<li class="nav-item">
						<a class="nav-link';
	$navbar_item_after = '</a>
					</li>' . PHP_EOL;
	
	$nav_sidebar =
	'	<nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
		<button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="#">Recall</a>
			
		<div class="collapse navbar-collapse" id="navbarsExampleDefault">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="settings.php">Settings</a>
				</li>
			</ul>
			<form class="form-inline mt-2 mt-md-0" action="search.php" method="POST">
				<input class="form-control mr-sm-2" type="text" placeholder="Search">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
			</form>
		</div>
	</nav>
			
	<div class="container-fluid">
		<div class="row">
			<nav class="col-sm-3 col-md-2 hidden-xs-down bg-faded sidebar">
				<ul class="nav nav-pills flex-column">' . PHP_EOL;
	
	$nav_sidebar = $nav_sidebar . $navbar_item_after;
	if($page == 'index') {
		$nav_sidebar = $nav_sidebar . ' active" href="' . $page . '.php">' . ucfirst($page) . '<span class="sr-only">(current)</span>';
	} else {
		$nav_sidebar = $nav_sidebar . '" href="' . $page . '.php">' . ucfirst($page);
	}
	$nav_sidebar = $nav_sidebar . $navbar_item_after;
	
	if($isadmin) {
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		if($page == 'recall') {
			$nav_sidebar = $nav_sidebar . ' active" href="' . $page . '.php">' . ucfirst($page) . '<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="' . $page . '.php">' . ucfirst($page);
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		if($page == 'users') {
			$nav_sidebar = $nav_sidebar . ' active" href="' . $page . '.php">' . ucfirst($page) . '<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="' . $page . '.php">' . ucfirst($page);
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		if($page == 'billets') {
			$nav_sidebar = $nav_sidebar . ' active" href="' . $page . '.php">' . ucfirst($page) . '<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="' . $page . '.php">' . ucfirst($page);
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		if($page == 'departments') {
			$nav_sidebar = $nav_sidebar . ' active" href="' . $page . '.php">' . ucfirst($page) . '<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="' . $page . '.php">' . ucfirst($page);
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
	}
		
	$nav_sidebar = '				</ul>
			</nav>
 		
		</div>
	</div>' . PHP_EOL;
	
	return $nav_sidebar;
}

?>