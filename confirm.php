<?php
/**
 * This is the page in which a user confirms his or her status.
 * @package confirm.php
 */
@include 'template.php';
@include 'lib.php';

check_post();

/**
 *  Check POST variables to see if are contents to submit.
 */
function check_post() {
	if(!isset($_GET['id'])) {
		display_unsubmitted_page_contents();
		exit();
	} else {
		submit();
	}
}

/**
 * Sets variables, connects to database, submits query to database.
 */
function submit() {
	global $mysql_error_connect, $template_footer;
	global $mysql_connection;
	check_vars();
	
	/* TODO Get userid, recallid based on sanitized_id */
	//$query_result = mysqli_query($mysql_connection, "SELECT * FROM recipients WHERE recipientid")

	/* TODO Insert confirmation into database */
	//$query_result = mysqli_query($mysql_connection, "INSERT INTO confirmations");
	display_submitted_page_contents();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	$sanitized_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer;
	
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	echo $template_header;
	echo '<p>You must be participating in a recall to submit a confirmation.</p>';
	echo $template_footer;
}

mysqli_close($mysql_connection);
?>