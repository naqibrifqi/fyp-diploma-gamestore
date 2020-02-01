<?php
	session_start();
?>
<html>
	<head>
	<style type="text/css" media="all">@import "./includes/style.css";</style>
	<title>Login</title>
	</head>
	
	<body>
	<div id="body">
		<?php # Script 2 add_court.php
		// This page allows the user to book court.
		
		require_once ('config/mysql_connect.php'); // Connect to the database
		include ('./includes/header.html');
		
		if (isset($_POST['submitted'])) {	// Check if the form is submitted
			
			require_once ('config/mysql_connect.php');
			
			// username and password sent from form.
			$mid = $_POST['member_id'];
			$p = $_POST['password'];
			
			// To protect MySQL injection.
			$mid = stripslashes($mid);
			$p = stripslashes($p);
			$mid = mysqli_real_escape_string($dbc,$mid);
			$p = mysqli_real_escape_string($dbc,$p);
			
			$query = "SELECT * FROM member WHERE member_id = '$mid' AND member_pass = SHA('$p')";
			$result = mysqli_query($dbc, $query);
			
			// Mysql_num is counting table row.
			$count = mysqli_num_rows($result);
			
			//If result matched $mid and $p and redirect to file "login_success"
			if($count == 1) {
				$_SESSION["member_id"] = $mid;
				$_SESSION["password"] = $p;
				header("location:login_success.php");
			} else {
				echo 'Wrong Username or Password';
				echo '<p>Register an account <a href="register.php">Here!</a></p><br/>';
			}
			
		} // End of the main Submit conditional.
		?>
		<h1>Member's Login</h1>
		<table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
		<tr>
		<form action="login.php" method="post" >
			<td>
			<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
			<tr><td colspan="3"><strong>Member Login </strong></td></tr>
			
			<tr><td width="78">Username</td>
			<td width="6">:</td>
			<td width="294"><input name="member_id" type="text" id="username" value= "<?php if(isset($_POST['username'])) echo $_POST['username']; ?>"></td></tr>
			
			<tr><td>Password</td><td>:</td>
			<td><input name="password" type="password" id="password"></td>
			</tr>
			
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><input type="submit" name="Submit" value="Login">
			<input type="hidden" name="submitted" value="TRUE" /></td>
			</tr>
			</table>
			</td>
		</form>
		</tr>
	</div>
	</body>
</html>