<?php
/**
 * This page is used for adding new users to the database.
 * @package adduser.php
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
	
	if(isset($_SESSION['email'])) {
		check_post();
	} else {
		header("location:index.php");
	}
}

/**
 * Check POST variables to see if are contents to submit.
 */
function check_post() {
	global $_POST, $template_header, $_SESSION;
	echo $template_header;
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	
	if(isset($_SESSION['privilege']!=0) && isset($_POST['submit'])) {
		submit_add();
	} else if (($_SESSION['privilege']!=0) && isset($_POST['edit']))  {
		submit_edit();
	} else {
		display_unsubmitted_page_contents;
		echo $template_footer;
		exit();
	}
}

/**
 * Sets variables, connects to database, and inserts contents into database.
 */
function submit_add() {
	global $mysql_error_connect, $template_footer;
	global $user_hashed_password, $user_privilege, $user_firstname, $user_lastname, $user_email, $user_phone, $user_billetid;
	global $mysql_connection, $template_footer;
	check_vars();
	
	$query_result = mysqli_query($mysql_connection, "INSERT INTO users (password, privilege, firstname, lastname, email, phone, billetid) VALUES ('" . $user_hashed_password . "', '" . $user_privilege . "', '" . $user_firstname . "', '" . $user_lastname . "', '" . $user_email . "', '" . $user_phone . "', '" . $user_billetid . "');");
	if($query_result) {
		echo '<div class="alert alert-success" role="alert">Success: ' . $user_firstname . ' ' . $user_lastname . ' added.</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not add user to database.</div>';
	}
	display_unsubmitted_page_contents();
}

/**
 * Sets variables, connects to database, and changes contents in database.
 */
function submit_edit() {
	global $template_footer;
	global $user_privilege_edit, $user_firstname_edit, $user_lastname_edit, $user_email_edit, $user_phone_edit, $user_billetid_edit, $user_hashed_password_edit;
	global $mysql_connection;
	check_vars();
	
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	$sql = "UPDATE users SET ";
	if(($user_firstname_edit != "") || ($user_firstname_edit != NULL)){
		$sql = $sql . "firstname='" . $user_firstname_edit . "' WHERE email='" . $user_email_edit . "';";
	}
	if (($user_lastname_edit != "") || ($user_lastname_edit != NULL)){
		$sql = $sql . "lastname='" . $user_lastname_edit . "' WHERE email='" . $user_email_edit . "';";	
	}			
	if (($user_phone_edit != "") || ($user_phone_edit != NULL)){
		$sql = $sql . "phone='" . $user_phone_edit . "' WHERE email='" . $user_email_edit . "';";	
	}				
	if (($user_privilege_edit != "") || ($user_privilege_edit != NULL)){
		$sql = $sql . "privilege='" . $user_privlege_edit . "' WHERE email='" . $user_email_edit . "';";
	}	
	if (($user_billetid_edit != "") || ($user_billetid_edit != NULL)){
		$sql = $sql . "billetid='" . $user_billetid_edit . "' WHERE email='" . $user_email_edit . "';";	
	}		
	if ($user_hashed_password_edit == "") || ($user_hashed_password_edit == NULL)){
		$sql = $sql . "password='" . $user_hashed_password_edit . "' WHERE email='" . $user_email_edit . "';";
	}
	
	$query_result = mysqli_query($mysql_connection, $sql);
	if($query_result) {
		echo '<div class="alert alert-success" role="alert">Success: ' . $user_email_edit . ' has been modified.</div>';
	} else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not edit existing user.</div>';
	}
	display_unsubmitted_page_contents();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	
	global $user_hashed_password, $user_privilege, $user_firstname, $user_lastname, $user_email, $user_phone, $user_billetid;
	
	$user_firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
	$user_lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
	$user_email = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
	$user_phone = filter_var(strtolower($_POST['phone']), FILTER_SANITIZE_STRING);
	$user_privilege = filter_var($_POST['privilege'], FILTER_SANITIZE_NUMBER_INT);
	$user_billetid = filter_var($_POST['billetid'], FILTER_SANITIZE_NUMBER_INT);
	
	$user_firstname_edit = filter_var($_POST['firstname_edit'], FILTER_SANITIZE_STRING);
	$user_lastname_edit = filter_var($_POST['lastname_edit'], FILTER_SANITIZE_STRING);
	$user_email_edit = filter_var(strtolower($_POST['email_edit']), FILTER_SANITIZE_EMAIL);
	$user_phone_edit = filter_var(strtolower($_POST['phone_edit']), FILTER_SANITIZE_STRING);
	$user_privilege_edit = filter_var($_POST['privilege_edit'], FILTER_SANITIZE_NUMBER_INT);
	$user_billetid_edit = filter_var($_POST['billetid_edit'], FILTER_SANITIZE_NUMBER_INT);
	
	if($_POST['password1'] == $_POST['password2']) {
		$user_hashed_password = filter_var(hash('sha256', $_POST['password1']), FILTER_SANITIZE_STRING);
	} else {
		echo '<div class="alert alert-danger" role="alert">Passwords do not match. </div>';
	}
	
	if($_POST['password1_edit'] == $_POST['password2_edit']) {
		$user_hashed_password_edit = filter_var(hash('sha256', $_POST['password1_edit']), FILTER_SANITIZE_STRING);
	} else {
		echo '<div class="alert alert-danger" role="alert">Passwords do not match. </div>';
	}
}

/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_header, $template_footer;
	
	echo '<h1>Users</h1>' . PHP_EOL;
	echo '<h4>Add a new user</h4>';
	echo '<form name="adduserform" action="users.php" method="POST">
		<div class="table-responsive">
		<table class="table">
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
				<td>Department:</td>
				<td>
					<select name="departmentid">
						<option value="0">Default</option>' . PHP_EOL;
	display_dropdown_department_list();
	echo '					</select>
				</td>
				</tr>
				<tr>
				<td>Billet:</td>
				<td>
					<select name="billetid">
						<option value="0">Default</option>' . PHP_EOL;
	display_dropdown_billet_list();
	echo '					</select>
				</td>
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
		</div>
</form>' . PHP_EOL;
	echo '<h4>Edit an existing user</h4>';
	echo '<form name="edituserform" action="users.php" method="POST">
		<div class="table-responsive">
		<table class="table">
				<tr>
				<td>E-mail of Existing User:</td>
				<td><input type="text" name="email_edit" /></td>
				</tr>
				<tr>
				<td>Change of First Name:</td>
				<td><input type="text" name="firstname_edit" /></td>
				</tr>
				<tr>
				<td>Change of Last Name:</td>
				<td><input type="text" name="lastname_edit" /></td>
				</tr>
				<tr>
				<td>Change of Phone Number:</td>
				<td><input type="text" name="phone_edit" /></td>
				</tr>
				
				<tr>
				<td>Change of Department:</td>
				<td>
					<select name="departmentid_edit">
						<option value="0">Default</option>' . PHP_EOL;
	display_dropdown_department_list();
	echo '					</select>
				</td>
				</tr>
				<tr>
				<td>Change of Billet:</td>
				<td>
					<select name="billetid_edit">
						<option value="0">Default</option>' . PHP_EOL;
	display_dropdown_billet_list();
	echo '					</select>
				</td>
				</tr>
				<tr>
				<td>Change of Privilege Level: (Must be Administrator!)</td>
				<td>
					<select name="privilege_edit">
						<option value="0">Default</option>
						<option value="1">Student Administrator</option>
						<option value="2">Staff Administrator</option>
					</select>
				</td>
				<tr>
				<td>Change of Password:</td>
				<td><input type="password" name="password1_edit" /></td>
				</tr>
				<tr>
				<td>Retype Password:</td>
				<td><input type="password" name="password2_edit" /></td>
				</tr>
				<tr>
				<td></td>
				<td><input type="submit" name="Edit" /></td>
				</tr>
		</table>
		</div>
</form>' . PHP_EOL;
	
	echo '<h4>All users</h4>' . PHP_EOL;
	echo '<div class="table-responsive">' . PHP_EOL;
	echo '<table class="table table-striped">';
	echo '<thead>' . PHP_EOL;
	echo
	'	<tr>
		<th>Last Name</th>
		<th>First Name</th>
		<th>Email Address</th>
		<th>Phone Number</th>
		<th>Billet</th>
	</tr>' . PHP_EOL;
	echo '</thead>' . PHP_EOL . '<tbody>' . PHP_EOL;
	display_user_list();
	echo '</tbody>' . PHP_EOL;
	echo '</table>' . PHP_EOL . '</div>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
	echo '</main>' . PHP_EOL;
	echo $template_footer;
}

/**
 * Generates billet dropdown list.
 */
function display_dropdown_billet_list() {
	global $mysql_connection;
	
	$query_billetlist = mysqli_query($mysql_connection, "SELECT * FROM billets ORDER BY name;");
	if($query_billetlist) {
		while($row = $query_billetlist->fetch_assoc()) {
			echo '						';
			echo '<option value="' . $row['billetid'] . '">' . $row['name'] . '</option>' . PHP_EOL; 
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

/**
 * Generates user list.
 */
function display_user_list() {
	global $mysql_connection;
	
	$query_userlist = mysqli_query($mysql_connection, "SELECT lastname, firstname, email, phone, billetid FROM users ORDER BY lastname LIMIT 500;");
	if($query_userlist) {
		while($row = $query_userlist->fetch_assoc()) {
			$query_billet = mysqli_query($mysql_connection, "SELECT * FROM billets WHERE billetid='" . $row['billetid'] . "';");
			echo '	';
			echo '<tr><td>' . $row['lastname'] . '</td><td>' . $row['firstname'] . '</td><td>' . $row['email'] . '</td><td>' . $row['phone'] . '</td>';
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

mysqli_close($mysql_connection);
?>