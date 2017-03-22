<?php
@include 'config.php';
@include 'template.php';

echo $template_header;
if(!isset($_POST['billetname']) || ($_POST['billetname'] == "") || !isset($_POST['department']) || ($_POST['department'] == "")) {
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
	echo $template_footer;
	exit();
} else {
	$billet_name = filter_var($_POST['billetname'], FILTER_SANITIZE_STRING);
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
	$query_result = mysqli_query($mysql_connection, "INSERT INTO billets (name) VALUES (`" . $billet_name . "`);");

}
?>