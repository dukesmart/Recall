<?php
@include template.php;
@include config.php;

echo $template_header;
if(!isset($_POST['billetname']) || ($_POST['billetname'] == null)) {
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
				<td></td>
				<td><input type="submit" name="Submit" /></td>
				</tr>
		</table>
</form>';
	echo $template_footer;
	exit();
}
?>