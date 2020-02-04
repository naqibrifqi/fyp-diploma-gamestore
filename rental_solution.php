<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Rental Solutions - KITAMEN</title>
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
			<div class="content-wrapper">
				<?php
					$query = "SELECT prod_id, prod_img, prod_name, prod_price FROM products WHERE prod_type = 'Rental' ORDER BY date_added desc";
					$result = @mysqli_query($dbc, $query);
						
					if($result) {
						echo '<h1 style="font-family: Verdana;">Rental Solutions</h1><a href="product_catalogue.php" style="float:left;">< Pre Order</a><a href="instock.php" style="float:right;">In Stock ></a><hr>';
						echo '<h2>ELECTRONIC ENTERTAINMENT SOLUTION</h2>
						Professional Service<br />
						Solution for Corporate, Association, Organization, Clubs, Individuals, Event Organizers to infuse electronic entertainment into their events that can range from<br /><br />

						• Family Day<br />
						• Video Game Tournament or Competition<br />
						• Product Launching & Ehxibition<br />
						• Convocation fair<br />
						• Annual dinner<br />
						• Private Party. Eg: Birthday party, Student gathering, wedding reception<br />
						• Theme specific event, eg: sport, concert or music, kids etc<br /><br />

						*More solutions will be added in the future.<br />
						*DELIVERY TO WHOLE PENINSULA MALAYSIA (T&C Apply)<br /><br />';
						
						echo '<table align="center" cellspacing="2" cellpadding="9"><tr>';
						
						for($i=0, $max = 3; $row = mysqli_fetch_array($result, MYSQLI_ASSOC);) {
							if($i++ % $max == 0)
									echo '<tr>';
								
							echo '<td><center><a href="rental_page.php?prod_id=' . $row['prod_id'] .'"><img src="' . $row['prod_img'] . '" width="280px" height="350px"/><br />' . $row['prod_name'] . '<br />RM ' . number_format($row['prod_price'], 2) . '</a></center></td>';
							$_GET['prod_id'] = $row['prod_id'];
							
							if ($i % $max == 0)
								echo '</tr>';
						}
						echo '</table><br />';
					} else {	// If it did not run OK.
						echo '<h1 id="mainhead">System Error</h1>
						<p class="error">The data cannot be entered due to a system error. </p>';
						echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
						exit();
					}
				?>
			</div>
		</div>
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>