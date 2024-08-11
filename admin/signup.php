<?php
include('../includes/db.php');
//var_dump($conn);
if(isset($_POST['submit'])){
	$error=array();
	if(empty($_POST['name'])){
		$error['name']="Enter name";
		
		}
if(empty($_POST['email'])){
	$error['email']="Enter email";
	}else{	
	
	$statement=$conn->prepare("SELECT* FROM admin WHERE email=:em");
	$statement->bindparam(":em",$_POST['email']);
	$statement->execute();
	
	if($statement->rowCount() >0){
		$error['email']="Email already Exist";
		}
	
	//$row=$statement->fetch(PDO::FETCH_BOTH);
	
	}
	
if(empty($_POST['hash'])){
	$error['hash']="Enter Password";
	}
if(empty($_POST['confirm_hash'])){
	$error['confirm_hash']="Confirm password";
	
	}elseif($_POST['hash']!==$_POST['confirm_hash']){
		$error['confirm_hash']="Passowrd mismatch";
		}
	if(empty($error)){
		$encrypted =password_hash($_POST['hash'],PASSWORD_BCRYPT);
		
		$stmt=$conn->prepare("INSERT INTO admin VALUES(NULL,:nm,:em,:hsh,NOW(),NOW())");
		$data=array(
		":nm"=>$_POST['name'],
		":em"=>$_POST['email'],
		":hsh"=>$encrypted
		);
		$stmt->execute($data);
		header("location:login.php");
		exit();
		
		
		}
	
	
	
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Signup</title>
</head>

<body>

<form action="" method="post">


<?php
if(isset($error['name'])){
	echo $error['name'];
	}
if(isset($error['email'])){
	echo $error['email'];
	}
if(isset($error['hash'])){
	echo $error['hash'];
	}
//if(isset($error['confirm_hash'])){
	//echo $error['confirm_hash'];
	
	//}

?>

<p>Name:<input type="text" name="name"  /></p>
<p>Email:<input type="email" name="email" /></p>
<p>Password:<input type="password" name="hash"  /></p>
<p>Confirm Password:<input type="password" name="confirm_hash" /></p>
<input type="submit" name="submit" value="submit" />

</form>

</body>
</html>