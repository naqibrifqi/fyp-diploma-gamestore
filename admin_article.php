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
		<div class="wrapper">
			<?php
				require_once ('config/mysql_connect.php');
				include('./includes/admin_header.html');
				
				if(isset($_POST['submitted'])){
					$errors = array();
					
					if(!empty($_POST['headline'])) {
						$headline = mysqli_real_escape_string($dbc, $_POST['headline']);
					} else {
						$headline= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the Headline</font></p>';
					}
					
					if(!empty($_POST['authname'])) {
						$authname = $_POST['authname'];
					} else {
						$authname= "NULL";
					}
					
					if(!empty($_POST['story'])) {
						$story = mysqli_real_escape_string($dbc, $_POST['story']);
					} else {
						$story= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the story</font></p>';
					}
					
					if(empty($errors)){
						function findexts($filename)  {  
							$filename = strtolower($filename) ;  
							$exts = preg_split("[/\\.]", $filename) ;  
							$n = count($exts)-1;  
							$exts = $exts[$n];  
							return $exts;  
						}
						
						$ext = findexts ($_FILES['uploaded']['name']) ; 
						
						$ran = rand();
						$ran2 = $ran.".";
						$target = "images/story/";
						$target = $target . $ran2.$ext;
						
						if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))  { 
							echo "<div class=\"wrapper\">The file has been uploaded as ".$ran2.$ext . "</div>"; 
						}  else { 
							echo "<div class=\"wrapper\">Sorry, there was a problem uploading your file.</div>"; 
						}
						
						
						/*$file = $_FILES['file'];
						$name = $file['name'];
						
						if ($_FILES['file']['error'] > 0) {
							echo "Return Code: " . $_FILES['file']['error'] . "<br />";
						} else {
							if(file_exists("images/story/" . $_FILES["file"]["name"])) {
								echo $_FILES["file"]["name"] . " already exists. ";
							} else {
								move_uploaded_file($_FILES["file"]["tmp_name"],
								"images/story/" . $_FILES["file"]["name"]);
								echo "Stored in: " . "images/story/" . $_FILES["file"]["name"] . "<br />";
								$path = mysqli_real_escape_string($dbc,"images/story/" . $_FILES["file"]["name"]);
							}
						}*/
						
						$query = "INSERT INTO news(name, headline, image, story, timestamp)
						VALUES('$authname', '$headline', '$target', '$story', NOW())";
						$result = @mysqli_query($dbc, $query);
						
						if(!$result){
							echo('Error adding news: ' . mysqli_error($dbc));
							exit();
						}else{
							echo('<br />Success!<br><a href="add.php">Click here</a> to add more news.<br><a href="edit.php">Click 
							here</a> to edit news.<br><a href="../index.php">Click here</a> to return to the main page.');
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
			
			<div class="article_holder">
			<h2>New Post</h2>
				<form action="admin_article.php" method="post" enctype="multipart/form-data">
					<table>
						<tbody>
							<tr>
								<td>Headline:</td>
								<td><input type="text" name="headline" placeholder="The Title Here" size="50" value="<?php if(isset($_GET['headline'])) echo  $_GET['headline']?>"/></td>
							</tr>
							
							<tr>
								<td>Post Image:</td>
								<td><input type="file" name="uploaded"></td>
							</tr>
							
							<tr>
								<td>Author's Name:</td>
								<td><input type="text" name="authname" placeholder="Author's Name" value="<?php if(isset($_GET['name'])) echo  $_GET['name']?>"/></td>
							</tr>
							
							<tr>
								<td>Story:</td>
								<td><textarea rows="4" cols="50" name="story" placeholder="Post here"></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2"><div align="center">
									<input type="submit" name="submit" value="Submit" class="btn" />
									<input type="hidden" name="submitted" value="TRUE">
								</div></td>
							</tr>
							
						</tbody>
					</table>
				</form>
			</div>
			
			<hr>
			<h2>Update Post</h2>
			<div class="article_holder">
				<form action="admin_article.php" method="post">
					<table>
						<tr>
							<th>News ID</th>
							<th>Headline</th>
							<th>Story</th>
							<th>Author's Name</th>
						</tr>
						<?php
							$sql = "SELECT id, headline, story, name FROM news ORDER BY timestamp desc";
							$resultsql = mysqli_query($dbc, $sql);
							
							if($resultsql){
								while($row = mysqli_fetch_array($resultsql, MYSQLI_ASSOC)) {
									echo '<tr><td>' . $row['id'] . '</td>';
									echo '<td><a href="article_edit.php?id=' . $row['id'] . '" style="text-decoration:none;">' . $row['headline'] . '</a></td>';
									echo '<td>' . substr($row['story'], 0, 150) . '...</td>';
									echo '<td>' . $row['name'] . '</td></tr>';
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