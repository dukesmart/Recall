<?php
@include 'config.php';
@include 'template.php';

echo $template_header;
/* Check to see if the required variables were set */
if(!isset($_POST['email']) || !isset($_POST['password'])) {
	echo <<<HERE
<form name="login" action=" method="POST">
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
	echo $template_end;
	exit();
} else {
	$email_address = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
	$hashed_password = filter_var(hash('sha256', $_POST['password']), FILTER_SANITIZE_STRING);
}

/* Connect to the MySQL server */
$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
if (!$mysql_connection) {
	/* Couldn't connect */
	echo '<p class="error0">Error: Unable to connect to MySQL.</p>\n';
	echo $template_end;
	exit;
} else {
	/* Connected */
	$query_result = mysqli_query($mysql_connection, "SELECT * FROM users WHERE email='" . $email_address . "' AND password='" . $hashed_password . "';");
	/* There are 0 results if there are no matching email/password combinations */
	if($query_result->num_rows == 0) {
		echo '<p class="error0">Error: Incorrect email address or password. <a href="index.php">Retry</a></p>';
		echo $template_end;
		exit();
	}
	/* User exists, continue */
	$user = $query_result->fetch_assoc();
	echo '<p>Welcome, ' . $user['firstname'] . ' ' . $user['lastname'] . '</p>';
	mysqli_close($mysql_connection);
	echo $template_end;
}

?>