<?php
@include 'config.php';
@include 'template.php';

echo $template_header;
if(!isset($_POST['departmentname']) || !isset($_POST['rootbillet']) || ($_POST['departmentname'] == "") || ($_POST['rootbillet'] == "")) {
	echo $template_header;
	echo '<form name="adddepartmentform" action="adddepartment.php" method="POST">
		<table class="center">
				<tr>
				<td colspan="2">Add a new Department</td>
				</tr>
				<tr>
				<td>Department Name:</td>
				<td><input type="text" name="departmentname" /></td>
				</tr>
				<tr>
				<td>Root Billet:</td>
				<td>
					<select name="department">
						' . /* TODO: Dynamically generate billet list*/ '
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
	echo $template_header;
	$department_name = filter_var($_POST['departmentname'], FILTER_SANITIZE_STRING);
	//$department_root_billet = filter_var($_POST['rootbillet'], FILTER_SANITIZE_NUMBER_INT);
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
	$query_result = mysqli_query($mysql_connection, "INSERT INTO departments (name) VALUES (`" . $department_name . "`);");

}
?>