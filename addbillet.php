<?php
/**
 * This page is used for adding new billets to the database.
 */
@include 'config.php';
@include 'template.php';

echo $template_header;
check_post();

/**
 * Check POST variables to see if are contents to submit.
 */
function check_post() {
	
}
if(!isset($_POST['billetname']) || ($_POST['billetname'] == "") || !isset($_POST['department']) || ($_POST['department'] == "")) {
	echo $template_footer;
	exit();
} else {
	submit();
}

/**
 * Sets variables, connects to database, and inserts contents into database.
 */
function submit() {
	check_vars();
	/* Connect to the MySQL server */
	$mysql_connection = connect();
	if(!$mysql_connection){
		exit();
	}
	
	/* Connected */
	$query_result = mysqli_query($mysql_connection, "INSERT INTO billets (name) VALUES (`" . $billet_name . "`);");
	display_submitted_page_contents();
	
	mysqli_close();
}

/**
 * Filter submitted contents and set the variables locally.
 */
function check_vars() {
	$billet_name = filter_var($_POST['billetname'], FILTER_SANITIZE_STRING);
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
	echo '<form name="addbilletform" action="addbillet.php" method="POST">
		<table class="center">
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
					<select name="department">
						' . /* TODO: Dynamically generate department list*/ '
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