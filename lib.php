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
function connect() {
	global $mysql_host, $mysql_username, $mysql_password, $mysql_database;
	
	$connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$connection) {
		/* Couldn't connect */
		echo '<p class="error0">Error: Unable to connect to MySQL.</p>\n';
		echo $template_footer;
	}
	
	return $connection;
}

?>