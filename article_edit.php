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
	
	<body>
		<?php
			require_once('config/mysql_connect.php');
			include('./includes/admin_header.html');
			
			if(isset($_GET['id'])){
				$id = $_GET['id'];
			}else{
				header('admin_article.php');
			}
			
			if(isset($_POST['delete'])){
				if(!empty($_POST['id'])) {
					$id = $_POST['id'];
				} else {
					$id= FALSE;
					$errors[] = '<p><font color="red">-Please recheck the Headline</font></p>';
				}
				
				$querydel = "DELETE FROM news WHERE id = '" . $id . "'";
				$resultdel = mysqli_query($dbc,$dbc,$querydel);
				
				if($resultdel){
					echo 'News with ID: ' . $id . ' has been deleted.';
				} else{
					echo 'Error deleting occured.';
				}
			}
			
			if(isset($_POST['submitted'])){
					$errors = array();
					
					if(!empty($_POST['id'])) {
						$id = $_POST['id'];
					} else {
						$id= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the Headline</font></p>';
					}
					
					if(!empty($_POST['headline'])) {
						$headline = $_POST['headline'];
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
						$story = mysqli_real_escape_string($dbc,$_POST['story']);
					} else {
						$story= FALSE;
						$errors[] = '<p><font color="red">-Please recheck the story</font></p>';
					}
					
					$file = $_FILES['uploaded'];
					$name = $file['name'];
					
					if($_FILES['uploaded']['size'] == 0){
						echo '<div class="wrapper"><br />Image retained.<br /></div>';
						
						$query2 = 'UPDATE news
						SET headline = "' . $headline .'", name= "' . $authname .'", story= "' . $story . '"
						WHERE id = "' . $id . '"';
						$result2 = @mysqli_query($dbc,$dbc,$query2);
						
						if(!$result2){
							echo('Error updating news: ' . mysqli_error($dbc));
							exit();
						}else{
							echo('<div class="wrapper"><br />Success Updating!<br><a href="add.php">Click here</a> to add more news.<br><a href="edit.php">Click 
							here</a> to edit news.<br><a href="../index.php">Click here</a> to return to the main page.</div>');
							
						}
					} else{
						
						/*if ($_FILES['file']['error'] > 0) {
							echo "Return Code: " . $_FILES['file']['error'] . "<br />";
							$path = NULL;
						} else {
							if(file_exists("images/story/" . $_FILES["file"]["name"])) {
								echo $_FILES["file"]["name"] . " already exists. ";
								$path = NULL;
							} else {
								move_uploaded_file($_FILES["file"]["tmp_name"],
								"images/story/" . $_FILES["file"]["name"]);
								echo "Stored in: " . "images/story/" . $_FILES["file"]["name"];
								$path = "images/story/" . $_FILES["file"]["name"];
							}
						}*/
						
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
						
						$query2 = 'UPDATE news
						SET headline = "' . $headline .'", name= "' . $authname .'", image= "' . $target . '", story= "' . $story . '"
						WHERE id = "' . $id . '"';
						$result2 = @mysqli_query($dbc,$dbc,$query2);
						
						if(!$result2){
							echo('Error updating news: ' . mysqli_error($dbc));
							exit();
						}else{
							echo('<br />Success Updating!<br><a href="add.php">Click here</a> to add more news.<br><a href="edit.php">Click 
							here</a> to edit news.<br><a href="../index.php">Click here</a> to return to the main page.');
							header('admin_article.php');
						}
					}	
			}
		?>
		<div class="wrapper">
			<div class="article_holder">
				<form action="article_edit.php" method="post" enctype="multipart/form-data">
					<?php
					
						if(isset($_GET['id'])){
							$id = $_GET['id'];
						}else{
							header('admin_article.php');
						}
						
						$query = "SELECT id, headline, image, story, name, timestamp FROM news WHERE id = '" . $id . "'";
						$result = mysqli_query($dbc, $query);
						if($result){
							echo '<table>';
							while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
								
								echo '<tr><td>ID</td><td><input type="text" size="50" name="id" value="' . $row['id'] .'" readonly/></td></tr>';
								echo '<tr><td>Headline</td><td><input type="text" size="50" name="headline" value="' . $row['headline'] .'"/></td></tr>';
								echo '<tr><td>Name of Author </td><td><input type="text" size="50" name="authname" value="' . $row['name'] .'"/></td></tr>';
								echo '<tr><td>Image</td><td><input type="file" name="uploaded"</td></tr>';
								echo '<tr><td>Story</td><td><textarea rows="20" cols="120" name="story">' . $row['story'] . '</textarea></td></tr>';
								
								echo '<h1>' . $row['headline'] . '</h1>';
								echo '<font size="2">' . $row['name'] . ' </font>  <font size="1">' . $row['timestamp'] . '</font><br /><br />';
								echo '<img src="' . $row['image'] . '" width="550" height="350" />' . '<br /><br />';
								echo '<font size="3">' . nl2br($row['story']) . '</font><br /><br /><br /><br /><hr>';
							}
							echo '</table>';
						}
					?>
					<input type="submit" name="submit" value="Update" class="btn" />
					<input type="submit" name="delete" value="Delete This Post?" style="float:right" onclick="return confirm('Are you sure you want to Delete?');" class="btn" />
					<input type="hidden" name="submitted" value="TRUE" />
				</form>
			</div>
		</div>
	</body>
</html>