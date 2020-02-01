<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Kitamen - Elctronics Entertainment</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
		?>
		
		<div class ="wrapper">
			<?php
			
				if(isset($_GET['id'])){
					$id = $_GET['id'];
				}else{
					header('home.php');
				}
				
				$query = "SELECT story, name, headline, image, timestamp 
				FROM news WHERE id = '" . $id . "'";
				$result = mysqli_query($dbc, $query);
					
				if($result){
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						echo '<h1>' . $row['headline'] . '</h1>';
						echo '<font size="2">' . $row['name'] . '</font> <font size="1">' . $row['timestamp'] . '</font><br /><br />';
						echo '<img src="' . $row['image'] . '" width="850" height="550" />' . '<br /><br />';
						echo '<font size="3">' . nl2br($row['story']) . '</font><br /><br /><font size="4"><a href="home.php">Back to homepage</a></font><br /><br /><br /><br /><hr>';
					}
				} else {	// If it did not run OK.
					echo '<h1 id="mainhead">System Error</h1>
					<p class="error">The data cannot be entered due to a system error. </p>';
					echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
					exit();
				}
			?>
		</div>
		
		<div class="push"></div>
		<?php
			include ('./includes/footer.html');
		?>
	</body>
</html>