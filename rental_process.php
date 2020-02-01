<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Products Catalogue</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<script>
		function receiptAccept() {
			alert("Receipt Accepted. Thank You!");
		}
		</script>
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
			
			if(isset($_SESSION['prod_id'])){
				$prod = $_SESSION['prod_id'];
			}else{
				$prod = FALSE;
			}
			
			if(isset($_POST['submitted'])){
				
				$errors = array();
				
				$mid = $_SESSION['member_id'];
				
				if(!empty($_POST['rent_id'])) {
					$rid = mysqli_real_escape_string($dbc, $_POST['rent_id']);
				} else {
					$rid= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Rental ID</font></p>';
				}
				
				if(!empty($_POST['details'])) {
					$d = mysqli_real_escape_string($dbc, $_POST['details']);
				} else {
					$d= NULL;
				}
				
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
				$target = "payment/rental/";
				$target = $target . $ran2.$ext;
				
				if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))  { 
					echo "<br><div class=\"wrapper\">The file has been uploaded as ".$ran2.$ext . "</div>"; 
				}  else { 
					echo "<br><div class=\"wrapper\">Sorry, there was a problem uploading your file.</div>"; 
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
						echo "<div class='wrapper'>Stored in: " . "payment/" . $_FILES["file"]["name"] . "</div>";
						$path = mysqli_real_escape_string($dbc,"payment/" . $_FILES["file"]["name"]);
					}
				}*/
				
				$paysql = "INSERT INTO rental(rent_id, rent_depo, details, member_id, datestamp, prod_id)
				VALUES('$rid', '$target', '$d', '$mid', NOW(), '$prod')";
				
				$payresult = mysqli_query($dbc, $paysql);
				if($payresult){
					echo '<script type="text/javascript">'
					, 'receiptAccept();'
					, '</script>'
					;
					$mailsql = "SELECT CONCAT(member_fname,' ',member_lname) AS member_name, member_email FROM member WHERE member_id='" . $_SESSION['member_id'] . "'";
					$resultmail = mysqli_query($dbc, $mailsql);
					if($resultmail){
						$row = mysqli_fetch_array($resultmail);
						$message = "
		Hello " . $row['member_name'] ."
		Thank you for your deposit payment for rental order " . $rid . ". Your payment will be confirmed during our business hours.
								
		Our staff will contact you as soon as possible( if working days ) to confirm your order.
								
		IMPORTANT: Please be advised that paid orders cannot be returned. All sales are final.
								
		Order Number: " . $rid . "
								
		Thank you for letting KITAMEN make your day better!
		";
						mail($row['member_email'],"KITAMEN Rental Payment Received", $message);
					}
				} else{
					echo 'Error: Unable to Insert new receipt. Please try again later.';
					echo '<h1 id="mainhead">System Error</h1>
					<p class="error">The data cannot be entered due to a system error. </p>';
					echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
					exit();
				}
				echo '<br><div class="wrapper">Rental order accepted. We will confirm your order shortly.</div>';
				echo '<br><div class="wrapper"><p><a href="home.php">Click here</a> to return home</p><p><a href="rental_solution.php">Click here</a> to return to rental</p></div>';
				exit();
			}
			
			echo '<div class="wrapper">';
			if(isset($_SESSION['member_id']) && $_SESSION['member_id'] != ""){
				$query = "SELECT member_fname, member_lname, member_email, member_phone FROM member WHERE member_id ='" . $_SESSION['member_id'] . "'";
				$result = mysqli_query($dbc, $query);
			
				if($result){
					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$fn = $row['member_fname'];
					$ln = $row['member_lname'];
					$e = $row['member_email'];
					$p = $row['member_phone'];
				}
			
		?>
		<br />
			<div class="wrapper">
			<div class="nav">
				<a href = "rental_solution.php">Rentals</a> > <a href="rental_page.php?prod_id=<?php echo $_SESSION['prod_id']?>">Product Details</a> > <a href="rental_process.php">Payment</a>
			</div></div><hr>
			
			<p><h3>Thank you for allowing us to make your event cooler :) <br />Please include the reference number of your deposits with the image. <br />We will
			reply to you by phone or email after this. <br />For additional details, please tell us in advance inside the details area. <br />Best Regards.</p>
			<p>CIMB: KITAMEN Studios Bhd 7049173313</p>
			<p>Maybank: KITAMEN Studios Bhd 1064329058865</p>
			<p>Bank Islam: KITAMEN Studios Bhd 10562883544219</p></h3>
			<form action="rental_process.php" method="post" enctype="multipart/form-data">
				<table class="article_holder">
					<tr>
						<td>Product ID</td><td><?php echo $prod; ?></td>
					</tr>
					<tr>
						<td>Full Name:</td>
						<td><?php echo $fn . " " . $ln; ?></td>
						<td>Reference No.</td>
						<td><input type="text" name="rent_id" placeholder="Reference No." /></td>
					</tr>
					<tr>
						<td>Phone Number:</td>
						<td><?php echo $p ?></td>
						<td>Deposit</td>
						<td><input type="file" name="uploaded"></td>
					</tr>
					<tr>
						<td>E-mail:</td>
						<td><?php echo $e ?></td>
						<td colspan="2"><font color="red"><p>Deposit Rate: </P><p>RM 500 package: RM 200 deposit</p><p>RM 1200 package: RM 500 deposit</p><p>RM 35 package: Full deposit</p></font></td>
					</tr>
					<tr>
						<td>Details</td>
						<td><textarea rows="20" cols="70" name="details" placeholder="Tell us what in details about your order here."></textarea></td>
					</tr>
				</table>
				<input type="submit" name="submit" value="Submit" class="btn" />
				<input type="hidden" name="submitted" value="TRUE" />
			</form>
		</div>
		
		<?php
			} else{
				echo '<br /><p>Only registered member are authorised to request rental.</p>
				<p>Why not be a <a href="register.php">member</a>?</p>
				Already a member? Login <a href="login.php">here</a>!';
			}
		?>
	</body>
</html>