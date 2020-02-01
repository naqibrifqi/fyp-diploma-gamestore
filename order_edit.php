<?php
	session_start();
	if(isset($_SESSION['member_id']) == ""){
		header('home.php');
	}
?>
<html>
	<head>
		<title>Order Information</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css">
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
			$mid = $_SESSION['member_id'];
			
			if(isset($_GET['po_id'])){
				$pid = $_GET['po_id'];
			} else{
				$pid = FALSE;
			}
			
			if(isset($_GET['prod_id'])){
				$po_id = $_GET['prod_id'];
			} else{
				$po_id = FALSE;
			}			
			
			echo '<br><div class="wrapper">';
			echo '<div class="nav">
						<a href = "myaccount_edit.php">My Account</a> > <a href="order_edit.php?po_id=' . $pid .'&prod_id=' . $po_id .'">Order Item</a>
					</div><hr>';
			
			if(isset($_POST['submitted'])){
				$errors = array();
				
				if (!empty($_POST['details'])) {	
					$d = mysqli_real_escape_string($dbc,$_POST['details']);
				} else { // If the first name field is empty.
					$d = FALSE;
					$errors[] = '<p><font color="red">-Please enter why you refunded this item!</font></p>';
				}
				
				if (empty($errors)) {
					$sql = "UPDATE receipt SET details = '" . $d . "'
					WHERE po_id = '" . $_SESSION['po_id'] . "' && prod_id = '" . $_SESSION['prod_id'] . "'";
					
					$ressql = mysqli_query($dbc,$dbc,$sql);
					if($ressql){
						echo '<br /><h1 id="mainhead">Thank You For your feedback!</h1>
						<p>We will review your refunds process, and our team will contact you later.</p>';
						echo '<a href="myaccount_edit.php">Back to my Account</a>';
					}
				} else { // Report the errors.
		
					echo '<div class="wrapper"><h1 id="mainhead">Error!</h1>
					<p class="error">The following error(s) occured:<br />';
					foreach ($errors as $msg) { // Print each error.
						echo "$msg";
					}
					echo '</p><p>Please try again later.</p><p><br /></p></div>';
			
				}
			}else{
			
			$query = "SELECT r.po_id, r.prod_id, r.quantity, p.prod_name, r.progress, r.details
			FROM receipt r
			JOIN products p
			ON p.prod_id = r.prod_id
			WHERE r.po_id = '" . $pid . "'AND r.prod_id = '" . $po_id ."'";
			
			$result = mysqli_query($dbc, $query);
			if($result){
				echo'<table class="dashboard-table">
					<tr>
						<th>Order ID</th>
						<th>Product ID</th>
						<th>Product Name</th>
						<th>Quantity</th>
						<th>Progress</th>
						<th>Details</th>
					</tr>';
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						echo '
						<tr><td>' . $row['po_id'] .'</td>
						<td>' . $row['prod_id'] .'</td>
						<td>' . $row['prod_name'] .'</td>
						<td>' . $row['quantity'] .'x</td>
						<td>' . $row['progress'] .'</td>
						<td>' . $row['details'] .'</td></tr>';
						$_SESSION['progress'] = $row['progress'];
						$_SESSION['po_id'] = $row['po_id'];
						$_SESSION['prod_id'] = $row['prod_id'];
						$_SESSION['details'] = $row['details'];
					}
				echo '</table>';
			} else {	// If it did not run OK.
				echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
				<p class="error">The data cannot be entered due to a system error. </p>';
				echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
				exit();
			}
			if($_SESSION['progress'] == "Shipped" && $_SESSION['details'] == 'none'){
		?>
			<hr>
			<h2>Return Item</h2>
			<form action="order_edit.php" method="post"> 
			<p><b>Returns</b></p>
			<p>You have 7 calendar days to return an item from the date you received it.</p>
			<p>To be eligible for a return, your item must be unused and in the same condition that you received it.</p>
			<p>Your item must be in the original packaging.</p>
			<p>Your item needs to have the receipt or proof of purchase.</p><br>
			<p><textarea name="details" placeholder="Tell us why you decided to return this product(s)." rows="9" cols="70"></textarea></p><br>
			<input type="submit" name="submit" value="Submit" />
			<input type="hidden" name="submitted" value="TRUE" />
			<?php
				}else if($_SESSION['progress'] == 'Not Shipped'){
					
				}else{
					echo '<h1 id="mainhead">Refund in progress</h1>
					<p class="error">You have marked this item for refund. Please contact Man 016-4446716 if you have changed your mind.</p>';
				}
			}
			?>
		</div>
	</body>
</html>