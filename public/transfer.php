<?php
session_start();
include('../includes/user_auth.php');
include('../includes/db.php');
include('../includes/user_info.php');
 if(isset($_POST['pay'])){
	 $issue=array();
	 
	 if(empty($_POST['account_number'])){
		 $issue['account_number']="Please Enter Account Number";
		 }elseif(!is_numeric($_POST['account_number'])){
			 $issue['account_number']="Please enter a numeric value";
			 }
		if(empty($_POST['amount'])){
			$issue['amount']="Please specify Amount";
			}elseif(!is_numeric($_POST['amount'])){
				$issue['amount']="Enter a numeric value";
				}
		if(empty($issue)){
			//var_dump($_POST);
			//$post = array_map('TRIM',$_POST);
			//var_dump($post);
			
			//die();
			
			//check if current user has up to that amount
		if($_POST['amount'] > $current_users_data['account_balance']){
			header("location:transfer.php?error=Insufficient fund");
			exit();
			}
			
			$fetch_beneficiary = $conn->prepare("SELECT * FROM customer WHERE account_number=:an");
			$fetch_beneficiary->bindparam(":an",$_POST['account_number']);
			$fetch_beneficiary->execute();
			
			//if record of the beneficiary doesnt exist
			if($fetch_beneficiary->rowCount() < 1){
				header("location:transfer.php?error=Account Number Doesnt Exist");
				exit();
				}
			//if it exists collect the beneficiary record
			$beneficiary_record=$fetch_beneficiary->fetch(PDO::FETCH_BOTH);
			//var_dump($beneficiary_record);
			
			//check if user is playing on th app
		if($current_users_data['customer_id']==$beneficiary_record['customer_id']){
			header("location:transfer.php?error=You can not send money to yourself");
			exit();
			}
		//debit transaction
		$senders_opening_balance=$current_users_data['account_balance'];
		$senders_closing_balance=$senders_opening_balance - $_POST['amount'];
		$debit=$conn->prepare("UPDATE customer SET account_balance=:ab WHERE account_number=:cua");
	$debit->bindparam(":ab",$senders_closing_balance);
	$debit->bindparam(":cua",$current_users_data['account_number']);
	$debit->execute();
		
		//header("location:transfer.php");
		//log a transaction
		$debit_transaction=$conn->prepare("INSERT INTO transaction VALUES(NULL,:sa,:ra,:ta,:pb,:fb,:tt,:cst,NOW(),NOW())");
		$data=array(
		":sa"=>$current_users_data['account_number'],
		":ra"=>$beneficiary_record['account_number'],
		":ta"=>$_POST['amount'],
		":pb"=>$senders_opening_balance,
		":fb"=>$senders_closing_balance,
		":tt"=>"debit",
		":cst"=>$current_users_data['customer_id']
		
			);
			$debit_transaction->execute($data);
		
		//credit transaction
		$beneficiary_opening_balance=$beneficiary_record['account_balance'];
		$beneficiary_closing_balance=$beneficiary_opening_balance+$_POST['amount'];
		$credit=$conn->prepare("UPDATE customer SET account_balance=:ab WHERE account_number=:ban" );
		$credit->bindparam(":ab",$beneficiary_closing_balance);
		$credit->bindparam(":ban",$beneficiary_record['account_number']);
		$credit->execute();
		
		
		//Log A Transaction
		$credit_transaction=$conn->prepare("INSERT INTO transaction VALUES(NULL,:sa,:ra,:ta,:pb,:fb,:tt,:cst,NOW(),NOW())");
		$credit_data = array(
		":sa"=>$current_users_data['account_number'],
		":ra"=>$beneficiary_record['account_number'],
		":ta"=>$_POST['amount'],
		":pb"=>$beneficiary_opening_balance,
		":fb"=>$beneficiary_closing_balance,
		":tt"=>"credit",
		":cst"=>$beneficiary_record['customer_id']
		
		);
		$credit_transaction->execute($credit_data);
		
		header("location:transfer.php?success=Transaction Successful");
		
		
		
		
		
		
			}
	 
	
	
	 }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Transfer</title>
</head>

<body>
<?php
include('../includes/user_header.php');

if(isset($_GET['error'])){
	echo "<p style='color:red'>".$_GET['error']."</p>";
	}
if(isset($_GET['success'])){
	echo "<p style='color:green'>".$_GET['success']."</p>";
	}	
?>
<form action="" method="post">
<p>Account Number:<input type="text" name="account_number" /> </p>
<p>Transaction Amount:<input type="text" name="amount" /></p>
<input type="submit" name="pay" value="Transfer" />
</body>
</html>