<?php
session_start();
include("db_connect.inc.php");
include("login_funcs.inc.php");
logout();
header('Location:index.php');
?>