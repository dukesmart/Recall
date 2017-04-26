<?php
/**
 * Page used to execute and display search results.
* @package search.php
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
 * Display Battle Buddy info.
 */
function display_battle_buddy_info() {

	global $mysql_connection;

	$query_userlist = mysqli_query($mysql_connection, "SELECT lastname, firstname, email, phone, billetid FROM users ORDER BY lastname LIMIT 500;");

	if($query_userlist) {
		$query_userlist = mysqli_query($mysql_connection, "SELECT * FROM users WHERE battlebuddyid='" . $row['battlebuddyid'] . "';");
		echo '	';
		echo '<tr><td>' . $row['lastname'] . '</td><td>' . $row['firstname'] . '</td><td>' . $row['email'] . '</td><td>' . $row['phone'] . '</td>';
		if($query_billet && ($query_userlist->num_rows == 1)) {
			$user = $query_userlist->fetch_assoc();
			echo '<td>' . $user['name'] . '</td></tr>' . PHP_EOL;
		} else {
			echo '<td><div class="alert alert-warning" role="alert">Could not obtain battle buddy name.</div></td>';
		}
	}
	else {
		echo '<div class="alert alert-danger" role="alert">Error: Could not obtain battle buddy info.</div>';
	}
}
/**
 * Display search results.
 */
function search_results(){
	if(isset($_POST['query_userlist'])){
		if(isset($_GET['searchquery'])){
			if(preg_match("/^[  a-zA-Z]+/", $_POST['searchquery'])){
				$name=$_POST['searchquery'];
				//connect  to the database
				$db=mysql_connect  ("servername", "username",  "password") or die ('I cannot connect to the database  because: ' . mysql_error());
				//-select  the database to use
				$mydb=mysql_select_db("recall");
				//-query  the database table
				$sql="SELECT firstname, lastname FROM users WHERE lastname LIKE '%" . $name .  "%'";
				//-run  the query against the mysql query function
				$result=mysql_query($sql);
				//-create  while loop and loop through result set
				while($row=mysql_fetch_array($result)){
					$FirstName  =$row['firstname'];
					$FirstInitial =substr($FirstName,0,1);
					$LastName=$row['lastname'];
					//-display the result of the array
					echo "<ul>\n";
					echo "<li>" . "<a  href=\"search.php?id=$ID\">"   .$FirstInitial . " " . $LastName .  "</a></li>\n";
					echo "</ul>";
				}
			}
			else{
				echo  "<p>Please enter a search query</p>";
			}
		}
	}
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
 *  Check POST variables to see if are contents to submit.
 */
function check_post() {
	if(!isset($_GET['searchquery'])) {
		display_unsubmitted_page_contents();
		exit();
	} else {
		submit();
	}
}
/**
 * Sets variables, connects to database, submits query to database.
 */
function submit() {
	global $mysql_error_connect, $template_footer;
	global $mysql_connection;
	check_vars();

	//Search function
	display_submitted_page_contents();
	search_results();
}
/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	$sanitized_searchquery = filter_var($_GET['searchquery'], FILTER_SANITIZE_STRING);
}
/**
 * Display the contents of the page after the form has been submitted.
 */
function display_submitted_page_contents() {
	global $template_header, $template_footer;

	echo $template_header;

	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	search_results();

	echo $template_footer;
}
/**
 * Display the submission form page contents.
 */
function display_unsubmitted_page_contents() {
	global $template_header, $template_footer;

	echo $template_header;
	echo get_nav_sidebar('index', isadmin($_SESSION['email']));
	echo $template_footer;
}
mysqli_close($mysql_connection);
?>
