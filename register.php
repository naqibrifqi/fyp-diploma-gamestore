<?php
	session_start();
?>
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="./includes/style.css" />
		<title>User Registration</title>
	</head>
	
	<body>
	<?php # Script 2 add_court.php
	// This page allows the administrator to add court.
	
	require_once ('config/mysql_connect.php'); // Connect to the database
	include ('./includes/header.html');
	if (isset($_POST['submitted'])) {	// Check if the form is submitted
		
		$errors = array();
		
		// Check for the username(cust_id)
		if (!empty($_POST['member_id'])) {	
			$mid = $_POST['member_id'];
		} else {	// if the username field is empty
			$mid = FALSE;
			$errors[] = '<p><font color="red">-Please enter your preferred member ID!</font></p>';
		}//	end checking for username(cust_id)
		
		// Check for the email
		if (!empty($_POST['email'])) {	
			$e = $_POST['email'];
		} else {	// if the email field is empty
			$e = FALSE;
			$errors[] = '<p><font color="red">-Please enter your email!</font></p>';
		}//	end checking for email
		
		//	Check for the password.
		if (!empty($_POST['password1'])) {
			if ($_POST['password1'] != $_POST['password2']) {
				$errors[] = '<p><font color="red">-Your entered password did not match!</font></p>';
			} else { // If password matched.
				$p = trim($_POST['password1']);
			}
		} else { // If password field is empty
			$errors[] = '<p><font color="red">-You forgot to enter your password!</font></p>';
		}// end checking for email
		
		// Check for the user's first name.
		if (!empty($_POST['fname'])) {	
			$fn = $_POST['fname'];
		} else { // If the first name field is empty.
			$fn = FALSE;
			$errors[] = '<p><font color="red">-Please enter your first name!</font></p>';
		}//	End checking for first name.
		
		// Check for the user's last name.
		if (!empty($_POST['lname'])) {	
			$ln = $_POST['lname'];
		} else { // If the last name field is empty.
			$ln = FALSE;
			$errors[] = '<p><font color="red">-Please enter your last name!</font></p>';
		}//	End checking for last name.
		
		// Check for the phone number.
		if (!empty($_POST['phone'])) {	
			$ph = $_POST['phone'];
		} else { // If the address field is empty.
			$ph = FALSE;
			$errors[] = '<p><font color="red">-Please enter your phone number!</font></p>';
		}//	End checking for phone number.
		
		if (isset($_POST['tnc'])) {	
			$tnc = $_POST['tnc'];
		} else { // If the last name field is empty.
			$tnc = FALSE;
			$errors[] = '<p><font color="red">-You\'ve not agreed with the Terms and Condition yet!</font></p>';
		}//	End checking for last name.
		
		
		//	If everything is okay.
		if (empty($errors)) {
			
			//	Register the user in the database.
			require_once ('config/mysql_connect.php');	//	Connect to the DB.
			
			$querycheck = "SELECT member_id FROM member WHERE member_id = '" . $mid . "'";
			$resquery = mysqli_query($dbc, $querycheck);
			
			if(mysqli_num_rows($resquery) == 1){
				echo '<div class="wrapper">
				<br /><font color="red"><p>The Username has already been used. Please change to other username.</p></font>
				</div>';
			}else{
				// Make the query.
				$query = "INSERT INTO member (member_id, member_pass, member_fname, member_lname, member_phone, member_email, member_level, member_date) 
				 VALUES('$mid', SHA('$p'), '$fn', '$ln', '$ph','$e', 'Normal Member', NOW())";
				$result = @mysqli_query ($dbc, $query); //	Run the query.
				if ($result) {	//	If it ran OK.
					
					//	Print a message.
					echo '<br /><div class="wrapper"><h1 id="mainhead">Welcome!</h1>
					<p>You are now a registered member to this site.</p>
					<a href="login.php"/>Login Here!</a></div>';
					
					$sql = "INSERT INTO user_roles VALUES('" . $mid ."', 2)";
					$resultsql = @mysqli_query($dbc, $sql);
					if($resultsql){
						echo '<div class="wrapper"><p>Normal Member!</p></div>';
					}
					
					exit();
					
				} else {	// If it did not run OK.
					echo '<div class="wrapper"><h1 id="mainhead">System Error</h1>
					<p class="error">The data cannot be entered due to a system error. </p>';
					echo '<p>' . mysqli_error($dbc) . '<br /><br /></p></div>';
					exit();
				}
				
				mysqli_close($dbc);	// Close the database connection.
			}
			
		} else { // Report the errors.
		
			echo '<div class="wrapper"><h1 id="mainhead">Error!</h1>
			<p class="error">The following error(s) occured:<br />';
			foreach ($errors as $msg) { // Print each error.
				echo "$msg";
			}
			echo '</p><p>Please try again later.</p><p><br /></p></div>';
			
		} // End of if (empty($errors) IF.
	} // End of the main Submit conditional.
	?>
	<div class="wrapper"><br /><br />
		<div class="div-login" id="login_form">
			<div class="headerlogin">
				<p class="first_p">User Registration</p><br />
				<p class="last_p">To KITAMEN Entertainment Website</p>
			</div>
			
			<div class="form-login">
				<center><form action="register.php" method="post" id="loginform" role="form">
					<div class="form-group has-feedback">
						Registration:<br /><br />
						<input type="text" name="member_id" class="form-control input-lg" placeholder="Username" size="25" maxlength="30" value="<?php if(isset($_POST['member_id'])) echo $_POST['member_id']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						<input type="password" name="password1" class="form-control input-lg" placeholder="Password" size="25" maxlength="30" value="<?php if(isset($_POST['password1'])) echo $_POST['password1']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						<input type="password" name="password2" class="form-control input-lg" placeholder="Confirm Password" size="25" maxlength="30" value="<?php if(isset($_POST['password2'])) echo $_POST['password2']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						<input type="text" name="fname" class="form-control input-lg" placeholder="First Name" size="25" maxlength="30" value="<?php if(isset($_POST['fname'])) echo $_POST['fname']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						<input type="text" name="lname" class="form-control input-lg" placeholder="Last Name" size="25" maxlength="30" value="<?php if(isset($_POST['lname'])) echo $_POST['lname']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						<input type="text" name="phone" class="form-control input-lg" placeholder="Phone Number" size="25" maxlength="30" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						<input type="text" name="email" class="form-control input-lg" placeholder="Email" size="25" maxlength="30" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"/>
					</div>
					<div class="form-group has-feedback">
						Membership Level: <font color="#ed1d70">Normal Member</font>
					</div>
					
					<input type="checkbox" name="tnc" value="chkyes">I am now hereby agree with the <a href="tnc.php" target="_blank">Terms & Condition</a> of 
					KITAMEN during my registration<br /><br />
					
					<input type="submit" name="submit" class="btn stronghold-btn-default pull-right" value="Register" /></p>
					<input type="hidden" name="submitted" value="TRUE" />
				</form></center>
				<hr>	
			</div>
		</div>
	
		<!--<h1>User Registration</h1>
		<form action="register.php" method="post">
			<p>Member ID (Username) : <input type="text" name="member_id" placeholder="username" size="15" maxlength="30" value="<?php if(isset($_POST['member_id'])) echo $_POST['member_id']; ?>"/><p>
			<p>Password : <input type="password" name="password1" placeholder="password" size="15" maxlength="30" value="<?php if(isset($_POST['password1'])) echo $_POST['password1']; ?>"/></p>
			<p>Confirm Password : <input type="password" name="password2" placeholder="confirm password" size="15" maxlength="30" value="<?php if(isset($_POST['password2'])) echo $_POST['password2']; ?>"/></p>
			<p>First Name : <input type="text" name="fname" placeholder="first name" size="15" maxlength="30" value="<?php if(isset($_POST['fname'])) echo $_POST['fname']; ?>"/><p>
			<p>Last Name : <input type="text" name="lname" placeholder="last name" size="15" maxlength="30" value="<?php if(isset($_POST['lname'])) echo $_POST['lname']; ?>"/><p>
			<p>Phone Number : <input type="text" name="phone" placeholder="phone number" size="15" maxlength="30" value="<?php if(isset($_POST['phone'])) echo $_POST['phone']; ?>"/><p>
			
			<p>E-mail : <input type="text" name="email" placeholder="email" size="25" maxlength="30" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"/><p>
			<p>Membership Level: <font color="#ed1d70">Normal Member</font></p>
			
			<p><input type="submit" name="submit" value="Register" /></p>
			<input type="hidden" name="submitted" value="TRUE" />
		</form>-->
	</div>
	<div class="push"></div>
	<?php
		include ('./includes/footer.html');
	?>
	</body>
</html>