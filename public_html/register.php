<?php

require_once( $_SERVER['DOCUMENT_ROOT'] . "/login_funcs.inc.php");

//retrieve our data from POST
$username = sanitiseUsername($_POST['username']);
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];

if($pass1 != $pass2){
    header('Location: /?msg=Passwords did not match');
}

if(strlen($username) > 30){
    header('Location: /?msg=Username too long');
}

// Sanitize username
$santisedUsername = preg_replace('/^[A-Za-z0-9_-]/', '', $username);
if( $santisedUsername != $username ){
	header('Location: /?msg=Username contains illigal characters');
}

// Generate a 32 character salt
$salt = md5( time() );

// Generate a salted hash of the password
$hash = saltAndHash( $salt, $pass1 );


include( $_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php");



// Query database using server session user id
$qryInsertUser = $db->prepare("	INSERT INTO `user` ( `username`, `password`, `salt` )
        						VALUES ( :username, :hash, :salt );
						"); 

$qryInsertUser->bindValue('username', $username, PDO::PARAM_INT);
$qryInsertUser->bindValue('hash', $hash, PDO::PARAM_STR);
$qryInsertUser->bindValue('salt', $salt, PDO::PARAM_STR);
$qryInsertUser->execute();
$qryInsertUser->closeCursor();

header('Location: /?msg=Registration+Successful!+Go+ahead+and+log+in&username=' . $username);
