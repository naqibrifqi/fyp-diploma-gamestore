<!DOCTYPE php>
<?php
	session_start();
	if(isset($_SESSION['member_id']) == ""){
		header('home.php');
	}
?>
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
		<title>My Account</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
			$mid = $_SESSION['member_id'];

		?>
		
		<div class="wrapper">
			<div class="content-wrapper">
				<h2>My Account</h2>
				
				<p>For <font color="red">refunds</font>, you may click the <font color="red">Order ID</font> to submit refund.</p>
				<p>Only <font color="red">shipped item</font> are allowed to submit for refund process.</p>
				<hr>
				<table>
					<tr>
						<td style="border-right: grey solid;"><h3>Order History</h3></td>
						<td style="padding-left:10px;"><h3>Account Information</h3></td>
					<tr><td style="border-right: grey solid;">
						<table class="dashboard-table">
						
							<?php
								$query = "SELECT preorder.po_id, preorder.po_delivery_method, preorder.po_date, receipt.progress, receipt.prod_id, preorder.total_payment
								FROM preorder
								INNER JOIN receipt
								ON receipt.po_id = preorder.po_id
								WHERE preorder.member_id = '{$_SESSION['member_id']}'";
								
								$result = mysqli_query($dbc, $query);
								
								if($result){
									while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
										echo '<tr><td><a href="order_edit.php?po_id=' . $row['po_id'] . '&prod_id=' . $row['prod_id'] . '">' . $row['po_id'] . '</a> <b></b></td>';
										$_GET['po_id'] = $row['po_id'];
										echo '<td>' . $row['po_delivery_method'] . ' <b></b> </td>';
										echo '<td>' . $row['po_date'] . ' <b></b> </td>';
										echo '<td>' . $row['progress'] . ' <b></b> </td>';
										echo '<td>RM ' . number_format($row['total_payment'], 2) . ' <b></b> </td></tr>';
									}
								} else {	// If it did not run OK.
								echo '<h1 id="mainhead">System Error</h1>
								<p class="error">The data cannot be entered due to a system error. </p>';
								echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
								exit();
							}
							?>
						</table></td>
						
						<td style="padding-left:10px;"><table>
							<?php
							
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
											echo 'Your shipping information has been updated!<br />';
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
								
								$sql = "SELECT member_lname 
								FROM member 
								WHERE member_id = '" . $mid . "' AND address IS NULL";
								
								$resultsql = @mysqli_query($dbc, $sql);
								
								if(mysqli_fetch_array($resultsql) == true){
									echo '<form action="myaccount_edit.php" method="post">';
									echo '<b>Add your shipping Address</b>';
									echo '<table class="address_reg"><tr>
										<td>Address:</td><td><textarea rows="4" cols="30" name="address"></textarea></td></tr>
										<tr><td>Postal:</td><td><input type="text" name="postal" size="35"/></td></tr>
									</tr>
									<tr><td>City:</td><td><input type="text" name="city" size="35"/></td></tr>
										<tr><td>State:</td><td><input type="text" name="state" size="35"/></td></tr>
									<tr><td>Country :</td><td><font color="red">Malaysia</font></td></tr>';
									echo'<tr><td><button type="submit" class="button">Submit Address Information</button></td>
									<input type="hidden" name="add_submitted" value="TRUE" /></tr></table>';
								}else{
									$query3 = "SELECT member_fname, member_lname, member_email, address, city, country, state, postal, member_phone
									FROM member
									WHERE member_id = '" . $mid . "'";
									$result3 = @mysqli_query($dbc, $query3);
									if($result3){
										while($row = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
											echo "<br />" . ucfirst($row['member_fname']) . " " . ucfirst($row['member_lname']);
											echo "<br />" . $row['address'] . ", <br />" . $row['postal'] . " " . $row['city'] . " <br />" . $row['state'];
											echo "<br />" . $row['country'];
											echo "<br />" . $row['member_phone'];
											echo '<br />
											<form action="myaccount_edit.php" method="post">
												<input type="submit" name="edit" value="Edit" />
												<input type="hidden" name="edit_submitted" value="TRUE" />
											</form>
											<form action="password_edit.php" method="post">
												<input type="submit" name="edit" value="Change Password" />
												<input type="hidden" name="edit_password" value="TRUE" />
											</form>';
										}
									} 	else {	// If it did not run OK.
										echo '<h1 id="mainhead">System Error</h1>
										<p class="error">The data cannot be entered due to a system error. </p>';
										echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
										exit();
									}
								}
								
								if(isset($_POST['edit_submitted'])){
									echo '<form action="myaccount_edit.php" method="post">';
									echo '<br /><br /><b>Update your shipping Address</b>';
									echo '<table class="address_reg"><tr>
										<td>Address:</td><td><textarea rows="4" cols="30" name="address"></textarea></td></tr>
										<tr><td>Postal:</td><td><input type="text" name="postal" size="35"/></td></tr>
									</tr>
									<tr><td>City:</td><td><input type="text" name="city" size="35"/></td></tr>
										<tr><td>State:</td><td><input type="text" name="state" size="35"/></td></tr>
									<tr><td>Country :</td><td><font color="red">Malaysia</font></td></tr>';
									echo'<tr><td><button type="submit" class="button">Update Address Information</button></td>
									<input type="hidden" name="add_submitted" value="TRUE" /></tr></table>';
								}
							?>
						</table></td>
					</tr>
				</table>
			</div>	
		</div>
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>