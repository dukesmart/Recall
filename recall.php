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
	global $template_footer;
	// TODO Fix
	if(isset($_POST['users'])) {
		submit();
	} else {
		display_unsubmitted_page_contents();
	}
}

/**
 * Sets variables, connects to database, submits query to database.
 */
function submit() {
	global $mysql_error_connect, $template_footer;
	global $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database;
	global $send_email, $send_text;
	check_vars();
	
	/* Connect to the MySQL server */
	$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$mysql_connection) {
		/* Couldn't connect */
		echo $mysql_error_connect;
		echo $template_footer;
		exit;
	} else {
		/* Connected */
		display_submitted_page_contents();
		
		$recall_date = date("Y-m-d H:i:s");
		// TODO Add initiator ID
		/* Insert the recall */
		$recall_query = mysqli_query($mysql_connection, "INSERT INTO recalls (datetime, message) VALUES ('" . $recall_date . "', '" . $recall_message . "');");
		if(!$recall_query) {
			/* Query failed */
			echo '<p class="error1">Failed to initiate recall. <a href="recall.php">Retry</a></p>';
			echo $template_footer;
			exit();
		} else {
			/* Query to get the recall ID */
			$recall_result = mysqli_query($mysql_connection, "SELECT * FROM recalls WHERE datetime='" . $date . "';");
			if(!$recall_query) {
				/* Query failed */
				echo '<p class="error1">Failed to initiate recall. <a href="recall.php">Retry</a></p>';
				echo $template_footer;
				exit();
			} else {
				$row = $recall_result->fetch_assoc();
				$recall_id = $row['recallid'];
				foreach($recall_recipients as $recipient) {
					$recipient_query = mysqli_query($mysql_connection, "INSERT INTO recipients (userid, recallid) VALUES ('" . $recipient . "', '" . $recall_id . "');");
					if(!$recipient_query) {
						/* Query failed */
						echo '<p class="error1">Failed to add recipient. <a href="recall.php">Retry</a></p>';
						echo $template_footer;
						exit();
					} else {
						if($send_email) {
							//TODO Send email here
						}
						
						if($send_text) {
							//TODO Send text message here
						}
					}
				}
			}
		}
		
		mysqli_close();
	}
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $recall_message, $recall_recipients, $send_email, $send_text;
	$recall_message = filter_var( $_POST['message'], FILTER_SANITIZE_STRING);
	$recall_recipients = filter_var_array($_POST['users'], FILTER_SANITIZE_NUMBER_INT);
	if(isset($_POST['sendemail']) && (filter_var( $_POST['sendemail'], FILTER_SANITIZE_STRING) == 'true')) {
		$send_email = true;
	} else {
		$send_email = false;
	}
	if(isset($_POST['sendtext']) && (filter_var( $_POST['sendtext'], FILTER_SANITIZE_STRING) == 'true')) {
		$send_text = true;
	} else {
		$send_text = false;
	}
	
	if(($send_email == false) && ($send_text == false)) {
		echo '<p class="error1">At least one contact method must be selected (Email, text message). <a href="recall.php">Retry</a></p>';
		echo $template_footer;
		exit;
	}
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer, $nav_sidebar, $template_footer;
	echo '<p><a href="index.php">Return</a></p>';
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $mysql_error_connect;
	global $template_footer, $nav_sidebar, $template_footer;
	global $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database;
	
	echo $nav_sidebar;
	echo '<form name="recall" action="" method="POST">' . PHP_EOL;
	/* Connect to the MySQL server */
	$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$mysql_connection) {
		/* Couldn't connect */
		echo $mysql_error_connect;
		echo $template_footer;
		exit;
	} else {
		/* Connected */
		$userlist_query = mysqli_query($mysql_connection, "SELECT userid, firstname, lastname, billetid FROM users LIMIT 50;");
		if(!$userlist_query) {
			/* Query success */
			echo '<p class="error1">Failed to get user list from database.</p>' . PHP_EOL;
		} else {
			/* Query Failure */
			echo '<form name="recallusers" action="" method="POST">' . PHP_EOL;
			echo '<table class="center">' . PHP_EOL;
			echo '<tr><th colspan="4">Message:</th></tr>';
			echo '<tr><td colspan="4"><textarea name="message" form="recallusers"></textarea></td></tr>' . PHP_EOL;
			echo '<tr>' . PHP_EOL . '<th></th>' . PHP_EOL . '<th>Last Name</th>' . PHP_EOL . '<th>First Name</th>' . PHP_EOL . '<th>Billet</th>' . PHP_EOL . '</tr>' . PHP_EOL;
			/* Display each user as a checkable option in a table */
			while($row = $userlist_query->fetch_assoc()) {
				/* Query to get the billet name from the billet ID */
				$row_billet_query = mysqli_query($mysql_connection, "SELECT name FROM billets WHERE billetid='" . $row['billetid'] . "';");
				echo '<tr>' . PHP_EOL;
				echo '<td><input type="checkbox" name="users[]" value="' . $row['userid'] . '" /></td>' . PHP_EOL;
				echo '<td>' . $row['lastname'] . '</td>' . PHP_EOL;
				echo '<td>' . $row['firstname'] . '</td>' . PHP_EOL;
				// Get billet name from billetid
				if(!$row_billet_query) {
					/* Query Failure */
					echo '<td><p class="error1">Failed to get billet from database.</p></td>' . PHP_EOL;
				} else {
					/* Query success */
					$row_billet = $row_billet_query->fetch_assoc();
					echo '<td>' . $row_billet['name'] . '</td>' . PHP_EOL;
				}
				// TODO Get department name from departmentid
				//echo '<td>' . $row['departmentid'] . '</td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
			}
			echo '<tr><td colspan="4"></td></tr>' . PHP_EOL;
			echo '<tr>' . PHP_EOL;
			echo '<td><input type="checkbox" name="sendemail" value="true" checked /></td>' . PHP_EOL;
			echo '<td>Send Email</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
			echo '<tr>' . PHP_EOL;
			echo '<td><input type="checkbox" name="sendtext" value="true" /></td>' . PHP_EOL;
			echo '<td>Send Text Message</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
			
			echo '<tr><td colspan="3"><input type="submit" value="Start a Recall" /></td></tr>';
			echo '</table>' . PHP_EOL . '</form>' . PHP_EOL;
		}
		
		mysqli_close();
	}
	echo '<p><a href="index.php">Return</a></p>' . PHP_EOL;
	echo $template_footer;
}
?>