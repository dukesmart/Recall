<?php
/**
 * This is the page in which an administrator may add new departments.
 * @package adddepartment.php
 */
@include 'template.php';
@include 'lib.php';

if(mysql_setup()) {
	session_start();
	check_session();
} else {
	exit();
}

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
	
	if(isset($_POST['departmentname']) && ($_POST['departmentname'] != "")) {
		submit_add();
	} else if(isset($_POST['departmentid']) && ($_POST['departmentid'] != "")) {
		submit_edit();
	} else {
		display_unsubmitted_page_contents();
		echo $template_footer;
		exit();
	}
}

/**
 * Sets variables, and inserts contents into database.
 */
function submit_add() {
	global $mysql_error_connect, $template_footer;
	global $mysql_connection, $department_name, $department_root_billet;
	check_vars();
	
	$query_result = mysqli_query($mysql_connection, "INSERT INTO departments (name, rootbilletid) VALUES ('" . $department_name . "', '" . $department_root_billet . "');");
	if($query_result) {
		echo '<div class="alert alert-success" role="alert"> Success: ' . $department_name . ' added.</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not add department to database.</div>';
	}
	display_submitted_page_contents();
	
}

/**
 * Sets variables, and updates contents in database.
 */
function submit_edit() {
	global $template_footer, $nav_sidebar;
	global $mysql_connection;
	global $billet_id, $billet_edit, $billet_departmentedit;
	check_vars();
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	$sql = "UPDATE departments SET ";
	if(($billet_edit == "") || ($billet_edit == NULL)) {
		$sql = $sql . "rootbilletid='" . $department_root_billet_new . "' WHERE departmentid='" . $department_id . "';";
	} else {
		$sql = $sql . "name='" . $department_edit . "', rootbilletid='" . $department_root_billet_new . "' WHERE departmentid='" . $department_id . "';";
	}
	
	$query_result = mysqli_query($mysql_connection, $sql);
	if($query_result) {
		echo '<div class="alert alert-success" role="alert">Success.</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not edit billet.</div>';
	}
	display_unsubmitted_page_contents();
}

/**
 * Sets variables, and inserts contents into database.
 */
function submit_edit() {
	global $template_footer, $nav_sidebar;
	global $mysql_connection;
	global $department_id, $department_edit, $department_root_billet_new;
	check_vars();
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	$query_result = mysqli_query($mysql_connection, "UPDATE departments SET name='" . $department_edit . "', rootbilletid='" . $department_root_billet_new . "' WHERE departmentid='" . $department_id . "';");
	if($query_result) {
		echo '<div class="alert alert-success" role="alert">Success: billet name changed to ' . $department_edit . ".</div>";
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not edit billet.</p>';
	}
	display_unsubmitted_page_contents();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $department_name, $department_root_billet, $department_id, $department_edit, $department_edit, $department_root_billet_new;
	
	$department_name = filter_var($_POST['departmentname'], FILTER_SANITIZE_STRING);
	$department_id = filter_var($_POST['departmentid'], FILTER_SANITIZE_NUMBER_INT);
	$department_edit = filter_var($_POST['departmentedit'], FILTER_SANITIZE_STRING);
	$department_root_billet_new = filter_VAR($_POST['newrootbillet'], FILTER_SANITIZE_NUMBER_INT);
	$department_root_billet = filter_var($_POST['rootbillet'], FILTER_SANITIZE_NUMBER_INT);
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
	echo '<h4>Add a new department</h4>' . PHP_EOL;
	echo '<form name="adddepartmentform" action="departments.php" method="POST">
		<div class="table-responsive">
			<table class="table">
					<tr>
					<td>Department Name:</td>
					<td><input type="text" name="departmentname" /></td>
					</tr>
					<tr>
					<td>Root Billet:</td>
					<td>
						<select name="rootbillet">
							<option value="0">Default</option>' . PHP_EOL;
	display_dropdown_billet_list();
	echo '						</select>
					</td>
					</tr>
					<tr>
					<td></td>
					<td><input type="submit" name="Submit" /></td>
					</tr>
			</table>
		</div>
	</form>' . PHP_EOL;
	
	echo '<h4>Edit an existing billet</h4>' .  PHP_EOL;
	echo '<form name="editdepartmentform" action="departments.php" method="POST">
		<table class="table">
				<tr>
				<td>Department Name:</td>
				<td>
					<select name="departmentid">' .PHP_EOL;
	display_dropdown_department_list();
	echo '				</select>
				</td>
				</tr>
				<tr>
				<td>New Department Name:</td>
				<td><input type="text" name="departmentedit" /></td>
				</tr>
				<tr>
				<td>Root Billet:</td>
				<td>
					<select name="newrootbillet">' . PHP_EOL;
	display_dropdown_billet_list();
	echo '					</select>
				</td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" name="Edit" /></td>
				</tr>
			</table>
</form>';
	echo '<h4>All departments</h4>' . PHP_EOL;
	echo '<div class="table-responsive">' . PHP_EOL;
	echo '<table class="table table-striped">' . PHP_EOL;
	echo '<thead>' . PHP_EOL;
	echo 
'	<tr>
		<th>Department Name</th>
		<th>Root Billet</th>
	</tr>' . PHP_EOL;
	echo '</thead>' . PHP_EOL . '<tbody>' . PHP_EOL;
	display_department_list();
	echo '</tbody>' . PHP_EOL;
	echo '</table>' . PHP_EOL . '</div>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
}

/**
 * Generates billet dropdown list.
 */
function display_dropdown_billet_list() {
	global $mysql_connection;
	
	$query_billetlist = mysqli_query($mysql_connection, "SELECT * FROM billets ORDER BY name;");
	if($query_billetlist) {
		while($row = $query_billetlist->fetch_assoc()) {
			echo '							';
			echo '<option value="' . $row['billetid'] . '">' . $row['name'] . '</option>' . PHP_EOL;
		}
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not obtain billet list.</div>' . PHP_EOL;
	}
}

/**
 * Generates department list.
 */
function display_department_list() {
	global $mysql_connection;
	
	$query_departmentlist = mysqli_query($mysql_connection, "SELECT * FROM departments ORDER BY name;");
	if($query_departmentlist) {
		while($row = $query_departmentlist->fetch_assoc()) {
			$query_billet = mysqli_query($mysql_connection, "SELECT * FROM billets WHERE billetid='" . $row['rootbilletid'] . "';");
			echo '	';
			echo '<tr><td>' . $row['name'] . '</td>';
			if($query_billet && ($query_billet->num_rows == 1)) {
				$billet = $query_billet->fetch_assoc();
				echo '<td>' . $billet['name'] . '</td></tr>' . PHP_EOL;
			} else {
				echo '<td><div class="alert alert-warning" role="alert">Could not obtain billet name.</div></td>';
			}
		}
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not obtain billet list.</div>';
	}
}

/**
 * Generates department dropdown list.
 */
function display_dropdown_department_list() {
	global $mysql_connection;
	
	$query_deptlist = mysqli_query($mysql_connection, "SELECT * FROM departments ORDER BY name;");
	if($query_deptlist) {
		while($row = $query_deptlist->fetch_assoc()) {
			echo '						';
			echo '<option value="' . $row['departmentid'] . '">' . $row['name'] . '</option>' . PHP_EOL;
		}
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not obtain department list.</div>';
	}
}

mysqli_close($mysql_connection);
?>