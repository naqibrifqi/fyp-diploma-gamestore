<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Kitamen - Elctronics Entertainment</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
		?>
		
		<div class="wrapper">
			<?php
				if(isset($_POST['submit'])){
					if(isset($_GET['go'])){
						if(preg_match("^/[A-Za-z]+/", $_POST['search'])){ 
							$prod = $_POST['search'];
							$sql = mysqli_query($dbc,$dbc,"SELECT prod_img, prod_name, prod_price FROM products WHERE prod_name LIKE '%" . $prod . "%'");
							
							echo '<table>';
							while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
								$prod_name = $row['prod_name'];
								$prod_img = $row['prod_img'];
								$prod_price = $row['prod_price'];
								
								echo '<tr><td><img src="' . $row['prod_img'] . '"/></td>';
								echo "<td><a href=\"search.php?id=$prod_name\">" . $prod_name . "</td>";
								echo '<td>' . $row['prod_name'] . '</td>';
								echo '<td>' . $row['prod_price'] . '</td></tr>';
							}
							
							echo '</table>';
						}
					}
				}else{
					echo  "<p>Please enter a search query</p>"; 
				}
			?>
		</div>
	</body>