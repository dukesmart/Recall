<?php
@include 'config.php';
@include 'template.php';

echo $template_header;

/* Check to see if the required variables were set */
if(!isset($_POST['email']) || !isset($_POST['password'])) {
	echo '<p class="error0">Error: Email or password not set.</p>';
	echo $template_end;
	exit();
} else {
	$email_address = strtolower($_POST['email']);
	$hashed_password = hash('sha256', $_POST['password']);
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
	if($query_result->num_rows == 0) {
		echo '<p class="error0">Error: Incorrect email address or password. <a href="index.php">Retry</a></p>';
	}
	mysqli_close($mysql_connection);
	echo $template_end;
}
?>