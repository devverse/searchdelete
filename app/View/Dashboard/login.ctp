<html>
<title>Admin Dashboard</title>
<head><link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<style>
body { background-color: #EEEEEE; padding-bottom: 40px; padding-top: 40px; }
.form-signin { margin: 0 auto; max-width: 330px; padding: 15px; }
	.form-signin .form-signin-heading, .form-signin .checkbox {margin-bottom: 10px;}
	.form-signin .checkbox {font-weight: normal;}
	.form-signin .form-control { -moz-box-sizing: border-box; font-size: 16px; height: auto; padding: 10px; position: relative; }
		.form-signin .form-control:focus {z-index: 2;}
	.form-signin input[type="text"] { border-bottom-left-radius: 0; border-bottom-right-radius: 0; margin-bottom: -1px; }
	.form-signin input[type="password"] { border-top-left-radius: 0; border-top-right-radius: 0; margin-bottom: 10px; }
	#message { color: red}
</style>

</head>
<body>
    <div class="container">


	<form action="./authenticate" method="POST" class="form-signin">
	<label><span class="glyphicon glyphicon-lock"></span> Log in</label>
	<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
	<input type="password" name="password" class="form-control" placeholder="Password" required autofocus>
	<input type="submit" class="btn btn-lg btn-primary btn-block" value="Login">
	<span id="message"><?php  echo $this->Session->flash('err_msg'); ?></span>
	</form>
	
	</div>
  </body>
</body>
</html>