<?php
/**
 * This is the login page, and the start of the website.
 * @package index.php
*/

@include 'config.php';
@include 'template.php';

check_session();

/**
 * Check to see if a user has previously logged in.
 */
function check_session() {
	global $_SESSION;
	global $mysql_error_connect, $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database, $query_result;
	
	if(isset($_SESSION['email'])) {
		/* Connect to the MySQL server */
		$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
		if (!$mysql_connection) {
			/* Couldn't connect */
			echo $mysql_error_connect;
			echo $template_footer;
			exit;
		} else {
			/* Connected */
			$user_query = mysqli_query($mysql_connection, "SELECT * FROM users WHERE email='" . $email_address . "';");
			/* There are 0 results if there are no matching email/password combinations */
			if($user_query->num_rows == 0) {
				echo '<p class="error0">Error: Incorrect email address or password. <a href="index.php">Retry</a></p>' . PHP_EOL;
				echo $template_footer;
				exit();
			} else {
				display_submitted_page_contents($user_query);
			}
			
			mysqli_close($mysql_connection);
		}
	} else {
		echo $template_header;
		check_post();
	}
}

/**
 *  Check POST variables to see if are contents to submit.
 */
function check_post() {
	if(!isset($_POST['email']) || !isset($_POST['password'])) {
		display_unsubmitted_page_contents();
		$template_footer;
		exit();
	} else {
		submit();
	}
}

/**
 * Sets variables, connects to database, submits query to database.
 */
function submit() {
	global $template_footer;
	global $mysql_error_connect, $mysql_connection, $mysql_host, $mysql_username, $mysql_password, $mysql_database, $query_result;
	global $email_address, $hashed_password;
	check_vars();
	
	/* Connect to the MySQL server */
	$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	if (!$mysql_connection) {
		/* Couldn't connect */
		echo $mysql_error_connect;
		echo $template_footer;
		exit;
	} else {
		/* Connected */
		$user_query = mysqli_query($mysql_connection, "SELECT * FROM users WHERE email='" . $email_address . "' AND password='" . $hashed_password . "';");
		/* There are 0 results if there are no matching email/password combinations */
		if($user_query->num_rows == 0) {
			echo '<p class="error0">Error: Incorrect email address or password. <a href="index.php">Retry</a></p>' . PHP_EOL;
			echo $template_footer;
			exit();
		} else {
			$_SESSION['email'] = $email_address;
			display_submitted_page_contents($user_query);
		}
		
		mysqli_close($mysql_connection);
	}
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	global $email_address, $hashed_password;
	$email_address = filter_var(strtolower($_POST['email']), FILTER_SANITIZE_EMAIL);
	$hashed_password = filter_var(hash('sha256', $_POST['password']), FILTER_SANITIZE_STRING);
}



/**
 * Display the login screen.
 */
function display_unsubmitted_page_contents() {
	echo <<<HERE
	<form name="login" action="" method="POST">
		<div class="container col-xs-12 col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 col-lg-offset-4 col-lg-4">
			<br />
			<div class="panel panel-default">
				<div class="panel-heading">
					<h1>Login</h1>
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-user" style="width: auto"></i>
							</span>
							<input id="emailField" type="text" class="form-control" name="email" placeholder="Email Address" required="" />
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-lock" style="width: auto"></i>
							</span>
							<input id="passwordField" type="password" class="form-control" name="password" placeholder="Password" required="" />
						</div>
					</div>
					<input type="submit" id="submitButton" value="Login" class="btn btn-default" style="width: 100%" />
				</div>
			</div>
		</div>
	</form>
HERE;
	echo $template_footer;
}

/**
 * Display the contents of the page after login.
 * @param MySQL_Query_Result $query_result Results of the query containing the user's data row.
 */
function display_submitted_page_contents($user_query) {
	global $template_footer, $nav_sidebar;

	/* User exists, continue */
	$user = $user_query->fetch_assoc();
	
	echo $nav_sidebar;
	echo '<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">' . PHP_EOL;
	echo '<h1>Dashboard</h1>' . PHP_EOL;
	/* if(recall) {
	 * 	echo '<p> There is not a recall in progress. </p>';
	 * } else {
	 * 	
	 * }
	 */
	echo '</main>' . PHP_EOL;
	
	/*echo '<div class="content-container">';
	echo '<p>Welcome, ' . $user['firstname'] . ' ' . $user['lastname'] . '.</p>';
	if($user['privilege'] >= 2) {
		echo '<form name="search" action="search.php" method="GET">' . PHP_EOL;
		echo '<input type="text" name="searchquery" value="Search..." />' . PHP_EOL;
		echo '<input type="submit" name="submit" value="Search" />' . PHP_EOL;
		echo '</form>' . PHP_EOL;
	}
	echo '</div>' . PHP_EOL;
	echo '<div class="left-container">' . PHP_EOL . '<table class="left">' . PHP_EOL;
	if($user['privilege'] >= 2) {
		echo '<tr><td><a href="recall.php">Start a new recall</a></td></tr>
		<tr><td><a href="adduser.php">Add a new user</a></td></tr>
		<tr><td><a href="adddepartment.php">Add a new department</a></td></tr>
		<tr><td><a href="addbillet.php">Add a new billet</a></td></tr>';
		
	}
	echo '</table></div>' . PHP_EOL;*/
	echo $template_footer;
}

$content =
'			<main class="col-sm-9 offset-sm-3 col-md-10 offset-md-2 pt-3">
				<h1>Dashboard</h1>
		
				<section class="row text-center placeholders">
					<div class="col-6 col-sm-3 placeholder">
						<img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
						<h4>Label</h4>
						<div class="text-muted">Something else</div>
					</div>
					<div class="col-6 col-sm-3 placeholder">
						<img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
						<h4>Label</h4>
						<span class="text-muted">Something else</span>
					</div>
					<div class="col-6 col-sm-3 placeholder">
						<img src="data:image/gif;base64,R0lGODlhAQABAIABAAJ12AAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
						<h4>Label</h4>
						<span class="text-muted">Something else</span>
					</div>
					<div class="col-6 col-sm-3 placeholder">
						<img src="data:image/gif;base64,R0lGODlhAQABAIABAADcgwAAACwAAAAAAQABAAACAkQBADs=" width="200" height="200" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
						<h4>Label</h4>
						<span class="text-muted">Something else</span>
					</div>
				</section>
		
				<h2>Section title</h2>
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Header</th>
								<th>Header</th>
								<th>Header</th>
								<th>Header</th>
							</tr>
						</thead>
              			<tbody>
							<tr>
								<td>1,001</td>
								<td>Lorem</td>
								<td>ipsum</td>
								<td>dolor</td>
								<td>sit</td>
							</tr>
							<tr>
								<td>1,002</td>
								<td>amet</td>
								<td>consectetur</td>
								<td>adipiscing</td>
								<td>elit</td>
							</tr>
						</tbody>
					</table>
				</div>
			</main>' . PHP_EOL;
?>