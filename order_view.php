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
		<div class="wrapper">
		<br>
			<?php
				require_once ('config/mysql_connect.php');
				include('./includes/admin_header.html');
				
				if(isset($_GET['po_id'])){
					$poid = $_GET['po_id'];
				}else{
					$poid = FALSE;
				}
				
				if(isset($_POST['deleted'])){
					
					if(isset($_POST['po_id'])){
						$poid = $_POST['po_id'];
					}else{
						$poid = FALSE;
					}
					
					if(isset($_POST['prod_id'])){
						$pid = $_POST['prod_id'];
					}else{
						$pid = FALSE;
					}
					
					if(isset($_POST['po_id']) && empty($_POST['prod_id'])){
						$sql = "DELETE FROM receipt WHERE po_id = '" . $poid . "'";
						$sqlres = mysqli_query($dbc,$dbc,$sql);
						if($sqlres){
							$sql2 = "DELETE FROM preorder WHERE po_id = '" . $poid . "'";
							$sql2res = mysqli_query($dbc,$dbc,$sql2);
							if($sql2res){
								echo '<h1 id="mainhead">Deleted</h1>
								<p class="error">The order has been deleted!</p>
								<p class="error"><a href="admindash.php">Back to Dashboard</a></p>';
								exit();
							}
						} else {	// If it did not run OK.
							echo '<h1 id="mainhead">System Error</h1>
							<p class="error">The data cannot be entered due to a system error. (Select)</p>';
							echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
							exit();
						}
					}
					
					if(isset($_POST['prod_id']) && isset($_POST['prod_id'])){
						$sql = "DELETE FROM receipt WHERE prod_id = '" . $pid . "' AND po_id ='" . $poid . "'";
						$sqlres = mysqli_query($dbc,$dbc,$sql);
						if($sqlres){
							echo '<h1 id="mainhead">Deleted</h1>
							<p class="error">The product\'s receipt line has been deleted!</p>
							<p class="error"><a href="admindash.php">Back to Dashboard</a></p>';
							exit();
						} else {	// If it did not run OK.
							echo '<h1 id="mainhead">System Error</h1>
							<p class="error">The data cannot be entered due to a system error. (Select)</p>';
							echo '<p>' . mysqli_error($dbc) . '<br /><br /></p>';
							exit();
						}
					}
				}
				
				$query = "SELECT r.po_id, r.prod_id, p.prod_name, p.prod_price, r.progress, r.quantity, r.details, m.member_fname
				FROM receipt r
				JOIN preorder po
				ON po.po_id = r.po_id
				JOIN products p
				ON p.prod_id = r.prod_id
				JOIN member m
				ON m.member_id = po.member_id
				WHERE r.po_id = '" . $poid . "'";
				
				$result = mysqli_query($dbc, $query);
				
				if($result){
					echo '<h2 style="margin-bottom: 50px;">Order ID: ' . $_GET['po_id'] . '</h2><table class="dashboard-table alpha-table">
					<tr class="alpha-table-header"><th>Order ID</th>
					<th>Product ID</th>
					<th>Product Name</th>
					<th>Product Price</th>
					<th>Progress</th>
					<th>Quantity</th>
					<th>Details</th>
					<th>Member Name</th></tr>';
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
						echo '<tr bgcolor="#ffffff"><td>' . $row['po_id'] . '</td>';
						echo '<td>' . $row['prod_id'] . '</td>';
						echo '<td>' . $row['prod_name'] . '</td>';
						echo '<td>' . $row['prod_price'] . '</td>';
						echo '<td>' . $row['progress'] . '</td>';
						echo '<td>' . $row['quantity'] . '</td>';
						echo '<td>' . $row['details'] . '</td>';
						echo '<td>' . $row['member_fname'] . '</td></tr>';
					}
					echo '</table><br>';
					echo '<form action="order_view.php" method="post">';
					echo '<table class="dashboard-table">';
					echo '<tr><td>Delete Order</td><td><input type="text" name="po_id" value="' . $poid .'" /></td></tr>';
					echo '<tr><td>Delete Receipt Line</td><td><input type="text" name="prod_id" /></td></tr>';
					echo '</table>';
					echo '<input type="submit" name="delete" value="Delete" class="btn" onclick="return confirm(\'Are you sure you want to Delete?\');"/>';
					echo '<input type="hidden" name="deleted" value="TRUE" />';
					echo '</form>';
				} else {	// If it did not run OK.
					echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
					<p class="error">The data cannot be entered due to a system error. </p>';
					echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
					exit();
				}
			?>
		</div>
	</body>
</html>