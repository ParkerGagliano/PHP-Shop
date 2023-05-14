<?php 
	require '../secure_conn.php';
	require_once '../../mysqli_connect.php';
	require 'includes/header.php'; 
    //show php errors


	if (!$_SESSION['payment']) { 
        echo '<h2>We were unable to process your payment.</h2>';
		echo '<h3>Please try again later</h3>';
	} elseif (isset($_SESSION['email']) && !empty($_SESSION['email']) && $_SESSION['payment']) {
		$email = $_SESSION['email'];
		date_default_timezone_set("America/New_York");
		$orderDate = date("Y-m-d", time());
		$total = $_SESSION['total'];  
		//insert data into JJ_orders table
		$query = "INSERT INTO proj_orders (customerEmail, orderDate, total) VALUES ('$email', '$orderDate', $total)";
		$result=mysqli_query($dbc, $query);
		if(!$result){
				echo "We are unable to process your request at  this  time. Please try again later.";
				include 'includes/footer.php'; 
				exit;
		} 
		else {//query OK
 		//Orders table updated successfully.  Now update order_details
 	  
			$orderID = mysqli_insert_id($dbc); //retrieves the autonum assigned 
			$cart = $_SESSION['cart'];
			//initialize any unset values
			$itemNum=1;
			
			foreach($cart as $img => $item) { //retrieve the cart variables from the session
				$imageID = $img;
				$qty = $item['quantity'];
				$price = $item['price'];
				$query2 = "INSERT INTO proj_order_details values ($itemNum, $orderID, $imageID, $qty, $price)";
				$result2=mysqli_query($dbc, $query2);
				$itemNum++;
			} // end foreach
		} 
		?>
	<main>
			<h2>Your order is complete. A summary is below:</h2>
			<?php
				echo "<h3>Order number: $orderID</h3>";
				echo "<h3>Order date: $orderDate</h3>";
				echo "<h3>Order Total: $".$total."</h3>";	
				unset($_SESSION['cart']); //clear the cart
                echo "</div>";
	} //first elseif
	else
		echo "You have reached this page in error.";		
	?>	
</main>
<?php include 'includes/footer.php'; ?>