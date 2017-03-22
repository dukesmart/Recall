<?php
@include 'config.php';
@include 'template.php';

if(!isset($_GET['id'])) {
	echo $template_header;
	echo <<<HERE
<p>You must be participating in a recall to submit a confirmation.</p>

HERE;
	echo $template_footer;
	exit();
}

$sanitized_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
/* Connect to the MySQL server */
$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
if (!$mysql_connection) {
	/* Couldn't connect */
	echo $template_header;
	echo '<p class="error0">Error: Unable to connect to MySQL.</p>\n';
	echo $template_footer;
	exit;
} else {
	/* Connected */
	/* TODO Get userid, recallid based on sanitized_id */
	//$query_result = mysqli_query($mysql_connection, "SELECT * FROM recipients WHERE recipientid")
	
	/* TODO Insert confirmation into database */
	//$query_result = mysqli_query($mysql_connection, "INSERT INTO confirmations");
}
?>