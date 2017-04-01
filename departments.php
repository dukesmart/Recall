<?php
/**
 * This is the page in which an administrator may add new departments.
 * @package adddepartment.php
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
			echo '<div class="alert alert-success" role="alert"> Success: ' . $department_name . ' added.</div>';
		} else {
			echo '<div class="alert alert-danger" role="alert">Error: Could not add department to database.</div>';
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
	
	echo $nav_sidebar;
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_footer, $nav_sidebar;
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	echo '<h1>Departments</h1>' . PHP_EOL;
	echo '<form name="adddepartmentform" action="departments.php" method="POST">
		<table class="table">
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
</form>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
}
?>