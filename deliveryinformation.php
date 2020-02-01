<!DOCTYPE php>
<?php
	session_start();
?>
<html>
	<head>
		<title>Delivery Information</title>
		<link rel="stylesheet" type="text/css" href="../includes/style.css" /> 
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
			<?php
				require_once('./config/mysql_connect.php');
				include('./includes/header.html');
				echo '<div class="wrapper">';
				$mid = $_SESSION['member_id'];
				
				if(isset($_POST['add_submitted'])) {
					$mid = $_SESSION['member_id'];
					
					$errors = array();
					
					if(!empty($_POST['address'])) {
						$add = $_POST['address'];
					} else {
						$add= FALSE;
						$errors[] = '<p><font color="red">-Please enter your address</font></p>';
					}
					
					if(!empty($_POST['city'])) {
						$city = $_POST['city'];
					} else {
						$city= FALSE;
						$errors[] = '<p><font color="red">-Please enter your city</font></p>';
					}
					
					if(!empty($_POST['state'])) {
						$state = $_POST['state'];
					} else {
						$state= FALSE;
						$errors[] = '<p><font color="red">-Please enter your state</font></p>';
					}
					
					if(!empty($_POST['postal'])) {
						$postal = $_POST['postal'];
					} else {
						$postal= FALSE;
						$errors[] = '<p><font color="red">-Please enter your postal code</font></p>';
					}
					
					if(empty($errors)){
						$sqladd = "UPDATE member
						SET address = '$add', city = '$city', country = 'Malaysia', state = '$state', postal = '$postal'
						WHERE member_id = '" . $mid . "'";
						
						$resultadd = @mysqli_query($dbc, $sqladd);
						if($resultadd){
							echo 'Your shipping information has been added!';
							echo '<a href="deliveryinformation.php>Back</a>"';
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
			
			<div id="cart"><br />
				<div class="nav">
					<a href = "myaccount.php">My Account</a> > Shipping Method > Payment
				</div><br><hr>
				
					<?php
					//Display the name and the saved shipping address of the member, if any
						if(isset($_SESSION['member_id']) && $_SESSION['member_id'] != ""){
							$sql = "SELECT member_lname 
							FROM member 
							WHERE member_id = '" . $mid . "' AND address IS NULL";
							
							$resultsql = @mysqli_query($dbc, $sql);
							
							if(mysqli_fetch_array($resultsql) == true){
								echo '<br /><b>Shipping Information</b>';
								echo '<form action="deliveryinformation.php" method="post">';
								echo '<table class="address_reg"><tr>
								<td>Address: <textarea rows="4" cols="50" name="address"></textarea></td><td>Postal: <input type="text" name="postal" /></td></tr>
								<tr><td>City: <input type="text" name="city" /></td><td>State: <input type="text" name="state" /></td></tr>
								<tr><td>Country : <font color="red">Malaysia</font></td></tr>';
								echo'<tr><td><button type="submit" class="button">Submit Address Information</button></td>
								<input type="hidden" name="add_submitted" value="TRUE" /></tr></table>';
							}else{
								echo '<form action="payment.php" method="post" enctype="multipart/form-data">';
								echo '<h2>Shipping address</h2>';
								$query3 = "SELECT member_fname, member_lname, member_email, address, city, country, state, postal, member_phone
								FROM member
								WHERE member_id = '" . $mid . "'";
								$result3 = @mysqli_query($dbc, $query3);
								if($result3){
									while($row = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
										echo "<br />" . $row['member_fname'] . " " . $row['member_lname'];
										echo "<br />" . $row['address'] . ", <br />" . $row['postal'] . " " . $row['city'] . " <br />" . $row['state'];
										echo "<br />" . $row['country'];
										echo "<br />" . $row['member_phone'];
									}
									
									?>
									<p>Delivery Method:
									<select name="delivery">
										<option value="Self Pickup">Self Pickup</option>
										<option value="Postage">Postage</option>
										<option value="COD">COD</option>
									</select></p>
									<p><button type="submit" class="button">Continue to shipping method</button></p><br />
									<input type="hidden" name="submitted" value="TRUE" />
								</form>
									<?php
								} 	else {	// If it did not run OK.
									echo '<h1 id="mainhead">System Error</h1>
									<p class="error">The data cannot be entered due to a system error. </p>';
									echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
									exit();
								}
							} 
						}else{
							echo 'Why not make an account. <a href="register.php">Join</a> us now!';
						}
					?>
			</div>
		</div>
	</body>
</html>