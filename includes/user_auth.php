<?php

if(!isset($_SESSION['id'])){
	header("location:login.php?error=ths page requires a login");
	die();
	
	}
?>