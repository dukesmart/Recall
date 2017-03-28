<?php
/**
 * This page is the page in which an administrator may start a recall.
 * @package recall.php
 */

@include 'config.php';
@include 'template.php';

echo $template_header;
check_post();

/**
 *  Check POST variables to see if are contents to submit.
 */
function check_post() {
	// TODO Fix
	if(true) {
		display_unsubmitted_page_contents();
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
	
	display_submitted_page_contents();
	
	mysqli_close();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	echo '<p><a href="index.php">Return</a></p>';
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	echo '<form name="recall" action="" method="POST">
			<input type="submit" value="Start a Recall" />
			</form>';
	echo '<p><a href="index.php">Return</a></p>';
	echo $template_footer;
}
?>