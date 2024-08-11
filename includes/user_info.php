<?php

$statement = $conn ->prepare("SELECT * FROM customer WHERE customer_id=:cid");
$statement->bindparam(":cid",$_SESSION['id']);
$statement->execute();
$current_users_data =$statement->fetch(PDO::FETCH_BOTH);

if($statement->rowCount()<1){
	header("location:login.php?error=this record does'nt exist on our system");
	exit();
	}
	
	//$current_users_data=$statement->fetch(PDO::FETCH_BOTH);

?>