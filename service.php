<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
		<title>Services & Products</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
		?>
		
		<div class ="wrapper">
			<center><h1>Services And Products</h1></center>
			<hr>
			<table class="service-table">
				<tr>
					<td><a href="instock.php"><img src="images/instock.png" width="300" height="150" /></a></td>
					<td><a href="product_catalogue.php"><img src="images/preorder.png" width="300" height="150" /></a></td>
					<td><a href="rental_solution.php"><img src="images/rental.png" width="300" height="150" /></a></td>
				</tr>
			</table>
		</div>
	</body>
</html>