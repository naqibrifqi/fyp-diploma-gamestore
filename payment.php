<!DOCTYPE php>
<?php
	session_start();
?>

<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
		<title>Order Payment</title>
		<link rel="stylesheet" type="text/css" name="includes/style.css" />
		<script>
		function myFunction() {
			alert("Receipt Accepted. Thank You!");
		}
		</script>
	</head>
	
	<body>
		<?php
			require_once('./config/mysql_connect.php');
			include('./includes/header.html');
			
			use PHPMailer\PHPMailer\PHPMailer;
			use PHPMailer\PHPMailer\SMTP;

			require './PHPMailer/src/Exception.php';
			require './PHPMailer/src/PHPMailer.php';
			require './PHPMailer/src/SMTP.php';
		?>
		<div class="wrapper">
		<?php
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//When the submit button is clicked for uploading receipt
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	require_once('./config/mysql_connect.php');
	if(isset($_POST['confirmed'])) {
		
		$mid = $_SESSION['member_id'];
		$cart = $_SESSION['cart'];
		$type = $_SESSION['type'];
		$avail = $_SESSION['prod_avail'];
		
		$errors = array();
		
		if(isset($_POST['po_id']) && $_POST['po_id']!="" && is_numeric($_POST['po_id'])){
			$pid = $_POST['po_id'];
		}else{
			$pid = FALSE;
			$errors[] = '<p><font color="red">-Please re-check your receipt reference no!</font></p>';
		}
		
		if(isset($_POST['tnc'])){
			$tnc = $_POST['tnc'];
		}else{
			$tnc = FALSE;
			$errors[] = '<p><font color="red">-You can\'t place an order if you did not accept out Terms & Condition!</font></p>';
		}
		
		/*$file = $_FILES['file'];
		$name = $file['name'];
		
		if ($_FILES['file']['error'] > 0) {
			echo "Return Code: " . $_FILES['file']['error'] . "<br />";
		} else {
			if(file_exists("payment/" . $_FILES["file"]["name"])) {
				echo $_FILES["file"]["name"] . " already exists. ";
			} else {
				move_uploaded_file($_FILES["file"]["tmp_name"],
				"payment/" . $_FILES["file"]["name"]);
				echo "Stored in: " . "payment/" . $_FILES["file"]["name"];
				$path = "payment/" . $_FILES["file"]["name"];
			}
		}*/
		if (empty($errors)) {
			
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
				echo "The file has been uploaded as ".$ran2.$ext; 
			}  else { 
				$errors[] = 'Sorry, there was a problem uploading your file.'; 
			}
			
			foreach($_SESSION['cart'] AS $cart){
				$sql = "SELECT prod_name FROM products WHERE prod_avail = 'IN STOCK' AND prod_id = '{$cart['prod_id']}'";
				$resultsql = @mysqli_query($dbc, $sql);
				
				//Update stock - qty after payment
				if($resultsql){
					$sqlupdate = "UPDATE products SET stock = stock - '{$cart['qty']}' WHERE prod_id = '{$cart['prod_id']}' AND 
					prod_avail = 'In Stock'";
					$resultupdate = @mysqli_query($dbc, $sqlupdate);
					
					if($resultupdate){
						echo '<p>Stock Updated!</p>';
					} else {	// If it did not run OK.
						echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
						<p class="error">The data cannot be entered due to a system error. (Stock)</p>';
						echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
						exit();
					}
				} else {	// If it did not run OK.
					echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
					<p class="error">The data cannot be entered due to a system error. (Select)</p>';
					echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
					exit();
				}
			}
			
			$query = "INSERT INTO preorder(po_id, po_delivery_method, po_payment_proof, po_date, type, member_id, total_payment)
			VALUES('$pid', '{$_SESSION['del']}', '$target', NOW(), '$type', '$mid', '{$_SESSION['total']}')";
			
			$result = @mysqli_query($dbc, $query);
			
			if($result){
				echo'<br /><b>New order inserted</b>';
				foreach($_SESSION['cart'] AS $cart){
					$query2 = "INSERT INTO receipt(po_id, prod_id, quantity, details)
					VALUES('$pid', '{$cart['prod_id']}', '{$cart['qty']}', 'none')";
					
					$result2 = @mysqli_query($dbc, $query2);
					if($result2){
						echo '<p>Receipt accepted</p>';
					} else {	// If it did not run OK.
						echo '<h1 id="mainhead">System Error</h1>
						<p class="error">The data cannot be entered due to a system error. (Receipt)</p>';
						echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
					}
				}
				
				$mailsql = "SELECT CONCAT(member_fname,' ',member_lname) AS member_name, member_email FROM member WHERE member_id='" . $_SESSION['member_id'] . "'";
				$resultmail = mysqli_query($dbc, $mailsql);
				if($resultmail){
					$row = mysqli_fetch_array($resultmail);
					$message = "
					Hello " . $row['member_name'] ."
					Thank you for your payment for order " . $pid . ". Your payment will be confirmed during our business hours.
											
					In-stock orders will be shipped within 2 business days. Orders that have been paid in advance will be 
					shipped on or after the day of release. A shipping notification mail will be sent within 24 hours of order shipment 
					and your order may be marked as \"Shipped\" on our website before you receive the notification mail.
											
					IMPORTANT: Please be advised that paid orders cannot be returned. All sales are final.
											
					Order Number: " . $pid . "
											
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
					$mail->Subject = 'Kitamen Payment';
					//$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
					$mail->AltBody = $message;
					$mail->Body = $message;
					$mail->SMTPDebug = false;

					if (!$mail->send()) {
						echo 'Mailer Error: '. $mail->ErrorInfo;
					} else {
						echo '<script language="javascript">';
						echo 'alert("Payment Success. We\'ll return to you.")';
						echo '</script>';
						//Section 2: IMAP
						//Uncomment these to save your message in the 'Sent Mail' folder.
						#if (save_mail($mail)) {
						#    echo "Message saved!";
						#}
					}
					//mail($row['member_email'],"KITAMEN Confirmation Receipt", $message);
				}
			} else {	// If it did not run OK.
				echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
				<p class="error">The data cannot be entered due to a system error. </p>';
				echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
				exit();
			}
			unset($_SESSION['cart']);
			echo '<p>A confirmation email has been sent to your email.</p>';
			echo('<br />Payment uploaded! We will confirm this.<br><a href="home.php">Click here</a> to return home<br><br>');
			exit();
		} else { // Report the errors.
		
			echo '<div class="wrapper"><h1 id="mainhead">Error!</h1>
			<p class="error">The following error(s) occured:<br />';
			foreach ($errors as $msg) { // Print each error.
				echo "$msg";
			}
			echo '</p><p>Please try again later.</p><p><br /></p></div>';
		}
	}
?>
			<div id="cart"><br />
				<div class="nav">
					<a href = "myaccount.php">My Account</a> > <a href="deliveryinformation.php">Delivery Information</a> > <a href="payment.php">Payment</a>
				</div><hr>
				<p><b>Payment</b></p>
					<p>Thank you for allowing us to make your event cooler :) <br />Please include the reference number of your deposits with the image. <br />We will
				reply to you by phone or email after this. <br />For additional details, please tell us in advance inside the details area. <br />Best Regards.</p>
				<p>CIMB: KITAMEN Studios Bhd 7049173313</p>
				<p>Maybank: KITAMEN Studios Bhd 1064329058865</p>
				<p>Bank Islam: KITAMEN Studios Bhd 10562883544219</p><br>
				<p><font color="red">Please be inform that for COD, you need to pay deposits to us first.</font></p><hr>
				<?php
					echo $_SESSION['member_email'];
					if(isset($_POST['delivery'])){
						$_SESSION['del'] = $_POST['delivery'];
						echo 'Delivery Method: ' . $_SESSION['del'];
					}
				?>
				<form action="payment.php" method="post" enctype="multipart/form-data">
					<table>
						<tr>
							<td>Reference No:</td>
							<td><input type="text" name="po_id" placeholder="Receipt Reference" /></td>
						</tr>
						<tr>
							<td><p>Upload your receipt here:</p></td>
							<td><input type="file" name="uploaded"></td>
						</tr>
					</table>
					
					<input type="checkbox" name="tnc" value="chkyes">I am now hereby agree with the <a href="tnc.php" target='_blank'>Terms & Condition</a> of 
					KITAMEN on my transaction<br /><br />
					
					<p><input type="submit" name="submit" value="Proceed" class="button" />
					<input type="hidden" name="confirmed" value="TRUE" /></p>
				</form>
			</div><br><br><br>
			
		</div>
	</body>
</html>
