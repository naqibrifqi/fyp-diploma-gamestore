<?php
session_start();
if(!$_SESSION["admin"] && !$_SESSION['member_id']) {
	header("location:login.php");
}
?>

<html>
	<head>
		<title>Login Successful</title>
	</head>
	<body>
		<?php
			include('./includes/admin_header.html');
		?>
		<div class="wrapper">
			<h1>Admin Login Successful!</h1>
			<a href="admindash.php" >Admin Dashboard</a>
		</div>
	</body>
</html>