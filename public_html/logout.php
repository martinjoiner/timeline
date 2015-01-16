<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/db_connect.inc.php");
include($_SERVER['DOCUMENT_ROOT'] . "/login_funcs.inc.php");
logout();
header('Location: /');
