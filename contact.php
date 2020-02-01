<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Contact - KITAMEN</title>
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
			<h2>CONTACT US</h2>
			<hr>
			<p>Lets Talk or Come and Play</p>
			<p>also visit us at</p>
			<p><a href="http://www.facebook.com/kitamen">Facebook</a></p>
			<p><a href="http://www.instagram.com/kitamen">Instagram</a></p>
			<p>Gaming Studio Inquiry, Buy and Sell Video Games</p>
			<p>Contact Gon 017-6814931</p>
			<p>Rentals and Technical Inquiry</p>
			<p>Contact</p>
			<p>Man 016-4446716</p>
			<br />
			<p>email: kitamenstudio@gmail.com</p>
			<br>
			<p><b>PAYMENT</b></p>
			<p>CIMB: KITAMEN Studios Bhd 7049173313</p>
			<p>Maybank: KITAMEN Studios Bhd 1064329058865</p>
			<p>Bank Islam: KITAMEN Studios Bhd 10562883544219</p>
		</div>
		
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>