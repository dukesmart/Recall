<?php
@include 'config.php';
@include 'template.php';

echo $template_header;
echo <<<HERE
<form name="login" action="login.php" method="POST">
<table class="center">
		<tr>
		<td>Email Address: </td>
		<td><input type="text" name="email" /></td>
		</tr>
		<tr>
		<td>Password: </td>
		<td><input type="password" name="password" /></td>
		</tr>
		<tr>
		<td></td>
		<td><input type="submit" name="Submit" /></td>
		</tr>
</table>
</form>
HERE;
echo $template_end;
?>