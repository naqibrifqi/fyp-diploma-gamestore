<?php
	session_start();
?>
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<title>Change Password</title>
	</head>
	
	<body>
	<?php # Script 2 add_court.php
	// This page allows the administrator to add court.
	
	require_once ('config/mysql_connect.php'); // Connect to the database
	include ('./includes/header.html');
			
			$sql = "SELECT member_email FROM member WHERE member_id = '" . $_SESSION['member_id'] . "'";
			
			$res = mysqli_query($dbc,$dbc,$sql);
			
			if($res){
				$row = mysqli_fetch_array($res);
				
				if(isset($_POST['pwdrequested'])){
					
					$errors = array();
					
					if (!empty($_POST['oldpassword'])) {	
						$op = $_POST['oldpassword'];
					} else {	// if the username field is empty
						$op = FALSE;
						$errors[] = '<p><font color="red">-Please enter your old password!</font></p>';
					}
					
					if (!empty($_POST['password1'])) {
						if ($_POST['password1'] != $_POST['password2']) {
							$errors[] = '<p><font color="red">-Your entered password did not match!</font></p>';
						} else { // If password matched.
							$p = trim($_POST['password1']);
						}
					} else { // If password field is empty
						$errors[] = '<p><font color="red">-You forgot to enter your password!</font></p>';
					}// end checking for email
					
					if (!empty($_POST['email'])) {	
						$e = $_POST['email'];
					} else {	// if the username field is empty
						$e = FALSE;
						$errors[] = '<p><font color="red">-Please enter your E-mail!</font></p>';
					}
					
					if (empty($errors)) {
						$querypass = "SELECT member_id, member_email, member_fname 
						FROM member
						WHERE member_id = '" . $_SESSION['member_id'] ."' AND member_email = '$e' AND member_pass = SHA('$op') ";
						$respass = mysqli_query($dbc,$dbc,$querypass);
						
						if($respass){
							if(mysqli_num_rows($respass) == 1){
								$querychange = "UPDATE member SET member_pass = SHA('$p') 
								WHERE member_id = '" . $_SESSION['member_id'] . "' AND member_email = '" . $e . "'";
								$changeres = mysqli_query($dbc,$dbc,$querychange);
								
								if($changeres){
									echo '<br /><div class="wrapper"><h1 id="mainhead">SUCCESS!</h1>
									<p>You have changed your password! You may login with the new password now.</p></div>';
									
								}else {	// If it did not run OK.
									echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
									<p class="error">The data cannot be entered due to a system error. </p>';
									echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
									exit();
								}
							}else{
								echo '<br /><div class="wrapper"><h1 id="mainhead">Incorrect</h1>
								<p>The information you\'ve entered is incorrect</p>
								<p>If you think this is a mistake, please contact the system administrator</p></div>';
							}
						} else {	// If it did not run OK.
							echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
							<p class="error">The data cannot be entered due to a system error. </p>';
							echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
							exit();
						}
					} else { // Report the errors.
					
						echo '<div class="wrapper"><h1 id="mainhead">Error!</h1>
						<p class="error">The following error(s) occured:<br />';
						foreach ($errors as $msg) { // Print each error.
							echo $msg;
						}
						echo '</p><p>Please try again later.</p><p><br /></p></div>';	
					}
				}
	?>
	
	<div class="wrapper"><br /><br />
				<div class="div-login" id="login_form">
					<div class="form-login">
						<center>
							<form action="password_edit.php" method="post" id="loginform" role="form">
								<div class="form-group has-feedback">
									Change Password:<br /><br />
									<input type="text" name="email" placeholder="E-mail" class="form-control input-lg" value="<?php echo $row['member_email']; ?>"/>
								</div>
								<div class="form-group has-feedback">
									<input type="password" name="oldpassword" placeholder="Old Password" class="form-control input-lg" />
								</div>
								<div class="form-group has-feedback">
									<input type="password" name="password1" placeholder="New Password" class="form-control input-lg" />
								</div>
								<div class="form-group has-feedback">
									<input type="password" name="password2" placeholder="Confirm Password" class="form-control input-lg" />
								</div>
								
								<input type="submit" name="pwdbtn" class="btn stronghold-btn-default pull-right" value="Request">
								<input type="hidden" name="pwdrequested" value="TRUE" />
							</form>
						</center>
						<hr>
					</div>
				</div>
			</div>
	</body>
</html>

<?php
			}
?>