<!DOCTYPE php>
<?php
	session_start();
?>
<html>
	<head>
		<title>My Cart</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
			<?php
				require_once('config/mysql_connect.php');
				include('includes/header.html');
				
				echo '<div class="wrapper">';
				echo '<div class="content-wrapper">';
				
				echo '<div id="cart"><br />
				<div class="nav">
					<a href = "myaccount.php">My Account</a> > Delivery Information > Shipping Method > Payment
				</div><br /><hr>
				<h2>Shopping Cart</h2>';
				$mid = $_SESSION['member_id'];
				
				///[Remove Item from the cart]//////
				if(isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != ""){
					$key_to_remove = $_POST['index_to_remove'];
					if(count($_SESSION['cart']) <= 1) {
						unset($_SESSION['cart']);
					} else{
						unset($_SESSION['cart'][$key_to_remove]);
						sort($_SESSION['cart']);
					}
				}
				
				$sum = 0;
				$i = 0;
				if(isset($_SESSION['cart']) && $_SESSION['cart'] != 0){
					foreach($_SESSION['cart'] AS $cart){
						
						if(isset($_POST['remove_submit'])){
							unset($_SESSION['cart']['prod_id']['qty']['price']);
						} else{
						$query = "SELECT prod_img, prod_name, prod_avail, prod_price
						FROM products
						WHERE prod_id = '" . $cart['prod_id'] . "'";
						
						$result = @mysqli_query($dbc, $query);
						if($result){
							echo '<table>';
							while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
								echo'
									<tbody>
										<tr>
											<td width="10%"><img src="' . $row['prod_img'] . '"height="220" width="150" /></td>
											<td width="50%">' . $row['prod_name'] . '</td>
											<td width="10%">' . $row['prod_avail'] . '</td>
											<td width="10%">x' . $cart['qty'] . '</td>
											<td width="10%">RM ' . number_format($row['prod_price'], 2) . '</td>
											<td width="10%">RM ' . number_format($row['prod_price'] * $cart['qty'], 2) . '</td>
											<td width="10%">
												<form action="myaccount.php" method="post">
													<input type="Submit" name="delete_btn' . $cart['prod_id'] . '" value="x" />
													<input type="hidden" name="index_to_remove" value="' . $i .'"/>
												</form>
											</td>
										</tr>
									</tbody>';
									$_SESSION['total'] = number_format($row['prod_price'] * $cart['qty'], 2);
							}
							echo '</table>';
						} else {	// If it did not run OK.
							echo '<h1 id="mainhead">System Error</h1>
							<p class="error">The data cannot be entered due to a system error. </p>';
							echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
							exit();
						}
						$sum += $cart['price'] * $cart['qty'];
						}
						$i++;
					}
					echo '<table><tr><td width="10%"></td><td width="50%"></td><td width="10%"></td>
					<td width="15%">Subtotal RM ' . number_format($sum, 2) . '</td><td width="10%"></td></tr></table>';
					echo '<center><br /><p><a href="deliveryinformation.php" class="linkbtn">Checkout</a></p></center>';
				}else{
					echo '<h1>Your cart is empty</h1>';
				}
				
			?>
		</div>
		</div>
		</div>
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>