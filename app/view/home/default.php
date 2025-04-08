<?php
session_start();
?>

<div class="container">
	<h1>This is my Home Page.</h1>
	
	<p>I'm <?php echo $data['name']; ?> from Manado.</p>
	
	<p>I'm <?php echo $data['age']; ?> years old this year.</p>
	<?php
		echo "<b>User Session Information: </b><br>";
		echo "<b>Session Role: </b>".$_SESSION['user-role']."<br>";
		echo "<b>Session User Name: </b>".$_SESSION['user-name']."<br>";
		echo "<b>Session Email: </b>".$_SESSION['user-email']."<br><br>";
	?>
	<img src="<?php echo APP_PATH; ?>/img/test.png" alt="test image" width="200" class="rounded-circle shadow">
</div>
