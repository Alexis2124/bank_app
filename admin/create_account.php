<?php
session_start();
include("../includes/admin_auth.php");
include("../includes/db.php");
if(isset($_POST['submit'])){
	$error=array();
	if(empty($_POST['account_name'])){
		$error['account_name']="Please enter name";
		}
	if(empty($_POST['account_balance'])){
		$error['accunt_balance']="Please Enter Account Balance";
		}
		
	if(empty($_POST['account_type'])){
		$error['account_type']="please Enter Account Type";
		}
	if(!is_numeric($_POST['account_balance'])){
		$error['account']="Numeric Value Required";
		}
	if(empty($error)){
		//$initial=309;
		//$account=$initial.rand(100000000,9999999);
		$account="309".rand(1000000,9999999);
		//echo $account;
		$stmt=$conn->prepare("INSERT INTO customer VALUES(NULL,:anm,:an,:at,:ab,NOW(),NOW())");
		$data=array(
		":anm"=>$_POST['account_name'],
		":an"=>$account,
		":at"=>$_POST['account_type'],
		":ab"=>$_POST['account_balance']
		
		
		);
		$stmt->execute($data);
		header("location:view_account.php");
					}
		
	}
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<?php
include ('../includes/admin_header.php');

?>
<form action="" method="post">
<p>Account Name <input type="text" name="account_name" /> </p>
<p>Account Balance <input type="text" name="account_balance" /></p>

<select name="account_type">
<option disabled selected >--Select Account Type--</option>
<option value="Savings">Savings</option>
<option value="Current">Current</option>
</select><br />
<br />
<input type="submit" name="submit" value="Create Account" />

</form>

</body>
</html>