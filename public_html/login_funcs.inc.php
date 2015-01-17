<?php 

function validateUser($user_id,$username){
    session_regenerate_id (); //this is a security measure
    $_SESSION['valid'] = 1;
	$_SESSION['user_id'] = $user_id;
	$_SESSION['username'] = $username;
}

function isLoggedIn(){
    if(isset($_SESSION['valid']) && $_SESSION['valid'])
        return true;
    return false;
}

function logout(){
    $_SESSION = array(); //destroy all of the session variables
    session_destroy();
}

function saltAndHash( $salt, $password ){
	return hash('sha256', $salt . hash('sha256', $password) );
}

function sanitiseUsername( $strUsername ){
    $strUsername = trim( $strUsername );
    $strUsername = preg_replace('/ +/','_',$strUsername);
    $strUsername = preg_replace('/[^a-zA-Z0-9-_]/', '', $strUsername);
    return strToLower( $strUsername );
}
