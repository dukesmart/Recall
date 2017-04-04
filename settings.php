<?php
/**
 * This page is the page in which an administrator may edit the administrative settings.
 * @package recall.php
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
	global $template_header;
	
	session_start();
	if(isset($_SESSION['email'])) {
		echo $template_header;
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
	global $template_footer;
	global $mysql_connection;
	check_vars();
	
	// TODO Display settings management
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
	global $template_footer, $nav_sidebar, $template_footer;
	
	echo $nav_sidebar;
	
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_footer, $nav_sidebar, $template_footer;
	
	echo $template_header;
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	echo '<h1>Settings</h1>' . PHP_EOL;
	echo '</main>';
	echo $template_footer;
}

mysqli_close($mysql_connection);
?>