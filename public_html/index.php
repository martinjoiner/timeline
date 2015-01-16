<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/login_funcs.inc.php");
// If the user has not logged in
if(isLoggedIn()){
    header('Location: /me/');
    die();
}
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Timeline</title>

    <?php include($_SERVER['DOCUMENT_ROOT'] . "/head_includes.inc.php"); ?>
    <link rel="stylesheet" href="/css/index.css" />
</head>
<body>
	<div class="header">

		<div class="vertline fore"></div>
		<div class="vertline fore"></div>
		<div class="vertline mid"></div>
		<div class="vertline mid"></div>
		<div class="vertline mid"></div>
		<div class="vertline back"></div>
		<div class="vertline back"></div>
		<div class="vertline back"></div>
		<div class="vertline back"></div>

		<div class="contwrap">
			<h1>Timeline</h1>

			<h2>Login</h2>

			<form name="register" action="login.php" method="post">
			    <label for="username">Username</label> <input type="text" id="username" name="username" maxlength="30" /><br>
			    <label for="password">Password</label> <input type="password" name="password" /><br>
			    <input type="submit" value="Login" /><br>
			</form>
		</div>

	</div>

	<div class="regwrap">
		<h2>Register</h2>

		<form name="register" action="register.php" method="post">
		    <input type="text" name="username" maxlength="30" /><label for="username">Username</label>
		    <input type="password" name="pass1" /><label for="pass1">Password</label>
		    <input type="password" name="pass2" /><label for="pass2">Password Again</label>
		    <input type="submit" value="Register" /><br>
		</form>
	</div>

		
    <script src="/js/index.js"></script>

</body>
</html>
