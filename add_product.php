<!DOCTYPE html>
<?php
	session_start();
	if($_SESSION['admin'] == 0) {
		header("location:login.php");
	}
?>
<html>
	<head>
		<title>Products Cataloging</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
	</head>
	
	<body class="admin-body">
		<?php
			include('./includes/admin-sidebar.html');
		?>
		<div class="wrapper">
		<?php
			include('./includes/admin_header.html');
			require_once('./config/mysql_connect.php');

			if(isset($_POST['submitted'])) {
				

				$errors = array();
				
				if(!empty($_POST['prod_id'])) {
					$pid = $_POST['prod_id'];
				} else {
					$pid= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Product ID</font></p>';
				}
				
				if(!empty($_POST['prod_name'])) {
					$pn = mysqli_real_escape_string($dbc, $_POST['prod_name']);
				} else {
					$pn= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Product Name</font></p>';
				}
				
				if(!empty($_POST['prod_type'])) {
					$pt = $_POST['prod_type'];
				} else {
					$pt= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Product Type</font></p>';
				}
				
				if(!empty($_POST['prod_price'])) {
					$pp = $_POST['prod_price'];
				} else {
					$pp= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Product Price</font></p>';
				}
				
				/*$file = $_FILES['file'];
				$name = $file['name'];
				
				if ($_FILES['file']['error'] > 0) {
					echo "Return Code: " . $_FILES['file']['error'] . "<br />";
				} else {
					if(file_exists("uploads/" . $_FILES["file"]["name"])) {
						echo $_FILES["file"]["name"] . " already exists. ";
					} else {
						move_uploaded_file($_FILES["file"]["tmp_name"],
						"uploads/" . $_FILES["file"]["name"]);
						echo "Stored in: " . "uploads/" . $_FILES["file"]["name"];
						$path = "uploads/" . $_FILES["file"]["name"];
					}
				}*/
				
				function findexts($filename)  {  
					$filename = strtolower($filename) ;  
					$exts = preg_split("[/\\.]", $filename) ;  
					$n = count($exts)-1;  
					$exts = $exts[$n];  
					return $exts;  
				}
				
				$ext = findexts ($_FILES['uploaded']['name']) ; 
				
				$ran = rand();
				$ran2 = $ran.".";
				$target = "images/product/";
				$target = $target . $ran2.$ext;
				
				if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))  { 
					echo "The file has been uploaded as ".$ran2.$ext . ""; 
				}  else { 
					echo "Sorry, there was a problem uploading your file."; 
				}
				
				if(!empty($_POST['prod_avail'])) {
					$pa = $_POST['prod_avail'];
				} else {
					$pa= FALSE;
				}
				
				if(!empty($_POST['prod_desc'])) {
					$pd = mysqli_real_escape_string($dbc, $_POST['prod_desc']);
				} else {
					$pd= "NULL";
				}
				
				if(!empty($_POST['stock'])) {
					$s = $_POST['stock'];
				} else {
					$s= "NULL";
				}
			
				if(empty($errors)) {

					$query = "INSERT INTO products(prod_id, prod_name, prod_type, prod_price, date_added, prod_img, prod_avail, prod_desc, stock)
					 VALUES('$pid', '$pn', '$pt', '$pp', NOW(), '$target', '$pa', '$pd', '$s')";
					 $result = mysqli_query($dbc, $query);
					 if ($result) {
						 
						//	Print a message.
						echo '<h1 id="mainhead">Product Cataloging Complete</h1>
						<p>The Product has been added</p>';
						echo '<br><div class="wrapper"><p><a href="home.php">Click here</a> to return home</p><p><a href="rental_solution.php">Click here</a> to return to rental</p></div>';
						echo '
						<div class="wrapper_product">
						<br /><form action="cart.php" method="get">
							<table>
								<tr>
									<td>
										<table>
											<tr>
												<td rowspan = "7" style="vertical-align: top; padding: 50px;">
													<img src="' . $target . '" width="250" height="350"/>
												</td>
											</tr>
											<tr>
												<td>
													<h2>' . $pn . '</h2>
												</td>
											</tr>
											<tr>
												<td>
													<h1>RM ' . number_format($pp, 2) . '</h1>
												</td> 
											</tr>';
											echo'<tr style="width:20%">
												<td>
													' . nl2br($pd) . '
												</td> 
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</form></div>';
						
						mysqli_close($dbc);
						exit();
						
					} else {	// If it did not run OK.
						echo '<h1 id="mainhead">System Error</h1>
						<p class="error">The data cannot be entered due to a system error. </p>';
						echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
						exit();
					}
				} else { // Report the errors.
		
					echo '<h1 id="mainhead" style="font-family:Verdana">Error!</h1>
					<p class="error" style="font-family:Verdana">The following error(s) occured:<br />';
					foreach ($errors as $msg) { // Print each error.
						echo "$msg";
					}
					echo '</p><p>Please try again later.</p><p><br /></p>';
					
				}
			}
		?>
			
		<h2>Add Product</h2>
		<div class="article_holder">
		<form action="add_product.php" method="post" enctype="multipart/form-data">
			<p><center><table>
				<tbody>
					<tr>
						<td>Product ID</td>
						<td><input type="text" name="prod_id" name="prod_id" placeholder="Product ID" /></td>
						<td>Product Name</td>
						<td><input type="text" name="prod_name" name="prod_name" placeholder="Product Name" /></td>
					</tr>
					<tr>
						<td>Product Type</td>
						<td>
							<select name="prod_type" >
								<option value="Video Game" selected="selected">Video Game</option>
								<option value="Rental">Rental Sevice</option>
							</select>
						</td>
						<td>Product Price</td>
						<td><input type="text" name="prod_price" name="prod_price" placeholder="Product Price" /></td>
					</tr>
					<tr>
						<td>Product Image</td>
						<td><input type="file" name="uploaded"></td>
						<td>Product Availability</td>
						<td>
							<select name="prod_avail" style="width:170px">
								<option value=""></option>
								<option value="Pre Order">Pre Order</option>
								<option value="In Stock">In Stock</option>
							</select>
						</td>
					</tr>
						<td>Product Stock</td>
						<td><input type="text" name="stock" value="0"/></td>
						<td colspan="2"><font color="red">Leave empty if pre-order item.</font></td>
					</tr>
					<tr>
						<td colspan="4">
							<center><textarea rows="3" cols="80" placeholder="Product Description" name="prod_desc"></textarea></center>
						</td>
					</tr>
					<tr>
						<td colspan="7"><center><input type="submit" name="submit" value="Register" class="btn" /></center></td>
						<input type="hidden" name="submitted" value="TRUE" />
					</tr>
				</tbody>
			</table></center></p>
		</form>
		</div>
		
		<hr>
		<h2>Update Product</h2>
			<div class="article_holder">
				<form action="admin_article.php" method="post">
					<table class="table-alpha">
						<tr class="table-alpha-header"">
							<th>Product ID</th>
							<th>Name</th>
							<th width="13%">Product Type</th>
							<th>Detail</th>
							<th>Price</th>
							<th>Stock</th>
							<th>Update</th>
						</tr>
						<?php
							$sql = "SELECT prod_id, prod_name, prod_desc, prod_price, prod_type,stock FROM products ORDER BY prod_id ASC";
							$resultsql = mysqli_query($dbc, $sql);
							
							if($resultsql){
								while($row = mysqli_fetch_array($resultsql, MYSQLI_ASSOC)) {
									echo '<tr bgcolor="#ffffff"><td>' . $row['prod_id'] . '</td>';
									echo '<td><a href="product_edit.php?prod_id=' . $row['prod_id'] . '" style="text-decoration:none;">' . $row['prod_name'] . '</a></td>';
									echo '<td>' . $row['prod_type'] . '</td>';
									echo '<td>' . substr($row['prod_desc'], 0, 150) . '...</td>';
									echo '<td>RM ' . number_format($row['prod_price'], 2) . '</td>';
									echo '<td>' . $row['stock'] . '</td>';
									echo '<td><a href="product_edit.php?prod_id=' . $row['prod_id'] . '" style="text-decoration:none; color: blue; font-weight:bold; font-size:20px;">...</a></td>';
								}
								
								echo '</table><br /><br />';
							}
							mysqli_close($dbc);
						?>
				</form>
			</div>
		</div>
	</body>
</html>