<?php
/**
 * This is the page in which an administrator may add new departments.
 */

@include 'config.php';
@include 'template.php';

global $department_name, $department_root_billet;
echo $template_header;
check_post();

/**
 * Check POST variables to see if are contents to submit.
 */
function check_post() {
	if(!isset($_POST['departmentname']) || !isset($_POST['rootbillet']) || ($_POST['departmentname'] == "") || ($_POST['rootbillet'] == "")) {
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
	/* Connect to the MySQL server */
	$mysql_connection = connect();
	if(!$mysql_connection){
		exit();
	}
	
	/* Connected */
	$query_result = mysqli_query($mysql_connection, "INSERT INTO departments (name) VALUES (`" . $department_name . "`);");
	display_submitted_page_contents();
	
	mysqli_close();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	$department_name = filter_var($_POST['departmentname'], FILTER_SANITIZE_STRING);
	//$department_root_billet = filter_var($_POST['rootbillet'], FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {

}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
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
	echo '<p><a href="index.php">Return</a></p>';
}
?>