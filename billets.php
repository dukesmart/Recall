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
		$query_result = mysqli_query($mysql_connection, "INSERT INTO billets (name, department) VALUES ('" . $billet_name . "', '" . $billet_department . "');");
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
	global $billet_name, $billet_department;
	$billet_name = filter_var($_POST['billetname'], FILTER_SANITIZE_STRING);
	$billet_department = filter_VAR($_POST['department'], FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_footer, $nav_sidebar;
	
	echo $template_footer;
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_footer, $nav_sidebar;
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	echo '<h1>Billets</h1>' . PHP_EOL;
	echo '<form name="addbilletform" action="billets.php" method="POST">
		<table class="table">
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
					<select name="department">' . PHP_EOL;
	display_department_list();
					echo '
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

/**
 * Generates department dropdown list.
 */
function display_department_list() {
	global $template_footer, $mysql_host, $mysql_username, $mysql_password, $mysql_database, $query_result, $mysql_error_connect;
	
	/* Connect to the MySQL server */
	$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$mysql_connection) {
		/* Couldn't connect */
		echo $mysql_error_connect . PHP_EOL . '</main>' . PHP_EOL;
		echo $template_footer;
		exit();
	} else {
		/* Connected */
		$query_deptlist = mysqli_query($mysql_connection, "SELECT * FROM departments ORDER BY name;");
		if($query_deptlist) {
			while($row = $query_deptlist->fetch_assoc()) {
				echo '						';
				echo '<option value="' . $row['departmentid'] . '">' . $row['name'] . '</option>' . PHP_EOL; 
			}
		} else {
			echo '<div class="alert alert-danger" role="alert">Error: Could not add user to database.</div>';
		}
	}
}
?>