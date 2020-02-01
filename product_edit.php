<html>
	<head>
		<title>Admin Update Products</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include('./includes/admin_header.html');
			
			if(isset($_GET['prod_id'])){
				$pid = $_GET['prod_id'];
			}else{
				header('admin_article.php');
			}
			
			if(isset($_POST['delete'])){
				if(!empty($_POST['prod_id'])) {
					$pid = $_POST['prod_id'];
				} else {
					$pid= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Product ID</font></p>';
				}
				
				$querydel = "DELETE FROM products WHERE prod_id = '" . $pid . "'";
				$resultdel = mysqli_query($dbc,$dbc,$querydel);
				
				if($resultdel){
					echo 'Product with ID: ' . $pid . ' has been deleted.';
				} else{
					echo 'Error deleting occured. ' . mysqli_error($dbc);
				}
			}
			
			if(isset($_POST['submitted'])){
					$errors = array();
					
					if(!empty($_POST['prod_id'])) {
						$pid = $_POST['prod_id'];
					} else {
						$pid= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the Product ID</font></p>';
					}
					
					if(!empty($_POST['prod_name'])) {
						$pn = $_POST['prod_name'];
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
					
					if(!empty($_POST['prod_desc'])) {
						$pd = mysqli_real_escape_string($dbc,$_POST['prod_desc']);
					} else {
						$pd= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the product description.</font></p>';
					}
					
					if(!empty($_POST['prod_avail'])) {
						$pa = mysqli_real_escape_string($dbc,$_POST['prod_avail']);
					} else {
						$pa= NULL;
					}
					
					if(!empty($_POST['stock'])) {
						$s = mysqli_real_escape_string($dbc,$_POST['stock']);
					} else {
						$s= NULL;
					}
					
					$file = $_FILES['uploaded'];
					$name = $file['name'];
					
					if($_FILES['uploaded']['size'] == 0){
						echo '<br />Image retained.<br />';
						
						$query2 = 'UPDATE products
						SET prod_name = "' . $pn .'", prod_name= "' . $pn .'", prod_desc= "' . $pd . '", prod_type="' . $pt . '", prod_avail="' . $pa . '", stock="' . $s . '"
						WHERE prod_id = "' . $pid . '"';
						$result2 = @mysqli_query($dbc,$dbc,$query2);
						
						if(!$result2){
							echo('Error updating product: ' . mysqli_error($dbc));
							exit();
						}else{
							echo('<br />Success Updating!<br><a href="add_product.php">Click here</a> to add more product.<br><a href="add_product.php">Click 
							here</a> to edit products.<br><a href="admindash.php">Click here</a> to return to the main admin page.');
							exit();							
						}
					} else{
						
						
						/*if ($_FILES['file']['error'] > 0) {
						echo "Return Code: " . $_FILES['file']['error'] . "<br />";
						} else {
							if(file_exists("uploads/" . $_FILES["file"]["name"])) {
								echo $_FILES["file"]["name"] . " already exists. ";
							} else {
								move_uploaded_file($_FILES["file"]["tmp_name"],
								"images/story/" . $_FILES["file"]["name"]);
								echo "Stored in: " . "images/story/" . $_FILES["file"]["name"];
								$path = "images/story/" . $_FILES["file"]["name"];
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
						$target = "payment/";
						$target = $target . $ran2.$ext;
						
						if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))  { 
							echo "<div class=\"wrapper\">The file has been uploaded as ".$ran2.$ext . "</div>"; 
						}  else { 
							echo "<div class=\"wrapper\">Sorry, there was a problem uploading your file.</div>"; 
						}
						
						$query2 = 'UPDATE products
						SET prod_id = "' . $pid .'", prod_name= "' . $pn .'", prod_image= "' . $path . '", prod_desc= "' . $pd . '", prod_type="' . $pt . '", prod_avail="' . $pa . '"
						WHERE prod_id = "' . $pid . '"';
						$result2 = @mysqli_query($dbc,$dbc,$query2);
						
						if(!$result2){
							echo('Error updating product: ' . mysqli_error($dbc));
						}else{
							echo('<br />Success Updating!<br><a href="add_product.php">Click here</a> to add more product.<br><a href="add_product.php">Click 
							here</a> to edit news.<br><a href="admindash.php">Click here</a> to return to the main admin page.');
							header('admin_article.php');
						}
					}
			}
		?>
		<div class="wrapper">
			<div class="article_holder">
				<form action="product_edit.php" method="post" enctype="multipart/form-data">
					<?php
					
						if(isset($_GET['prod_id'])){
							$pid = $_GET['prod_id'];
						}else{
							header('admin_article.php');
						}
						
						$query = "SELECT prod_id, prod_name, prod_img, prod_desc, prod_type, prod_avail, prod_price, stock FROM products WHERE prod_id = '" . $pid . "'";
						$result = mysqli_query($dbc, $query);
						if($result){
							echo '<table>';
							while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
								
								echo '<tr><td>Product ID</td><td><input type="text" size="50" name="prod_id" value="' . $row['prod_id'] .'" readonly /></td></tr>';
								echo '<tr><td>Name</td><td><input type="text" size="50" name="prod_name" value="' . $row['prod_name'] .'"/></td></tr>';
								echo '<tr><td>Type</td><td><input type="text" size="50" name="prod_type" value="' . $row['prod_type'] .'"/></td></tr>';
								echo '<tr><td>Availability</td><td><input type="text" size="50" name="prod_avail" value="' . $row['prod_avail'] .'"/></td></tr>';
								echo '<tr><td>Stock</td><td><input type="text" size="50" name="stock" value="' . $row['stock'] .'"/></td></tr>';
								echo '<tr><td>Image</td><td><input type="file" name="uploaded"</td></tr>';
								echo '<tr><td>Description</td><td><textarea rows="20" cols="120" name="prod_desc">' . $row['prod_desc'] . '</textarea></td></tr>';
								
								echo '<div class="wrapper_product">
								<br />
									<table>
										<tr>
											<td>
												<table>
													<tr>
														<td rowspan = "6" style="vertical-align: top; padding: 50px;">
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
													</tr>
													<tr> 
													</tr>';
													echo'<tr style="width:20%">
														<td>
															' . nl2br($row['prod_desc']) . '
														</td> 
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</div>';
							}
							echo '</table>';
						}
					?>
					<center><input type="submit" name="submit" value="Update" class="btn" />
					<input type="submit" name="delete" class="btn" value="Delete This Item?"  onclick="return confirm('Are you sure you want to delete this item?');" />
					<input type="hidden" name="submitted" value="TRUE" /></center><br /><br />
				</form>
			</div>
		</div>
	</body>
</html>