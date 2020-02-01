<?php
	session_start();
?>
<html>
	<head>
		<style type="text/css" media="all">@import "./includes/style.css";</style>
		<title>Terms And Condition</title>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
	<?php
		require_once ('config/mysql_connect.php'); // Connect to the database
		include ('./includes/header.html');
		
	?>
	<br>
		<div class="wrapper">
			<p>Thanks for choosing at KITAMEN</p>
			<p>If you are not entirely satisfied with your purchase, we're here to help.</p>
			<br>
			<p><b>Returns</b></p>
			<p>You have 7 calendar days to return an item from the date you received it.</p>
			<p>To be eligible for a return, your item must be unused and in the same condition that you received it.</p>
			<p>Your item must be in the original packaging.</p>
			<p>Your item needs to have the receipt or proof of purchase.</p>
			<br>
			<p><b>Refunds</b></p>
			<p>Once we receive your item, we will inspect it and notify you that we have received your returned</p>
			<p>item. We will immediately notify you on the status of your refund after inspecting the item.</p>
			<br>
			<p>If your return is approved, we will initiate a refund to your credit card (or original method of payment).</p>
			<p>You will receive the credit within a certain amount of days, depending on your card issuer's policies.</p>
			<br>
			<p><b>Shipping</b></p>
			<p>You will be responsible for paying for your own shipping costs for returning your item. Shipping costs</p>
			<p>are non refundable. If you receive a refund, the cost of return shipping will be deducted from your</p>
			<p>refund.</p>
			<br>
			<p><b>Contact Us</b></p>
			<p>If you have any questions on how to return your item to us, contact us.</p>
			<p></p>
		</div>
	</body>
</html>