<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>PreOrder - KITAMEN</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
		?>
			
		<div class ="wrapper">
			<?php
				$query = "SELECT prod_id, prod_img, prod_name, prod_price FROM products WHERE prod_type = 'Video Game' 
				AND prod_avail = 'Pre Order' ORDER BY date_added desc";
				// $query = "SELECT prod_id, prod_img, prod_name, prod_price FROM products WHERE prod_type = 'Video Game' ORDER BY date_added desc";
				$result = @mysqli_query($dbc, $query);
					
				if($result) {
					echo '<h1 style="font-family: Verdana; text-align:Center">Pre Order Items</h1><a href="instock.php" style="float:left;">< In Stock</a><a href="rental_solution.php" style="float:right;">Rental ></a><hr>';
					echo '<table align="center" cellspacing="0" cellpadding="5" border="1px"><tr>';
					
					for($i=0, $max = 4; $row = mysqli_fetch_array($result, MYSQLI_NUM);) {
						if($i++ % $max == 0)
								echo '<tr>';
							
						echo '<td><center><a href="product_page.php?prod_id=' . $row[0] .'"><img src="' . $row[1] . '" width="250px" height="350px"/><br />' . $row[2] . '<br />RM ' . number_format($row[3], 2) . '</a></center></td>';
						$_GET["prod_id"] = $row[0];
						
						if ($i % $max == 0)
							echo '</tr>';
					}
					echo '</table><br />';

				}else{
					printf("Error:", mysqli_error($dbc));
				}
			?>
		</div>
		
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>