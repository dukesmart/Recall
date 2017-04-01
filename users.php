<?php
/**
 * This page is used for adding new users to the database.
 * @package adduser.php
 */

@include 'config.php';
@include 'template.php';

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
	if(!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['password1']) || !isset($_POST['password2'])) {
		display_unsubmitted_page_contents();
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
	global $user_hashed_password, $user_privilege, $user_firstname, $user_lastname, $user_email, $user_phone, $user_billetid;
	global $mysql_connection, $template_footer, $mysql_host, $mysql_username, $mysql_password, $mysql_database, $query_result;
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
		$query_result = mysqli_query($mysql_connection, "INSERT INTO users (password, privilege, firstname, lastname, email, phone, billetid) VALUES ('" . $user_hashed_password . "', '" . $user_privilege . "', '" . $user_firstname . "', '" . $user_lastname . "', '" . $user_email . "', '" . $user_phone . "', '" . $user_billetid . "');");
		if($query_result) {
			echo '<p>Success: ' . $user_firstname . ' ' . $user_lastname . ' added.</p>';
		} else {
			echo '<p>Success! </p><p class="error1">Error: Could not add user to database.</p>';
		}
		echo $template_footer;
	}
}


/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $user_hashed_password, $user_privilege, $user_firstname, $user_lastname, $user_email, $user_phone, $user_billetid;
	$user_firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$user_lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$user_email = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
	$user_phone = filter_var(strtolower($_POST['phone']), FILTER_SANITIZE_STRING);
	$user_privilege = filter_var($_POST['privilege'], FILTER_SANITIZE_NUMBER_INT);
	
	if($_POST['password1'] == $_POST['password2']) {
		$user_hashed_password = filter_var(hash('sha256', $_POST['password1']), FILTER_SANITIZE_STRING);
	} else {
		echo '<p class="error0">Passwords do not match. <a href="adduser.php">Retry</a></p>';
	}
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_footer, $nav_sidebar;
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	echo '<form name="adduserform" action="adduser.php" method="POST">
		<table class="center">
				<tr>
				<td colspan="2">Add a new user</td>
				</tr>
				<tr>
				<td>First Name:</td>
				<td><input type="text" name="firstname" /></td>
				</tr>
				<tr>
				<td>Last Name:</td>
				<td><input type="text" name="lastname" /></td>
				</tr>
				<tr>
				<td>Email Address:</td>
				<td><input type="text" name="email" /></td>
				</tr>
				<tr>
				<td>Phone Number:</td>
				<td><input type="text" name="phone" /></td>
				</tr>
				<tr>
				<td>Privilege Level:</td>
				<td>
					<select name="privilege">
						<option value="0">Default</option>
						<option value="1">Student Administrator</option>
						<option value="2">Staff Administrator</option>
					</select>
				</td>
				<tr>
				<td>Billet:</td>
				<td>
					<select name="billetid">
						' . /* TODO: Dynamically generate billet list*/ '
					</select>
				</td>
				</tr>
				<tr>
				<td>Password:</td>
				<td><input type="password" name="password1" /></td>
				</tr>
				<tr>
				<td>Retype Password:</td>
				<td><input type="password" name="password2" /></td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" name="Submit" /></td>
				</tr>
		</table>
</form>';
	echo '</main>' . PHP_EOL;
	echo $template_footer;
}
?>