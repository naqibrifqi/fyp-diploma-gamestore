<?php
	session_start();
?>
<html>
	<head>
		<style type="text/css" media="all">@import "./includes/style.css";</style>
		<title>Retrieve Password</title>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
	</head>
	
	<body>
	<?php
		require_once ('config/mysql_connect.php'); // Connect to the database
		include ('./includes/header.html');
		
		if(isset($_GET['email']) && isset($_GET['member_id'])){
			echo'
			<div class="wrapper"><br /><br />
				<div class="div-login" id="login_form">
					<div class="headerlogin">
						<p class="first_p">Update New Password</p><br />
						<p class="last_p">Please enter your new password</p>
					</div>
					<div class="form-login">
						<center>
							<form action="passwordretrieve.php" method="post" id="loginform" role="form">
								<div class="form-group has-feedback">
									<input type="text" name="member_email" placeholder="E-mail" value="' . $_GET['email'] .'" readonly class="form-control input-lg" />
								</div>
								<div class="form-group has-feedback">
									<input type="text" name="member_id" placeholder="Username" value="' . $_GET['member_id'] .'" readonly class="form-control input-lg" />
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
			</div>';
		}
		
		if(isset($_POST['pwdrequested'])){
				
				$errors = array();
				
				if (!empty($_POST['password1'])) {
					if ($_POST['password1'] != $_POST['password2']) {
						$errors[] = '<p><font color="red">-Your entered password did not match!</font></p>';
					} else { // If password matched.
						$p = trim($_POST['password1']);
					}
				} else { // If password field is empty
					$errors[] = '<p><font color="red">-You forgot to enter your password!</font></p>';
				}// end checking for email
				
				if (!empty($_POST['member_email'])) {	
					$e = $_POST['member_email'];
				} else {	// if the username field is empty
					$e = FALSE;
					$errors[] = '<p><font color="red">-Please enter your E-mail!</font></p>';
				}
				
				if (!empty($_POST['member_id'])) {	
					$mid = $_POST['member_id'];
				} else {	// if the username field is empty
					$mid = FALSE;
					$errors[] = '<p><font color="red">-Please enter your username!</font></p>';
				}
				
				if (empty($errors)) {
					$querypass = "SELECT member_id, member_email, member_fname 
					FROM member
					WHERE member_email = '$e' AND member_id = '$mid'";
					$respass = mysqli_query($dbc,$dbc,$querypass);
					
					if($respass){
						if(mysqli_num_rows($respass) == 1){
							$querychange = "UPDATE member SET member_pass = SHA('$p') 
							WHERE  member_email = '" . $e .  "'";
							$changeres = mysqli_query($dbc,$dbc,$querychange);
							
							if($changeres){
								echo '<br /><div class="wrapper"><h1 id="mainhead">SUCCESS!</h1>
								<p>You have changed your password! You may <a href="login.php">login</a> with the new password now.</p>
								<a href="login.php"/>Login Here!</a></div>';
								
							}else {	// If it did not run OK.
								echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
								<p class="error">The data cannot be entered due to a system error. </p>';
								echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
								exit();
							}
						}else{
							echo '<br /><div class="wrapper"><h1 id="mainhead">Incorrect</h1>
							<p>The information you\'ve entered is incorrect</p>
							<p>If you think this is a mistake, please contact the system administrator</p>
							<a href="login.php"/>Login Here!</a></div>';
							echo mysqli_error($dbc);
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
						echo "$msg";
					}
					echo '</p><p>Please try again later.</p><p><br /></p></div>';
					
				}
		}
				?>
	</body>
</html>