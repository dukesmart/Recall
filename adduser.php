<?php
@include template.php;
@include config.php;

echo $template_header;
if(!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['email']) || !isset($_POST['phone']) || !isset($_POST['password']) || 
		($_POST['firstname'] == "") || ($_POST['lastname'] == "") || ($_POST['email'] == "") || ($_POST['phone'] == "") || ($_POST['password'] == "")) {
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
				<td></td>
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
	echo $template_footer;
	exit();
} else {
	$user_firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$user_lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$user_email = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
	$user_phone = filter_var(strtolower($_POST['phone']), FILTER_SANITIZE_STRING);
	$user_hashed_password = filter_var(hash('sha256', $_POST['password']), FILTER_SANITIZE_STRING);
	$user_privilege = filter_var($_POST['privilege'], FILTER_SANITIZE_NUMBER_INT);
}

/* Connect to the MySQL server */
$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
if (!$mysql_connection) {
	/* Couldn't connect */
	echo '<p class="error0">Error: Unable to connect to MySQL.</p>\n';
	echo $template_footer;
	exit;
} else {
	/* Connected */
	$query_result = mysqli_query($mysql_connection, "INSERT INTO users (password, privilege, firstname, lastname, email, phone, billetid) VALUES (`" . $user_hashed_password . "`, `" . $user_privilege . "`, `" . $user_firstname . "`, `" . $user_lastname . "`, `" . $user_email . "`, `" . $user_phone . "`, `" . $user_billetid . "`);");
	
}
?>