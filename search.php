<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Kitamen - Elctronics Entertainment</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
		?>
		
		<div class="wrapper">
			<?php
				if(isset($_POST['searched']) && !empty($_POST['search'])){
							$prod = $_POST['search'];
							$sql = mysqli_query($dbc, "SELECT prod_id, prod_img, prod_name, prod_price, stock, prod_avail FROM products WHERE prod_name LIKE '%" . $prod . "%'");
							
							echo '<h3>Youve searched for "' . $prod . '". These are the search result.</h3>';
							echo '<form action="search.php" method="post" id="searchform">
							<input type="text" name="search" placeholder="search all products" value="' . $prod .'"size="30" />
							<input type="hidden" name="searched" value="TRUE" />
						</form>';
							echo '<table cellspacing="10">';
							while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
								$prod_name = $row['prod_name'];
								$prod_img = $row['prod_img'];
								$prod_price = $row['prod_price'];
								$prod_id = $row['prod_id'];
								$stock = $row['stock'];
								$prod_avail = $row['prod_avail'];
								
								echo '<br /><tr><td><img src="' . $row['prod_img'] . '" width="250" height="350"/></td>';
								echo '<td>
									<table cellspacing="10">
										<tr><td><h2><a href=product_page.php?prod_id=' . $prod_id . '>' . $prod_name . '</a></td></tr>
										<tr><td><h1>RM ' . number_format($row['prod_price'], 2) .'</h1></td></tr>
										<tr><td>' . $prod_avail . '</td></tr>';
										if($prod_avail == "In Stock"){
											echo '<tr><td>' . $stock . ' In Stock. Get it now!</td></tr>';
										} else{
											echo '<tr><td></td></tr>';
										}
									echo '</table>
								</td>
								</tr>';
							}
							
							echo '</table><br />';
				}else{
					echo  "<h3>Please enter a search query</h3>"; 
				}
			?>
		</div>
	</body>