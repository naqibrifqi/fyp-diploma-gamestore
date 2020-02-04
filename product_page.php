<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Product Information</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include('./includes/header.html');
			
			$query = "SELECT prod_img, prod_id, prod_name, prod_price, stock, prod_desc, prod_avail FROM products WHERE prod_id = " . $_GET['prod_id'];
			$result = mysqli_query($dbc,$query);
			
			if ($result) {
				while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				echo '
				<div class="wrapper_product">
				<br /><form action="cart.php" method="get">
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
									</tr>'; 
									if($row['prod_avail'] == "Pre Order"){
										echo '<tr>
												<td>
													<h4 style="color:red"> Note that this item is Pre-Order</h4>
												</td>
											</tr>';
									}
									echo '<tr>
										<td>
											<h1>RM ' . number_format($row['prod_price'], 2) . '</h1>
										</td> 
									</tr>
									<tr>
										<td>
											<b>Quantity</b>: <input type="text" name="qty" size="5" value="1"/>
										</td>
									</tr>
									<tr>
										<td>
											<button type="submit" class="cart-button" onclick="return confirm(\'Add this item to cart?\');" name="submit"><span>
												<img src="images/cart.png" width="50px" height="50px"/>
												Add To Cart
											</span></button>
										</td> 
									</tr>';
									if($row['stock'] <= 0){
										$_SESSION['type'] = "Pre Order";
									}else{
										echo '<tr><td>
											<b>' . $row['stock'] . ' left</b>
										</td></tr>';
										$_SESSION['type'] = "In Stock";
									}
									echo'<tr style="width:20%">
										<td><span class="prod_desc">
											' . $row['prod_desc'] . '
										</span></td> 
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</form></div>';
				$_SESSION['prod_id'] = $row['prod_id'];
				$_SESSION['prod_price'] = $row['prod_price'];
				$_SESSION['prod_avail'] = $row['prod_avail'];
				}
			}else{
				echo mysqli_error($result);
			}
			echo '<br />';
			include('./prod_suggestion.php');
			echo '<br /><br />';
			include('./includes/footer.html');
		?>
	</body>
</html>