<?php
/**
 * This is the login page, and the start of the website.
 */

@include 'config.php';
@include 'template.php';

global $email_address, $hashed_password;
echo $template_header;
check_post();

/**
 *  Check POST variables to see if are contents to submit.
 */
function check_post() {
	if(!isset($_POST['email']) || !isset($_POST['password'])) {
		display_unsubmitted_page_contents();
		$template_footer;
		exit();
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
	
	/* Connected */
	$query_result = mysqli_query($mysql_connection, "SELECT * FROM users WHERE email='" . $email_address . "' AND password='" . $hashed_password . "';");
	display_submitted_page_contents($query_result);
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	$email_address = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
	$hashed_password = filter_var(hash('sha256', $_POST['password']), FILTER_SANITIZE_STRING);
}

/**
 * Display the login screen.
 */
function display_unsubmitted_page_contents() {
	echo <<<HERE
	<form name="login" action="" method="POST">
	<table class="center">
			<tr>
			<td>Email Address: </td>
			<td><input type="text" name="email" /></td>
			</tr>
			<tr>
			<td>Password: </td>
			<td><input type="password" name="password" /></td>
			</tr>
			<tr>
			<td></td>
			<td><input type="submit" name="Submit" /></td>
			</tr>
	</table>
	</form>
HERE;
}

/**
 * Display the contents of the page after login.
 * @param MySQL_Query_Result $query_result Results of the query containing the user's data row.
 */
function display_submitted_page_contents($query_result) {
	/* There are 0 results if there are no matching email/password combinations */
	if($query_result->num_rows == 0) {
		echo '<p class="error0">Error: Incorrect email address or password. <a href="index.php">Retry</a></p>';
		echo $template_footer;
		exit();
	}
	
	/* User exists, continue */
	$user = $query_result->fetch_assoc();
	echo '<p>Welcome, ' . $user['firstname'] . ' ' . $user['lastname'] . '.</p>';
	echo '<table class="left">
			<tr><td><a href="recall.php">Start a new recall</a></td></tr>
			<tr><td><a href="adduser.php">Add a new user</a></td></tr>
			<tr><td><a href="adddepartment.php">Add a new department</a></td></tr>
			<tr><td><a href="addbillet.php">Add a new billet</a></td></tr>
		</table>';
	mysqli_close($mysql_connection);
	echo $template_footer;
}
?>