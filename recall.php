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
	global $mysql_error_connect, $template_footer;
	global $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database;
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
		
		mysqli_close();
	}
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
	global $mysql_error_connect, $template_footer;
	global $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database;
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
			echo '<p class="error1">Failed to get user list from database.</p>' . PHP_EOL;
		} else {
			echo '<form name="recallusers" action="" method="POST">' . PHP_EOL;
			echo '<table class="center">' . PHP_EOL;
			echo '<tr>' . PHP_EOL . '<th></th>' . PHP_EOL . '<th>First Name</th>' . PHP_EOL . '<th>Last Name</th>' . PHP_EOL . '<th>Billet</th>' . PHP_EOL . '</tr>' . PHP_EOL;
			/* Display each user as a checkable option in a table */
			while($row = $userlist_query->fetch_assoc()) {
				/* Query to get the billet name from the billet ID */
				$row_billet_query = mysqli_query($mysql_connection, "SELECT name FROM billets WHERE billetid='" . $row['billetid'] . "';");
				echo '<tr>' . PHP_EOL;
				echo '<td><input type="checkbox" name="users" value="' . $row['userid'] . '" /></td>' . PHP_EOL;
				echo '<td>' . $row['firstname'] . '</td>' . PHP_EOL;
				echo '<td>' . $row['lastname'] . '</td>' . PHP_EOL;
				// TODO Get billet name from billetid
				if(!$row_billet_query) {
					echo '<td><p class="error1">Failed to get billet from database.</p></td>' . PHP_EOL;
				} else {
					$row_billet = $row_billet_query->fetch_assoc();
					echo '<td>' . $row_billet['name'] . '</td>' . PHP_EOL;
				}
				// TODO Get department name from departmentid
				//echo '<td>' . $row['departmentid'] . '</td>' . PHP_EOL;
				echo '</tr>' . PHP_EOL;
			}
			echo '<tr><td colspan="3"><input type="submit" value="Start a Recall" /></td></tr>';
			echo '</table>' . PHP_EOL . '</form>' . PHP_EOL;
		}
		
		mysqli_close();
	}
	echo '<p><a href="index.php">Return</a></p>';
	echo $template_footer;
}
?>