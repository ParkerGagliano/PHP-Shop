<?php 
	require_once('../reg_conn.php');
	session_start();
	if (isset($_SESSION['email']) )  {
		$name = $_SESSION['firstname'];
		$message = "Logged out: $name";
		$message2 = "Successful logout";
        session_destroy();
        setcookie('PHPSESSID', '', time()-3600, '/');
	} else { 
		$message = 'You have reached this page in error';
		$message2 = 'Please use the menu at the right';	
	}
require 'includes/header.php';
?>
	<main>
	<?php 
		// Print the message:
		echo '<h2>'.$message.'</h2>';
		echo '<h3>'.$message2.'</h3>';
		?>
	</main>
</div>
	<?php // Include the footer and quit the script:
	include ('./includes/footer.php'); 
	?>
	