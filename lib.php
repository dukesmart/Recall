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
	$email_address = filter_var(strtolower($email), FILTER_SANITIZE_EMAIL);
	
	$user_query = mysqli_query($mysql_connection, "SELECT privilege FROM users WHERE email='" . $email_address . "';");
	/* There are 0 results if there are no matching emails */
	if($user_query->num_rows == 0) {
		echo '<div class="alert alert-danger" role="alert">Error: Incorrect email address or password.</div>' . PHP_EOL;
		$admin = false;
	} else {
		$user = $user_query->fetch_assoc();
		if($user['privilege'] >= 2) {
			$admin = true;
		} else {
			$admin = false;
		}
	}
	
	return $admin;
}
?>