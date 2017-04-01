<?php
/**
 * This page is used for adding new billets to the database.
 * @package addbillet.php
 */
@include 'config.php';
@include 'template.php';

session_start();
check_session();

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
 * Check POST variables to see if are contents to submit.
 */
function check_post() {
	global $_POST;
	global $template_footer;
	
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
			echo '<div class="alert alert-success" role="alert">Success: ' . $billet_name . " added.</div>";
		} else {
			echo '<div class="alert alert-danger" role="alert">Error: Could not add department to database.</p>';
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
	global $template_footer, $nav_sidebar;
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
	global $template_footer, $nav_sidebar;
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
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
	echo '</main>' . PHP_EOL;
}
?>