<?php
@include template.php;
@include config.php;

echo $template_header;
if(!isset($_POST['departmentname']) || ($_POST['departmentname'] == null)) {
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
				<td></td>
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