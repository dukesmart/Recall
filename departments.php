<?php
/**
 * This is the page in which an administrator may add new departments.
 * @package adddepartment.php
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
	if(!isset($_POST['departmentname']) /*|| !isset($_POST['rootbillet'])*/) {
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
	global $mysql_connection, $department_name, $template_footer, $mysql_host, $mysql_username, $mysql_password, $mysql_database, $query_result;
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
		$query_result = mysqli_query($mysql_connection, "INSERT INTO departments (name) VALUES ('" . $department_name . "');");
		if($query_result) {
			echo '<p>Success: ' . $department_name . ' added.</p>';
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
	global $department_name, $department_root_billet;
	
	$department_name = filter_var($_POST['departmentname'], FILTER_SANITIZE_STRING);
	//$department_root_billet = filter_var($_POST['rootbillet'], FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer, $nav_sidebar;
	echo '<div class="left-container">' . PHP_EOL . '<table class="left">
			<tr><td><a href="adddepartment.php">Add another department</a></td></tr>
			<tr><td><a href="index.php">Return</a></td></tr>
		</table>' . PHP_EOL . '</div>' . PHP_EOL;
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	echo '<form name="adddepartmentform" action="" method="POST">
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