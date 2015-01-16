<?php 
$username = $_POST['username'];
$password = $_POST['password'];

include( $_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php");

$username = mysql_real_escape_string($username);


// Query database using server session user id
$qryUser = $db->prepare("	SELECT `id`, `password`, `salt` 
					        FROM `user`
					        WHERE username = :username 
					        LIMIT 1 
						"); 

$qryUser->bindValue('username', $username, PDO::PARAM_INT);
$qryUser->execute();
$rslt = $qryUser->fetchAll(PDO::FETCH_ASSOC);
$result = $rslt[0];
$qryUser->closeCursor();

if( sizeof($rslt) == 0 ){
	//no such user exists
    header('Location: /?msg=User does not exist');
}
$userData = $rslt[0];

require_once( $_SERVER['DOCUMENT_ROOT'] . "/login_funcs.inc.php");

$hash = saltAndHash( $userData['salt'], $password );

if( $hash != $userData['password'] ){
	// Incorrect password
    header('Location: /?msg=Incorrect Password');
} else {
	
	session_start();
    validateUser($userData['id'], $username ); //sets the session data for this user
	header('Location: /me/');
}

// If the script has got this far, something's wrong
