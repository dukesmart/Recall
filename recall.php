<?php
/**
 * This page is the page in which an administrator may start a recall.
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
	global $_SESSION;
	global $template_header;
	
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
	global $_SESSION;
	global $template_footer;
	global $mysql_connection;
	global $send_email, $send_text, $recall_recipients;
	check_vars();
	
	
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	
	$recall_date = date("Y-m-d H:i:s");
	// TODO Add initiator ID
	/* Insert the recall */
	$initiator_query = mysqli_query($mysql_connection, "SELECT userid FROM users WHERE email='" . $_SESSION['email'] . "';");
	
	/* There are 0 results if there are no matching email/password combinations */
	if($initiator_query->num_rows == 0) {
		echo '<div class="alert alert-danger" role="alert">Error: Incorrect email address or password.</div>' . PHP_EOL;
		echo '</main>' .  PHP_EOL;
		echo $template_footer;
		exit();
	} else {
		$initiator = $initiator_query->fetch_assoc();
		
		$recall_query = mysqli_query($mysql_connection, "INSERT INTO recalls (datetime, message, initiatorid) VALUES ('" . $recall_date . "', '" . $recall_message . "', '" . $initiator['userid'] . "');");
		if(!$recall_query) {
			/* Query failed */
			echo '<div class="alert alert-danger" role="alert">Error: Failed to initiate recall. </div>';
			echo $template_footer;
			exit();
		} else {
			/* Query to get the recall ID */
			$recall_result = mysqli_query($mysql_connection, "SELECT * FROM recalls WHERE datetime='" . $recall_date . "';");
			if(!$recall_result) {
				/* Query failed */
				echo '<div class="alert alert-danger" role="alert">Error: Failed to initiate recall. </div>';
				echo $template_footer;
				exit();
			} else {
				$recall = $recall_result->fetch_assoc();
				$recall_id = $recall['recallid'];
				echo $recallid;
				foreach($recall_recipients as $recipient) {
					$recipient_query = mysqli_query($mysql_connection, "INSERT INTO recipients (userid, recallid) VALUES ('" . $recipient . "', '" . $recall_id . "');");
					if(!$recipient_query) {
						/* Query failed */
						echo '<div class="alert alert-danger" role="alert">Error: Failed to add recipient. </div>';
						echo $template_footer;
						exit();
					} else {
						$destination_user_query = mysqli_query($mysql_connection, "SELECT email, phone FROM users WHERE userid='" . $recipient . "';");
						$destination_user = $destination_user_query->fetch_assoc();
						$url_query = mysqli_query($mysql_connection, "SELECT * FROM recipients WHERE userid='" . $recipient . "' AND recallid='" . $recall_id . "' ORDER BY recipientid DESC LIMIT 1;");
						$url = $url_query->fetch_assoc();
						$generated_url = "http://gemini.ruinscraft.com/confirm.php?id=" . $url['recipientid'];
						if($send_email) {
							mail($destination_user['email'], "Recall", $generated_url);
							//TODO Send email here
						}
						if($send_text) {
							//TODO Send text message here
						}
					}
				}
				display_submitted_page_contents();
			}
		}
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
		echo '<div class="alert alert-danger" role="alert">Error: At least one contact method must be selected (Email, text message). </div>';
		echo $template_footer;
		exit;
	}
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer, $template_footer;
	echo '<div class="alert alert-success" role="alert">Recall initiated.</div>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $mysql_error_connect;
	global $template_footer, $template_footer;
	global $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database;
	
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	echo '<form name="recall" action="" method="POST">' . PHP_EOL;
	echo '<h1>Recalls</h1>' . PHP_EOL;
	$userlist_query = mysqli_query($mysql_connection, "SELECT userid, firstname, lastname, billetid FROM users ORDER BY lastname LIMIT 500;");
	if(!$userlist_query) {
		/* Query success */
		echo '<div class="alert alert-danger" role="alert">Failed to get user list from database.</div>' . PHP_EOL;
	} else {
		/* Query Failure */
		echo '<form name="recallusers" action="" method="POST">' . PHP_EOL;
		echo '<table class="table">' . PHP_EOL;
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
	echo '</main>' . PHP_EOL;
	echo $template_footer;
}

mysqli_close($mysql_connection);
?>