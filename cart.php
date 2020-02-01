<!DOCTYPE php>
<?php
	session_start();
?>

<html>
	<head>
		<title>Cart</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include('./includes/header.html');
			
			echo '<div class="wrapper">';
			if(isset($_SESSION['member_id']) && $_SESSION['member_id'] != ""){
			
				if(isset($_GET['submit'])) {
					$mid = $_SESSION['member_id'];
					$pid = $_SESSION['prod_id'];
					$qty = $_GET['qty'];
					$price = $_SESSION['prod_price'];
					$avail = $_SESSION['prod_avail'];
					$_SESSION['cart'][] = array('prod_id' => $pid, 'qty' => $qty, 'price' => $price, 'prod_avail' => $avail);
					echo'<h1> Item has been added</h1>';
					
					foreach($_SESSION['cart'] AS $cart){
						
						$query="SELECT prod_img, prod_name, prod_price
						FROM products
						WHERE prod_id = '" . $cart['prod_id'] . "'";
						$result= @mysqli_query($dbc, $query);
						
						if($result){
							echo'<table class="cart-table">';
								
								while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
									echo'
										<tr>
											<td><img src="' . $row['prod_img'] . '"height="220" width="150" /></td>
											<td>' . $row['prod_name'] . '</td>
											<td>x' . $cart['qty'] . '</td>
											<td>RM ' . number_format($row['prod_price'], 2) . '</td></tr>';
								}
							echo '</table>';
						} else {	// If it did not run OK.
							echo '<h1 id="mainhead">System Error</h1>
							<p class="error">The data cannot be entered due to a system error. </p>';
							echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
							exit();
						}
					}
					echo '<br><p><a href="product_catalogue.php" class="linkbtn">Continue Shopping</a></p><br /><br />';
					echo '<a href="myaccount.php" class="linkbtn">Checkout</a>';
				}	
			} else{
				echo '<p>Only registered member are authorised to add to cart,</p>
				<p>Why not be a <a href="register.php">member</a>?</p>
				Already a member? Login <a href="login.php">here</a>!';
			}
		?>
	</div>
	</body>
</html>