<?php
session_start();
if(!$_SESSION["member_id"]) {
	header("location:login.php");
}
?>

<html>
	<head>
		<title>Login Successful</title>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
		<?php
			include ('./includes/header.html');
		?>
		<div class="wrapper">
			<h1>Login Successful!</h1>
			<a href="myaccount.php" >My Account</a>
		</div><br><br><br><br><br><br><br><br><br>
			
	</body>
</html>