<html><head></head>
<body>

	<form action="./authenticate" method="POST">
	<label>UserName:</label>
	<input type="text" name="username">
	<input type="password" name="password">
	<input type="submit" value="Login">
	</form>
	<?php  echo $this->Session->flash('err_msg'); ?>
</body>
</html>