<?php
	session_start();
?>
<html>
	<head>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="icon" href="favicon.ico">
	<style type="text/css" media="all">@import "./includes/style.css";</style>
	<title>Login</title>
	</head>
	
	<body>
	<div id="body">
		<?php # Script 2 add_court.php
		// This page allows the user to book court.
		
		require_once ('config/mysql_connect.php'); // Connect to the database
		include ('./includes/header.html');
		
		
		
		if (isset($_POST['forgotten'])) {
			echo'
			<div class="wrapper"><br /><br />
				<div class="div-login" id="login_form">
					<div class="form-login">
						<center>
							<form action="login.php" method="post" id="loginform" role="form">
								<div class="form-group has-feedback">
									Password Retrieval:<br /><br />
									<input type="text" name="email" placeholder="E-mail" class="form-control input-lg" />
								</div>
								<div class="form-group has-feedback">
									<input type="text" name="member_id" placeholder="Username" class="form-control input-lg" />
								</div>
								
								<input type="submit" name="pwdbtn" class="btn stronghold-btn-default pull-right" value="Request">
								<input type="hidden" name="submitforgot" value="TRUE" />
							</form>
						</center>
						<hr>
					</div>
				</div>
			</div>';
			
			if(isset($_POST['submitforgot'])){
				
				$errors1 = array();
								
				if(!empty($_POST['email'])) {
					$email = $_POST['email'];
				} else {
					$email= FALSE;
					$errors1[] = '<p><font color="red">-Please enter your email</font></p>';
				}
				
				if(!empty($_POST['member_id'])) {
					$member_id = $_POST['member_id'];
				} else {
					$member_id= FALSE;
					$errors1[] = '<p><font color="red">-Please enter your username</font></p>';
				}
				
				if (empty($errors1)) {
					$message = "
					<html>
					<body>
		You have requested for password change. Please ensure this is you.

		<a href=\"http://localhost/FYP/passwordretrieve.php?email=" . $email ."&member_id=" . $member_id ." \">Confirm Change Password</a>
									
		Thank you for shopping at KITAMEN!
		</body>
		</html>
		";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					mail($email,"KITAMEN Confirmation Receipt", $message, $headers);
					
				} else { // Report the errors.
					
					echo '<div class="wrapper"><h1 id="mainhead">Error!</h1>
					<p class="error">The following error(s) occured:<br />';
					foreach ($errors1 as $msg1) { // Print each error.
						echo $msg1;
					}
					echo '</p><p>Please try again later.</p><p><br /></p></div>';	
				}
			}
		}
		
		if (isset($_POST['submitted'])) {	// Check if the form is submitted
			
			require_once ('config/mysql_connect.php');
			
			// username and password sent from form.
			$mid = $_POST['member_id'];
			$p = $_POST['password'];
			
			// To protect MySQL injection.
			$mid = stripslashes($mid);
			$p = stripslashes($p);
			$mid = mysqli_real_escape_string($dbc, $mid);
			$p = mysqli_real_escape_string($dbc, $p);
			
			$query = "SELECT member_id, member_pass FROM member WHERE member_id = '$mid' AND member_pass = SHA('$p')";
			$result = mysqli_query($dbc, $query);
			
			if($result){
				// Mysql_num is counting table row.
				$count = mysqli_num_rows($result);
				
				//If result matched $mid and $p and redirect to file "login_success"
				if($count == 1) {
					$_SESSION["member_id"] = $mid;
					$_SESSION["password"] = $p;
					
					$row2 = mysqli_fetch_array($result);
					$_SESSION['member_email'] = $row2['member_email'];
					
					$sql = "SELECT role_id FROM user_roles WHERE member_id = '" . $_SESSION['member_id'] . "' AND role_id = 0";
					$result2 = @mysqli_query($dbc, $sql);
					
					if($result2){
						$row = mysqli_num_rows($result2);
						if($row == 1){
							header("location:login_success_admin.php");
							$_SESSION['admin'] = 1;
						} else{
							header("location:login_success.php");
							$_SESSION['member'];
							$_SESSION['admin'] = 0;
						}
					}
				} else {
					echo 'Wrong Username or Password';
					echo '<p>Register an account <a href="register.php">Here!</a></p><br/>';
				}
			}
			
		} // End of the main Submit conditional.
		?>
		<!-- Login Form -->
		<div class="wrapper"><br /><br />
		<?php
			if(!isset($_POST['forgotten'])){
		?>
			<div class="div-login" id="login_form">
				<div class="headerlogin">
					<p class="first_p">WELCOME</p><br />
					<p class="last_p">To KITAMEN Entertainment Website</p>
				</div>
				<div class="form-login">
					<center><form action="login.php" method="post" id="loginform" role="form">
						<div class="form-group has-feedback">
							Login:<br /><br />
							<input name="member_id" type="text" id="username" class="form-control input-lg login-user" onfocus=""
							style="background-image: url(images/user_id.png); background-size: 30px 30px; background-position: right center; background-repeat: no-repeat;"
							placeholder="Username" value= "<?php if(isset($_POST['username'])) echo $_POST['username']; ?>">
						</div>
						<div class="form-group has-feedback">
							<td><input name="password" type="password" onfocus=""
							style="background-image: url(images/key.png); background-size: 30px 30px; background-position: right center; background-repeat: no-repeat;"
							placeholder="Password" class="form-control input-lg pass-user" id="password"></td>
						</div>
						
						<input type="submit" name="Submit" class="btn stronghold-btn-default pull-right" value="Login">
						<input type="hidden" name="submitted" value="TRUE" />
					</form></center>
					<a href="register.php" style="float:left;">Register Here!</a>
					<a href="forgotpassword.php" style="float:right;">Forgot Password</a><br />
					<hr>
					<center><a href="home.php">Return to home</a></center>
				</div>
			</div>
		
		
		
			<!--<br /><br />
			<table width="300" border="0" align="center" cellpadding="2" cellspacing="1" bgcolor="#CCCCCC">
				<tr>
					<form action="login.php" method="post" >
						<td>
							<table width="100%" border="0" cellpadding="3" cellspacing="5" bgcolor="#FFFFFF">
								<tr><td colspan="3"><strong>Member Login </strong></td></tr>
								
								<tr>
									<td width="78">Username</td>
									<td width="6">:</td>
									<td width="294"><input name="member_id" type="text" id="username" value= "?php if(isset($_POST['username'])) echo $_POST['username']; ?>"></td></tr>
								
								<tr>
									<td>Password</td><td>:</td>
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
			</table> -->
	</div>
	<?php
			}
		echo'<div class="push"></div>';
		include ('./includes/footer.html');
	?>
	<script src="./js/loginfunc.js"></script>
	</body>
</html>