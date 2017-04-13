<?php
/**
 * This is a library of functions that are used accross several pages.
 * @package lib.php
 */
@include 'config.php';
@include 'template.php';

/**
 * Connect to the MySQL server.
 */
function mysql_setup() {
	global $mysql_error_connect, $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database;
	
	$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$mysql_connection) {
		/* Couldn't connect */
		echo $template_header;
		echo '<div class="alert alert-danger" role="alert">Error: Unable to connect to MySQL.</div>' . PHP_EOL;
		echo $template_footer;
		return false;
	}
	else {
		return true;
	}
}

/**
 * Determines if a user is an admin or not.
 * @param User_Email $email The email address of the user being checked.
 */
function isadmin($email) {
	global $mysql_connection;
	$filtered_email = filter_var(strtolower($email), FILTER_SANITIZE_EMAIL);
	
	$query = mysqli_query($mysql_connection, "SELECT privilege FROM users WHERE email='" . $filtered_email. "';");
	/* There are 0 results if there are no matching emails */
	if($query->num_rows == 0) {
		echo '<div class="alert alert-danger" role="alert">Error: No matching email.</div>' . PHP_EOL;
		$admin = false;
	} else {
		$user = $query->fetch_assoc();
		if($user['privilege'] >= 2) {
			$admin = true;
		} else {
			$admin = false;
		}
	}
	
	return $admin;
}

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
	
	$nav_sidebar = $nav_sidebar . $navbar_item_before;
	if($page == 'index') {
		$nav_sidebar = $nav_sidebar . ' active" href="index.php">Overview<span class="sr-only">(current)</span>';
	} else {
		$nav_sidebar = $nav_sidebar . '" href="index.php">Overview';
	}
	$nav_sidebar = $nav_sidebar . $navbar_item_after;
	
	if($isadmin) {
		$nav_sidebar = $nav_sidebar . $navbar_item_before;
		if($page == 'recall') {
			$nav_sidebar = $nav_sidebar . ' active" href="recall.php">Start a Recall<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="recall.php">Start a Recall';
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		
		$nav_sidebar = $nav_sidebar . $navbar_item_before;
		if($page == 'users') {
			$nav_sidebar = $nav_sidebar . ' active" href="users.php">Users<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="users.php">Users';
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		
		$nav_sidebar = $nav_sidebar . $navbar_item_before;
		if($page == 'billets') {
			$nav_sidebar = $nav_sidebar . ' active" href="billets.php">Billets<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="billets.php">Billets';
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
		
		$nav_sidebar = $nav_sidebar . $navbar_item_before;
		if($page == 'departments') {
			$nav_sidebar = $nav_sidebar . ' active" href="departments.php">Departments<span class="sr-only">(current)</span>';
		} else {
			$nav_sidebar = $nav_sidebar . '" href="departments.php">Departments';
		}
		$nav_sidebar = $nav_sidebar . $navbar_item_after;
	}
	
	$nav_sidebar = $nav_sidebar . '				</ul>
			</nav>
			
		</div>
	</div>' . PHP_EOL;
	
	return $nav_sidebar;
}

?>
