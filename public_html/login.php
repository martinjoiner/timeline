<?php 
$username = $_POST['username'];
$password = $_POST['password'];

include( $_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php");

$username = mysql_real_escape_string($username);
$query = "SELECT id, password, salt
        FROM user
        WHERE username = '$username';";
$result = mysql_query($query);
if(mysql_num_rows($result) < 1) //no such user exists
{
    header('Location: index.php?msg=User does not exist');
}
$userData = mysql_fetch_array($result, MYSQL_ASSOC);

$hash = hash('sha256', $userData['salt'] . hash('sha256', $password) );
if($hash != $userData['password']) //incorrect password
{
    header('Location: index.php?msg=Incorrect Password');
}
else{
	include( $_SERVER['DOCUMENT_ROOT'] . "/login_funcs.inc.php");
	session_start();
    validateUser($userData['id'],$username); //sets the session data for this user
	header('Location: /me/');
}

// If the script has got this far, something's wrong

?>