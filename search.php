<?php
/**
 * Page used to execute and display search results.
 * @package search.php
 */
@include 'template.php';
@include 'lib.php';

if(mysql_setup()) {
	session_start();
	check_session();
} else {
	exit();
}

/**
 * Check to see if a user has previously logged in.
 */
function check_session() {
	global $_SESSION;
	
	if(isset($_SESSION['email'])) {
		check_post();
	} else {
		header("location:index.php");
	}
}

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
	global $mysql_error_connect, $template_footer;
	global $mysql_connection;
	check_vars();
	
	// TODO Search
	display_submitted_page_contents();
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
	global $template_header, $template_footer;
	
	echo $template_header;
	
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_header, $template_footer;
	
	echo $template_header;
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	echo $template_footer;
}

mysqli_close($mysql_connection);
?>