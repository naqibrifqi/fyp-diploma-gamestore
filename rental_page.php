<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Product Page</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
			
			echo '<div class="wrapper">';
			$query = "SELECT prod_img, prod_id, prod_name, prod_price, stock, prod_desc FROM products WHERE prod_id = " . $_GET['prod_id'];
			$result = mysqli_query($dbc, $query);
			
			if ($result) {
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				echo '
				
				<div class="wrapper_product">
				<br /><form action="rental_process.php" method="get">
					<table>
						<tr>
							<td>
								<table>
									<tr>
										<td rowspan = "7" style="vertical-align: top; padding: 50px;">
											<img src="' . $row['prod_img'] . '" width="250" height="350"/>
										</td>
									</tr>
									<tr>
										<td>
											<h2>' . $row['prod_name'] . '</h2>
										</td>
									</tr>
									<tr>
										<td>
											<h1>RM ' . number_format($row['prod_price'], 2) . '</h1>
										</td> 
									</tr>
									<tr>
										<td>
												<button type="submit" class="cart-button"><span>
													<img src="images/cart.png" width="50px" height="50px"/>
													Rental Process
												</span></button>
										</td> 
									</tr>';
									if($row['stock'] == 0){
										$_SESSION['type'] = "Pre Order";
									}else{
										echo '<tr><td>
											<b>' . $row['stock'] . ' left</b>
										</td></tr>';
										$_SESSION['type'] = "In Stock";
									}
									echo'<tr style="width:20%">
										<td>
											' . nl2br($row['prod_desc']) . '
										</td> 
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</form></div>';
				$_SESSION['prod_id'] = $row['prod_id'];
				$_SESSION['prod_price'] = $row['prod_price'];
				}
			}
		?>
	</body>
</html>