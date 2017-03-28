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
		
		echo '<table class="center">' . PHP_EOL;
		echo '<tr>' . PHP_EOL . '<td></td>' . PHP_EOL . '<td>First Name</td>' . PHP_EOL . '<td>Last Name</td>' . PHP_EOL . '<td>Billet</td>' . PHP_EOL . '<td>Department</td>' . PHP_EOL . '</tr>';
		$userlist_query = mysqli_query($mysql_connection, "SELECT userid, firstname, lastname, billetid, departmentid FROM users");
		while($row = mysql_fetch_rows($userlist_query)) {
			echo '<tr>' . PHP_EOL;
			echo '<td><input type="radio" name="users" value="' . $row['userid'] . '" /></td>' . PHP_EOL;
			echo '<td>' . $row['firstname'] . '</td>' . PHP_EOL;
			echo '<td>' . $row['lastname'] . '</td>' . PHP_EOL;
			// TODO Get billet name from billetid
			echo '<td>' . $row['billetid'] . '</td>' . PHP_EOL;
			// TODO Get department name from departmentid
			echo '<td>' . $row['departmentid'] . '</td>' . PHP_EOL;
			echo '</tr>' . PHP_EOL;
		}
		echo '</table>' . PHP_EOL;
		
		mysqli_close();
	}
	echo '<input type="submit" value="Start a Recall" />' . PHP_EOL . '</form>' . PHP_EOL;
	echo '<p><a href="index.php">Return</a></p>';
	echo $template_footer;
}
?>