<?php
/**
 * This page is used for adding new billets to the database.
 * @package addbillet.php
 */
@include 'config.php';
@include 'template.php';

echo $template_header;
check_post();

/**
 * Check POST variables to see if are contents to submit.
 */
function check_post() {
	global $_POST;
	if(!isset($_POST['billetname']) || ($_POST['billetname'] == "")/* || !isset($_POST['department']) || ($_POST['department'] == "")*/) {
		display_unsubmitted_page_contents();
		echo $template_footer;
		exit();
	} else {
		submit();
	}
}

/**
 * Sets variables, connects to database, and inserts contents into database.
 */
function submit() {
	global $mysql_error_connect, $template_footer;
	global $mysql_connection, $billet_name, $template_footer, $mysql_host, $mysql_username, $mysql_password, $mysql_database, $query_result;
	check_vars();
	
	/* Connect to the MySQL server */
	$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$mysql_connection) {
		/* Couldn't connect */
		echo $mysql_error_connect;
		echo $template_footer;
		exit();
	} else {
		/* Connected */
		$query_result = mysqli_query($mysql_connection, "INSERT INTO billets (name) VALUES ('" . $billet_name . "');");
		if($query_result) {
			echo '<p>Success: ' . $billet_name . " added.</p>";
		} else {
			echo '<p>Success! </p><p class="error1">Error: Could not add department to database.</p>';
		}
		display_submitted_page_contents();
	
		mysqli_close();
	}
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $billet_name;
	$billet_name = filter_var($_POST['billetname'], FILTER_SANITIZE_STRING);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer;
	echo '<div class="left-container">' . PHP_EOL . '<table class="left">
			<tr><td><a href="addbillet.php">Add another billet</a></td></tr>
			<tr><td><a href="index.php">Return</a></td></tr>
		</table>' . PHP_EOL . '</div>' . PHP_EOL;
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	echo '<form name="addbilletform" action="addbillet.php" method="POST">
		<table class="center">
				<tr>
				<td colspan="2">Add a new Billet</td>
				</tr>
				<tr>
				<td>Billet Name:</td>
				<td><input type="text" name="billetname" /></td>
				</tr>
				<tr>
				<td>Department:</td>
				<td>
					<select name="department">
						' . /* TODO: Dynamically generate department list*/ '
					</select>
				</td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" name="Submit" /></td>
				</tr>
		</table>
</form>';
	echo '<p><a href="index.php">Return</a></p>';
}
?>