<?php
session_start();

include('../includes/user_auth.php');
include('../includes/db.php');
include('../includes/user_info.php');

//var_dump($current_users_data);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Dashboard</title>
</head>

<body>

<?php
include ('../includes/user_header.php');

?>
</body>
</html>