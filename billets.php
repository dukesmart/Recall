<?php
/**
 * This page is used for adding new billets to the database.
 * @package addbillet.php
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
	global $template_footer, $nav_sidebar;
	
	if(isset($_POST['billetname']) && ($_POST['billetname'] != "")) {
		submit_add();
	} else if(isset($_POST['billetid']) && ($_POST['billetid'] != "")) {
		submit_edit();
	} else {
		echo $nav_sidebar;
		echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
		display_unsubmitted_page_contents();
		echo $template_footer;
		exit();
	}
}

/**
 * Sets variables, and inserts contents into database.
 */
function submit_add() {
	global $template_footer, $nav_sidebar;
	global $mysql_connection, $billet_name, $billet_departmentid;
	check_vars();
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	$query_result = mysqli_query($mysql_connection, "INSERT INTO billets (name, departmentid) VALUES ('" . $billet_name . "', '" . $billet_departmentid . "');");
	if($query_result) {
		echo '<div class="alert alert-success" role="alert">Success: ' . $billet_name . " added.</div>";
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not add billet to database.</p>';
	}
	display_unsubmitted_page_contents();
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
	$query_result = mysqli_query($mysql_connection, "UPDATE billets SET name='" . $billet_edit . "', departmentid='" . $billet_departmentedit . "' WHERE billetid='" . $billet_id . "';");
	if($query_result) {
		echo '<div class="alert alert-success" role="alert">Success: billet name changed to ' . $billet_edit . ".</div>";
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not edit billet.</p>';
	}
	display_unsubmitted_page_contents();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $billet_name, $billet_department, $billet_edit, $billet_id;
	$billet_name = filter_var($_POST['billetname'], FILTER_SANITIZE_STRING);
	$billet_departmentid = filter_VAR($_POST['department'], FILTER_SANITIZE_NUMBER_INT);
	
	$billet_id = filter_var($_POST['billetid'], FILTER_SANITIZE_NUMBER_INT);
	$billet_edit = filter_var($_POST['billetedit'], FILTER_SANITIZE_STRING);
	$billet_departmentedit = filter_VAR($_POST['departmentid'], FILTER_SANITIZE_NUMBER_INT);
	
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
	echo '<h1>Billets</h1>' . PHP_EOL;
	echo '<h4>Add a new billet</h4>' .  PHP_EOL;
	echo '<form name="addbilletform" action="billets.php" method="POST">
		<table class="table">
				<tr>
				<td>Billet Name:</td>
				<td><input type="text" name="billetname" /></td>
				</tr>
				<tr>
				<td>Department:</td>
				<td>
					<select name="department">' . PHP_EOL;
	display_dropdown_department_list();
	echo '					</select>
				</td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" name="Submit" /></td>
				</tr>
		</table>
</form>';
	echo '<h4>Edit an existing billet</h4>' .  PHP_EOL;
	echo '<form name="editbilletform" action="billets.php" method="POST">
		<table class="table">
				<tr>
				<td>Billet Name:</td>
				<td>
					<select name="billetid">' .PHP_EOL;
	display_dropdown_billet_list();
	echo '				</select>
				</td>
				</tr>
				<tr>
				<td>New Billet Name:</td>
				<td><input type="text" name="billetedit" /></td>
				</tr>
				<tr>
				<td>Department:</td>
				<td>
					<select name="departmentid">' . PHP_EOL;
	display_dropdown_department_list();
	echo '					</select>
				</td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" name="Edit" /></td>
				</tr>
			</table>
</form>';
	echo '<h4>All billets</h4>' . PHP_EOL;
	echo '<div class="table-responsive">' . PHP_EOL;
	echo '<table class="table table-striped">';
	echo '<thead>' . PHP_EOL;
	echo
	'	<tr>
		<th>Billet Name</th>
		<th>Department</th>
	</tr>' . PHP_EOL;
	echo '</thead>' . PHP_EOL . '<tbody>' . PHP_EOL;
	display_billet_list();
	echo '</tbody>' . PHP_EOL;
	echo '</table>' . PHP_EOL . '</div>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
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

/**
 * Generates dropdown billet list.
 */
function display_dropdown_billet_list(){
	global $mysql_connection;
	
	$query_billetlist = mysqli_query($mysql_connection, "SELECT * FROM billets ORDER BY name;");
	if($query_billetlist){
		while($row = $query_billetlist->fetch_assoc()) {
			echo '						';
			echo '<option value="' . $row['billetid'] . '">' . $row['name'] . '</option>' . PHP_EOL;  //double check billet variable
		}
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not obtain billet list.</div>';
	}
}
 
/**
 * Generates billet list.
 */
function display_billet_list() {
	global $mysql_connection;
	
	$query_billetlist = mysqli_query($mysql_connection, "SELECT * FROM billets ORDER BY name;");
	if($query_billetlist) {
		while($row = $query_billetlist->fetch_assoc()) {
			$query_department = mysqli_query($mysql_connection, "SELECT * FROM billets WHERE billetid='" . $row['rootbilletid'] . "';");
			echo '	';
			echo '<tr><td>' . $row['name'] . '</td>';
			if($query_department && ($query_department->num_rows == 1)) {
				$billet = $query_department->fetch_assoc();
				echo '<td>' . $department['name'] . '</td></tr>' . PHP_EOL;
			} else {
				echo '<td><div class="alert alert-warning" role="alert">Could not obtain department name.</div></td>';
			}
		}
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not obtain billet list.</div>';
	}
}

mysqli_close($mysql_connection);
?>