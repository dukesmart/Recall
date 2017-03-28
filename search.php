<?php
/*
 * Page used to execute and display search results.
 * @package search.php
 */
@include 'config.php';
@include 'template.php';
check_post();

/**
 *  Check POST variables to see if are contents to submit.
 */
function check_post() {
	if(!isset($_GET['searchquery'])) {
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
	check_vars();
	
	/* Connect to the MySQL server */
	$mysql_connection = connect();
	if(!$mysql_connection){
		exit();
	}
	
	/* Connected */
	/* TODO Search */
	
	display_submitted_page_contents();
	
	mysqli_close();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	$sanitized_searchquery = filter_var($_GET['searchquery'], FILTER_SANITIZE_STRING);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer;
	
	echo '<p><a href="index.php">Return</a></p>' . PHP_EOL;
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

?>
?>