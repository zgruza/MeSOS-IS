<?php
$__VERSION__ = "1.3";
$admin_user = "admin";
$admin_passwd = "admin";

// Functions
function check_login(){
	if(empty($_SESSION['username'])){
		header('Location: index.php');
		session_destroy();
		exit;
	}
	if(!$user=$_SESSION['username']){
		header('Location: index.php');
		session_destroy();
		exit;
	}
	return $user;
}
?>
