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
$mysql_connection = connect();
if(!$mysql_connection){
	exit();
}

/* Connected */
/* TODO Get userid, recallid based on sanitized_id */
//$query_result = mysqli_query($mysql_connection, "SELECT * FROM recipients WHERE recipientid")

/* TODO Insert confirmation into database */
//$query_result = mysqli_query($mysql_connection, "INSERT INTO confirmations");

?>