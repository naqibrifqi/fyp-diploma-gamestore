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
			echo '<br><div class="wrapper">';
			
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
					$query = "SELECT member_id, member_email FROM member WHERE member_email = '" . $email ."' AND member_id = '" . $member_id . "'";
					$result = mysqli_query($dbc, $query);
					if($result){
						if(mysqli_num_rows($result) == 1){
							$message = "
							<html>
							<body>
				You have requested for password change. Please ensure this is you.

				<a href=\"http://localhost/FYP/passwordretrieve.php?email=" . $email ."&member_id=" . $member_id ." \">Confirm Change Password</a>
											
				Thank you for shopping at KITAMEN!
				</body>
				</html>
				";
							$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
							mail($email,"KITAMEN Confirmation Receipt", $message, $headers);
							echo '<p>A confirmation link has been sent to your email.</p>';
							echo '<p>Back to <a href="login.php">Login</a></p>';
							exit();
						}else{
							echo "<br><font color=\"red\">No such registered user detected.</font><br>";
							echo "<br><a href=\"register.php\">Register an Account</a><br>";
							echo mysqli_error($dbc);
						}
					}else{
						echo "No user detected from the database.<br>";
						exit();
					}
					
				} else { // Report the errors.
					
					echo '<div class="wrapper"><h1 id="mainhead">Error!</h1>
					<p class="error">The following error(s) occured:<br />';
					foreach ($errors1 as $msg1) { // Print each error.
						echo $msg1;
					}
					echo '</p><p>Please try again later.</p><p><br /></p></div>';	
				}
			}
		?>
				<div class="div-login" id="login_form">
					<div class="headerlogin">
						<p class="first_p">Forgot Password</p><br />
						<p class="last_p">Please enter your email and username.</p>
					</div>
					<div class="form-login">
						<center>
							<form action="forgotpassword.php" method="post" id="loginform" role="form">
								<div class="form-group has-feedback">
									<input type="text" name="email" placeholder="E-mail"  class="form-control input-lg" />
								</div>
								<div class="form-group has-feedback">
									<input type="text" name="member_id" placeholder="Username"  class="form-control input-lg" />
								</div>
								<input type="submit" name="pwdbtn" class="btn stronghold-btn-default pull-right" value="Request">
								<input type="hidden" name="submitforgot" value="TRUE" />
							</form>
						</center>
						<hr>
					</div>
				</div>
			</div>
	</body>
</html>