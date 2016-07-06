<?php
	session_start();
	$_SESSION["logged_in"] = 0;
	session_destroy();	
	header("location:index.php");
?>