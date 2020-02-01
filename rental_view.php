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
	</head>
	
	<body>
		<div class="wrapper">
		<br>
			<?php
				require_once ('config/mysql_connect.php');
				include('./includes/admin_header.html');
				
				if(isset($_GET['rent_id'])){
					$rid = $_GET['rent_id'];
				}else{
					$rid = FALSE;
				}
				
				if(isset($_POST['deleted'])){
					
					if(isset($_POST['rent_id'])){
						$rid = $_POST['rent_id'];
					}else{
						$rid = FALSE;
					}
					
					if(isset($_POST['rent_id'])){
						$sql = "DELETE FROM rental WHERE rent_id = '" . $rid . "'";
						$sqlres = mysqli_query($dbc,$dbc,$sql);
						if($sqlres){
							echo '<h1 id="mainhead">Deleted</h1>
							<p class="error">The rental data has been deleted!</p>
							<p class="error"><a href="admindash.php">Back to Dashboard</a></p>';
							exit();
						}else {	// If it did not run OK.
							echo '<h1 id="mainhead">System Error</h1>
							<p class="error">The data cannot be entered due to a system error. (Select)</p>';
							echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
							exit();
						}
					}
					echo '<p class="error"><a href="admindash.php">Back to Dashboard</a></p>';
					exit();
				}
				
					$query = "SELECT rent_id, rent_depo, details, member_id, datestamp
					FROM rental
					WHERE rent_id = '" . $rid . "'";
					
					$result = mysqli_query($dbc, $query);
					
					if($result){
						echo '<table class="dashboard-table" border="3">
						<tr><th>Rent ID</th>
						<th>Rental Deposit</th>
						<th>Details</th>
						<th>Member ID</th>
						<th>Datestamp</th></tr>';
						while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
							echo '<tr><td>' . $row['rent_id'] . '</td>';
							echo '<td>' . $row['rent_depo'] . '</td>';
							echo '<td>' . $row['details'] . '</td>';
							echo '<td>' . $row['member_id'] . '</td>';
							echo '<td>' . $row['datestamp'] . '</td></tr>';
						}
						echo '</table><br>';
						echo '<form action="rental_view.php" method="post">';
						echo '<table class="dashboard-table">';
						echo '<tr><td>Delete Order</td><td><input type="text" name="rent_id" value="' . $rid .'" /></td></tr>';
						echo '</table>';
						echo '<input type="submit" name="delete" value="Delete" class="btn" onclick="return confirm(\'Are you sure you want to Delete?\');"/>';
						echo '<input type="hidden" name="deleted" value="TRUE" />';
						echo '</form>';
						
					} else {	// If it did not run OK.
						echo '<h1 id="mainhead">System Error</h1>
						<p class="error">The data cannot be entered due to a system error. (Select)</p>';
						echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
						exit();
					}
			?>
		</div>
	</body>
</html>