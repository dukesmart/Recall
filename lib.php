<?php
$mysql_connection = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);
if (!$mysql_connection) {
	echo '<p class="error0">Error: Unable to connect to MySQL.</p>\n';
	echo $template_end;
	exit;
} else {
	echo '<p>Successfully connected to MySQL Server</p>\n';
	echo '<table class="center">\n</table>';
	mysqli_close($mysql_connection);
	echo $template_end;
}
?>