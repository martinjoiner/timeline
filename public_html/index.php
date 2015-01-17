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

			<?php 
			if( $_GET['msg'] ){
				?>
				<div class="msg">
					<?=htmlspecialchars( $_GET['msg'] )?>
				</div>
				<?php
			}
			?>

			<h2>Login</h2>

			<form class="frmLogin" action="login.php" method="post">
			    <div class="formRow">
			    	<label for="username">Username</label>
			    	<input type="text" id="username" name="username" maxlength="30" value="<?=sanitiseUsername( $_GET['username'] )?>">
			    </div>
			    <div class="formRow">
			    	<label for="password">Password</label>
			    	<input type="password" id="password" name="password" />
			    </div>
			    <input type="submit" value="Login" /><br>
			</form>
		</div>

	</div>

	<div class="regWrap">
		<h2>Create a new account</h2>

		<form name="register" action="register.php" method="post">
			<div class="formRow">
				<label for="username">Username</label>
		    	<input type="text" name="username" id="username" maxlength="30" required>
		    </div>
			<div class="formRow">
		    	<label for="pass1">Password</label>
		    	<input type="password" name="pass1" id="pass1" required>
		    </div>
		    <div class="formRow">
		    	<label for="pass2">Password Again</label>
		    	<input type="password" name="pass2" id="pass2" required>
		    </div>
		    <div>
		    	<input type="submit" value="Register" />
		    </div>
		</form>
	</div>

    <script src="/js/index.js"></script>

</body>
</html>
