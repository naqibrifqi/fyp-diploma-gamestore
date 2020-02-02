<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Gaming Studio - KITAMEN</title>
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
			<div class="content-wrapper">
				<h1 style="font-family: Verdana, Geneva, Tahoma, sans-serif">GAMING STUDIO</h1>
				<hr>
				<p>KITAMEN ENTERTAINMENT</p>
				<p>As part of the expansion plan, we took our business into different approach by approaching other gaming</p> 
				<p>studio or retail outlets and offer our Electronic Entertainment Experience Certified . </p>
				<p>With this, we market other places and people may enjoy the Kitamen Experience as per our Standards.</p>
				<br />
				<img src="./images/sponsor/KITAMEN-certified-copy.jpg"></p>
				<p>Please refer their official facebook page</p>
				<p>1-22 Blok B Jalan Dagang B/3A, Dagang Avenue, 68000 Ampang, Kuala Lumpur</p>
				<p><a href="http://www.facebook.com/GeekEmpireAmpang">Facebook Geek Empire Ampang</a></p>
				<hr>
				<p><img src="./images/sponsor/KITAMEN-EXPERIENCE-logo-copy.jpg"></p>
				<p>Rate:</p>
				<p>PS4 + 1DS4 @RM5/hr</p>
				<p>additional DS4 @RM5/hr</p>
				<p>1. The Vape – Bukit Sentosa</p>
				<p>13, 1st floor, Jalan Orkid 1F/1, Bukit Sentosa, 48300 Rawang, Selangor</p>
				<p><a href="https://www.facebook.com/The-VAPE-Bukit-Sentosa-722910224502817/?fref=ts">Facebook The VAPE Bukit Sentosa</a></p>
				<hr>	
			</div>
		</div>
		
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>