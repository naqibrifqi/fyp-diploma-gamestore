<?php
	session_start();
	if($_SESSION['admin'] == 0) {
		header("location:login.php");
	}
?>
<html>
	<head>
		<title>Admin Update</title>
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	
	<body class="admin-body">
		<?php
			require_once('config/mysql_connect.php');
			include('./includes/admin_header.html');
			
			if(isset($_GET['member_id'])){
				$mid = $_GET['member_id'];
			}else{
				header('member.php');
			}
			
			
			//////////////////////////////////////////////////////////////
			////////If The Delete Button to delete is clicked////////////
			//////////////////////////////////////////////////////////////
			
			
			if(isset($_POST['delete'])){
				if(!empty($_POST['member_id'])) {
					$mid = $_POST['member_id'];
				} else {
					$mid= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Member ID!</font></p>';
				}
				
				$querydel = "DELETE FROM user_roles WHERE member_id = '" . $mid . "'";
				$resultdel = mysqli_query($dbc, $querydel);
				if($resultdel){
					$querydel2 = "DELETE FROM member WHERE member_id = '" . $mid . "'";
					$resultdel2 = mysqli_query($dbc, $querydel2);
					
					if($resultdel2){
						echo '<div class="wrapper">News with ID: ' . $mid . ' has been deleted.</div>';
					} else{
						echo '<div class="wrapper">Error deleting user occured.<br>' . mysqli_error($dbc);
					}
				} else{
					echo '<div class="wrapper">Error deleting user occured.<br>' . mysqli_error($dbc);
				}
			}
			
			
			
			//////////////////////////////////////////////////////////////
			////////If The Submit Button to edit is clicked////////////
			//////////////////////////////////////////////////////////////
			
			if(isset($_POST['submitted'])){
					$errors = array();
					
					if(!empty($_POST['member_id'])) {
						$mid = $_POST['member_id'];
					} else {
						$mid= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the Member ID</font></p>';
					}
					
					if(!empty($_POST['member_lname'])) {
						$mln = $_POST['member_lname'];
					} else {
						$mln= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the Member Last Name</font></p>';
					}
					
					if(!empty($_POST['member_fname'])) {
						$mfn = $_POST['member_fname'];
					} else {
						$mfn= "NULL";
						$errors[] = '<p><font color="red">-Please recheck the Member First Name</font></p>';
					}
					
					if(!empty($_POST['member_phone'])) {
						$mp = $_POST['member_phone'];
					} else {
						$mp= "NULL";
						$errors[] = '<p><font color="red">-Please recheck the Member Phone</font></p>';
					}
					
					if(!empty($_POST['member_email'])) {
						$me = $_POST['member_email'];
					} else {
						$me = "NULL";
						$errors[] = '<p><font color="red">-Please recheck the Member Email</font></p>';
					}
					
					if(!empty($_POST['address'])) {
						$a = $_POST['address'];
					} else {
						$a = "NULL";
					}
					
					if(!empty($_POST['city']))
						$c = $_POST['city']; 
					else
						$c = "NULL";
					
					if(!empty($_POST['country']))
						$ct = $_POST['country']; 
					else
						$ct = "NULL";
					
					if(!empty($_POST['state']))
						$s = $_POST['state']; 
					else
						$s = "NULL";
					
					if(!empty($_POST['postal']))
						$p = $_POST['postal']; 
					else
						$p = "NULL";
					
					if(!empty($_POST['member_level']) && $_POST['member_level'] == "Admin") {
						$ml = $_POST['member_level'];
						$ur = 0;
					}else if(!empty($_POST['member_level']) && $_POST['member_level'] == "Normal Member") {
						$ml = $_POST['member_level'];
						$ur = 2;
					}else {
						$ml = "NULL";
						$errors[] = '<p><font color="red">-Please recheck the Member Level</font></p>';
					}
						
					$query2 = 'UPDATE member
					SET member_fname = "' . $mfn .'", member_lname= "' . $mln .'", member_phone= "' . $mp . '", member_email= "' . $me . '", address= "' . $a . '"
					, city= "' . $c . '", country= "' . $ct . '", state= "' . $s . '", postal= "' . $p . '", member_level= "' . $ml . '"
					WHERE member_id = "' . $mid . '"';
					$result2 = @mysqli_query($dbc, $query2);
					
					if(!$result2){
						echo('Error updating news: ' . mysqli_error($dbc));
						exit();
					}else{
						$queryrole = "UPDATE user_roles SET role_id = '" . $ur ."' WHERE member_id = '" . $mid . "'";
						$roleres = mysqli_query($dbc, $queryrole);
						
						if($roleres){
							echo('<div class="wrapper"><br />Success Updating!<br><a href="member.php">Click here</a> to view other users.<br>
							<a href="admindash.php">Click here</a> to return to the main admin page.</div>');
						}
						exit();
					}		
			}
		?>
		<div class="wrapper">
			<div class="article_holder">
				<form action="member_edit.php" method="post">
					<?php
					
						if(isset($_GET['member_id'])){
							$mid = $_GET['member_id'];
						}else{
							header('member.php');
						}
						
						$query = "SELECT member_id, member_lname, member_fname, member_email, member_phone, member_level, 
						address, city, country, state, postal 
						FROM member 
						WHERE member_id = '" . $mid . "'";
						$result = mysqli_query($dbc, $query);
						if($result){
							echo '<h2>Edit Member\'s Information</h2><table>';
							while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
								
								echo '<tr><td>ID</td><td><input type="text" size="50" name="member_id" value="' . $row['member_id'] .'" readonly/></td>';
								echo '<td>Address</td><td><input type="text" size="50" name="address" value="' . $row['address'] .'"/></td></tr>';
								echo '<tr><td>First Name</td><td><input type="text" size="50" name="member_fname" value="' . $row['member_fname'] .'"/></td>';
								echo '<td>City</td><td><input type="text" size="50" name="city" value="' . $row['city'] .'"/></td></tr>';
								echo '<tr><td>Last Name</td><td><input type="text" size="50" name="member_lname" value="' . $row['member_lname'] .'"/></td>';
								echo '<td>Country</td><td><input type="text" size="50" name="country" value="' . $row['country'] .'"/></td></tr>';
								echo '<tr><td>Phone</td><td><input type="text" size="50" name="member_phone" value="' . $row['member_phone'] .'"/></td>';
								echo '<td>State</td><td><input type="text" size="50" name="state" value="' . $row['state'] .'"/></td></tr>';
								echo '<tr><td>Email</td><td><input type="text" size="50" name="member_email" value="' . $row['member_email'] .'"/></td>';
								echo '<td>Postal</td><td><input type="text" size="50" name="postal" value="' . $row['postal'] .'"/></td></tr>';
								echo '<tr><td>Level</td><td><input type="text" size="50" name="member_level" value="' . $row['member_level'] .'"/></td></tr>';
							}
							echo '</table><br />';
						}
					?>
					<input type="submit" name="submit" value="Update" class="btn" />
					<input type="submit" name="delete" value="Delete This User?" style="float:right"  onclick="return confirm('Are you sure you want to delete this member?');" class="btn" />
					<input type="hidden" name="submitted" value="TRUE" />
				</form>
			</div>
		</div>
	</body>
</html>