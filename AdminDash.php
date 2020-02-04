<!DOCTYPES html>
<?php
	session_start();
	if($_SESSION['admin'] == 0) {
		header("location:login.php");
	}
?>
<html>
	<head>
		<title>Admin Dashboard</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body class="admin-body">
		<?php
			include('./includes/admin-sidebar.html');
		?>
		<div class="wrapper">
			<?php
				require_once ('config/mysql_connect.php');
				include('./includes/admin_header.html');
				
				use PHPMailer\PHPMailer\PHPMailer;
				use PHPMailer\PHPMailer\SMTP;

				require './PHPMailer/src/Exception.php';
				require './PHPMailer/src/PHPMailer.php';
				require './PHPMailer/src/SMTP.php';
			?>
			
			<h1>Admin Dashboard</h1>
			<?php
				
				/////////////////////////////////////////////////////////////////////////////////////////
				///////Update from Not Shipped state to Shipped and vice versa           /////////
				/////////////////////////////////////////////////////////////////////////////////////////
				
				if(isset($_GET['po_id']) && $_GET['progress'] == "Not Shipped"){
					$sql = "UPDATE receipt
					SET progress = 'Shipped'
					WHERE po_id = '" . $_GET['po_id'] . "' && prod_id = '" . $_GET['prod_id'] . "'";
					
					$res = mysqli_query($dbc, $sql);
					
					if($res){
						$mailsql = "SELECT CONCAT(m.member_fname,' ',m.member_lname) AS member_name, m.member_email 
						FROM receipt r
						JOIN preorder p
						ON r.po_id = p.po_id
						JOIN member m
						ON m.member_id = p.member_id
						WHERE r.po_id='" . $_GET['po_id'] . "'";
						$resultmail = mysqli_query($dbc,$mailsql);
						if($resultmail){
							$row = mysqli_fetch_array($resultmail);
							$message = "
Hello " . $row['member_name'] ."
Thank you for your payment for order " . $_GET['po_id'] . ".
We have confirmed your payment and shipped the following order to you.
			
order number: " . $_GET['po_id'] . "
											
Please understand that no changes can be made after an order has been shipped. Our
store has no control over the delivery of parcels. Please contact your local post office if you have any
inquiries regarding delivery.

Thank you for your purchase and we look forward to doing business with you again soon.
									
Thank you for shopping at KITAMEN!
";
							$mail = new PHPMailer;
							$mail->isSMTP();
							$mail->SMTPDebug = SMTP::DEBUG_SERVER;

							$mail->Host = 'smtp.gmail.com';

							$mail->Port = 587;

							$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
							$mail->SMTPAuth = true;
							$mail->Username = 'kitamenstudioent@gmail.com';
							$mail->Password = 'akulapor';
							$mail->setFrom('kitamenstudioent@gmail.com', 'Kitamen');
							$mail->addReplyTo('kitamenstudioent@gmail.com', 'Kitamen');
							$mail->addAddress($row['member_email'], $row['member_name']);
							$mail->Subject = 'Kitamen Receipt';
							//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
							$mail->AltBody = $message;
							$mail->Body = $message;
							$mail->SMTPDebug = false;

							if (!$mail->send()) {
								echo 'Mailer Error: '. $mail->ErrorInfo;
							} else {
								echo '<script language="javascript">';
								echo 'alert("Notification E-mail sent!")';
								echo '</script>';
								//Section 2: IMAP
								//Uncomment these to save your message in the 'Sent Mail' folder.
								#if (save_mail($mail)) {
								#    echo "Message saved!";
								#}
							}


							//mail($row['member_email'],"KITAMEN Shipping Notification", $message);
						}else{
							echo mysqli_error($dbc);
						}
						//header('admindash.php');
					}
				} else if(isset($_GET['po_id']) && $_GET['progress'] == "Shipped"){
					$sql = "UPDATE receipt
					SET progress = 'Not Shipped'
					WHERE po_id = '" . $_GET['po_id'] . "' && prod_id = '" . $_GET['prod_id'] . "'";
					
					$res = mysqli_query($dbc,$dbc,$sql);
					
					if($res){
						header('admindash.php');
					}
				}
				
				$query = "select preorder.po_id, products.prod_name, preorder.po_delivery_method, preorder.po_payment_proof, preorder.po_date, 
				receipt.progress, preorder.total_payment, receipt.prod_id, preorder.member_id, products.prod_avail, receipt.details, preorder.total_payment
				from preorder
				inner join receipt
				on preorder.po_id = receipt.po_id
				inner join products
				on products.prod_id = receipt.prod_id
				ORDER BY preorder.po_date DESC";
				
				$result = @mysqli_query($dbc, $query);
				
				if($result) {
					echo '<table class="dashboard-table table-alpha" ><caption>All Reservations</caption><tr class="table-alpha-header">';
					echo '<th>Order ID</th>';
					echo '<th>Product Name</th>';
					echo '<th>Delivery Method</th>';
					// echo '<th>Payment Proof</th>';
					echo'<th>Date Ordered</th>';
					echo'<th>Progress</th>';
					// echo'<th>Member ID</th>';
					// echo'<th>Availability</th>';
					echo'<th>Total Payment</th></tr>';
					// echo'<th>Details</th></tr>';
					
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						echo '<tr bgcolor="#ffffff"><td><a style="color:#727cf5; font-weight:bold;" href="order_view.php?po_id=' . $row['po_id'] . '">' . $row['po_id'] . '</a></td>';
						echo '<td>' . $row['prod_name'] . '</td>';
						echo '<td>' . $row['po_delivery_method'] . '</td>';
						// echo '<td>' . $row['po_payment_proof'] . '</td>';
						$date = date_create_from_format('Y-m-d H:i:s', $row['po_date']);
						echo '<td>' . $date->format('d M Y') . '</td>';
						if($row['progress'] == "Shipped")
							echo '<td><span style="color:#b3b6dc; font-weight:bold;">' . $row['progress'] . '</span></td>';
						else if($row['progress'] == "Not Shipped")
							echo '<td><a style="color:#727cf5; font-weight:bold;" onclick=" return confirm(\'Complete shipping process for this item?\');" href="admindash.php?po_id=' . $row['po_id'] . '&progress=' . $row['progress'] . '&prod_id=' . $row['prod_id'] . '">' . $row['progress'] . '</a></td>';
						// echo '<td>' . $row['member_id'] . '</td>';
						// echo '<td>' . $row['prod_avail'] . '</td>';
						echo '<td>RM ' . number_format($row['total_payment'], 2) . '</td></tr>';
						// echo '<td>' . $row['details'] . '</td></tr>';
					}
					echo '</table>';
				} else {	// If it did not run OK.
					echo '<h1 id="mainhead">System Error</h1>
					<p class="error">The data cannot be entered due to a system error. </p>';
					echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
					exit();
				}
				
				$query2 = "select rent_id, rent_depo, details, member_id, datestamp 
				from rental";
				
				$result2 = @mysqli_query($dbc,$dbc,$query2);
				
				if($result2) {
					echo '<br /><center><table border="1" class="dashboard-table"><caption>Rental Reservations</caption><tr>';
					echo '<th>Rental ID</th>';
					echo '<th>Deposit Proof</th>';
					echo '<th>Specific Details</th>';
					echo'<th>Member ID</th>';
					echo'<th>Date Stamp</th>';
					
					while($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
						echo '<tr><td><a href="rental_view.php?rent_id=' . $row2['rent_id'] . '">' . $row2['rent_id'] . '</a></td>';
						echo '<td>' . $row2['rent_depo'] . '</td>';
						echo '<td>' . $row2['details'] . '</td>';
						echo '<td>' . $row2['member_id'] . '</td>';
						echo '<td>' . $row2['datestamp'] . '</td></tr>';
					}
					echo '</table></center><br><br>';
				}
			?>
		</div>
	</body>

</html>