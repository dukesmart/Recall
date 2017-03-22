<?php
@include 'config.php';
@include 'template.php';

/**
 * 
 */
function connect() {
	$connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$connection) {
		/* Couldn't connect */
		echo '<p class="error0">Error: Unable to connect to MySQL.</p>\n';
		echo $template_footer;
	}
	
	return $connection;
}

?>