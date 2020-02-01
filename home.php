<?php
	session_start();
?>
<!DOCTYPE php>
<html>
	<head>
		<title>Kitamen - Electronics Entertainment</title>
		<link rel="stylesheet" type="text/css" href="./includes/footer.html">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include ('./includes/header.html');
		?>
		
		<div class ="wrapper">
			<?php	
				$query = "SELECT id, story, name, headline, image, timestamp 
				FROM news ORDER BY timestamp DESC";
				$result = mysqli_query($dbc, $query);
					
				if($result){
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						echo '<h1><a href="mainnews.php?id=' . $row['id'] . '">' . $row['headline'] . '</a></h1>';
						echo '<font size="2">' . $row['name'] . '</font> <font size="1">' . $row['timestamp'] . '</font><br /><br />';
						echo '<img style="width:100%; max-width: 100%; height: auto;" src="' . $row['image'] . '" width="550" height="350" />' . '<br /><br />';
						echo '<font size="4">' . substr(nl2br($row['story']), 0, 250) . '...<br /></font><font size="5"><a href="mainnews.php?id=' . $row['id'] . '">Read More</a></font><br /><br /><br /><br /><hr>';
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