<?php
	session_start();
	session_start();
	if($_SESSION['admin'] == 0) {
		header("location:login.php");
	}
?>
<html>
	<head>
		<title>Details Update</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body class="admin-body">
		<div class="wrapper">
			<?php
				require_once ('config/mysql_connect.php');
				include('./includes/admin_header.html');
			?>
			
			<h2>All Members</h2>
			<div class="article_holder">
				<form action="admin_article.php" method="post">
					<table class="table-alpha">
						<tr class="table-alpha-header">
							<th>ID</th>
							<th>Name</th>
							<th>Phone Number</th>
							<th>Email</th>
							<th>Level</th>
						</tr>
						<?php
							$sql = "SELECT member_id, CONCAT(member_fname, ' ', member_lname) AS fullname, member_email, member_level, member_phone
							FROM member";
							$resultsql = mysqli_query($dbc, $sql);
							
							if($resultsql){
								while($row = mysqli_fetch_array($resultsql, MYSQLI_ASSOC)) {
									echo '<tr bgcolor="#ffffff"><td><a href="member_edit.php?member_id=' . $row['member_id'] . '" style="text-decoration:none;">' . $row['member_id'] . '</a></td>';
									echo '<td>' . $row['fullname'] . '</td>';
									echo '<td>' . $row['member_phone'] . '</td>';
									echo '<td>' . $row['member_email'] . '</td>';
									echo '<td>' . $row['member_level'] . '</td></tr>';
								}
								
								echo '</table>';
							}
						?>
				</form>
			</div>
			<hr>
		</div>
	</body>
</html>