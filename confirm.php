<?php
/**
 * This is the page in which a user confirms his or her status.
 * @package confirm.php
 */
@include 'template.php';
@include 'lib.php';

if(mysql_setup()) {
	check_post();
} else {
	exit();
}

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
	global $template_header, $template_footer;
	global $mysql_connection, $sanitized_id;
	check_vars();
	
	echo $template_header;
	$confirmation_datetime = date("Y-m-d H:i:s");

	$recipient_query = mysqli_query($mysql_connection, "SELECT * FROM recipients WHERE recipientid='" . $sanitized_id. "';");
	if(!$recipient_query) {
		// Query failed
		echo '<div class="alert alert-danger" role="alert">Error: Failed to decect recipient ' . $sanitized_id . '. </div></main>' . PHP_EOL;
		echo $template_footer;
		exit();
	} else {
		// Query success
		$recipient = $recipient_query->fetch_assoc();
		$status = '1';
		$verification_query = mysqli_query($mysql_connection, "SELECT * FROM confirmations WHERE userid='" . $recipient['userid'] . "' AND recallid='" . $recipient['recallid']. "';");
		if(!$verification_query) {
			// Query failed
			echo '<div class="alert alert-danger" role="alert">Error: Failed to check existing confirmations. </div></main>' . PHP_EOL;
			echo $template_footer;
			exit();
		} else {
			// Query success
			if($verification_query->num_rows != 0) {
				// There is already a confirmation
				echo '<div class="alert alert-danger" role="alert">Error: Already confirmed. </div></main>' . PHP_EOL;
				echo $template_footer;
				exit();
			} else {
				$confirmation_query = mysqli_query($mysql_connection, "INSERT INTO confirmations (userid, recallid, datetime, status) VALUES ('" . $recipient['userid'] . "', '" . $recipient['recallid']. "', '" . $confirmation_datetime . "', '" . $status . "');");
				if(!$confirmation_query) {
					// Query failed
					echo '<div class="alert alert-danger" role="alert">Error: Failed to submit confirmation. </div></main>' . PHP_EOL;
					echo $template_footer;
					exit();
				} else {
					// Query success
					display_submitted_page_contents();
				}
			}
		}
	}
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $sanitized_id;
	$sanitized_id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer, $template_footer;
	echo '<div class="alert alert-success" role="alert">Confirmation submitted.</div>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_header, $template_footer;
	echo $template_header;
	echo '<p>You must be participating in a recall to submit a confirmation.</p>';
	echo $template_footer;
}

mysqli_close($mysql_connection);
?>