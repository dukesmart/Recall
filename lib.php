<?php
/**
 * This is a library of functions that are used accross several pages.
 * @package lib.php
 */
@include 'config.php';
@include 'template.php';

/**
 * Connect to the MySQL server
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
?>