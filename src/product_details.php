<?php
	//display errors php

	//end display errors php
	require './includes/header.php';
	require_once '../../mysqli_connect.php';
	echo '<main class="mx-auto>';
	
	function shortTitle ($title){
        $title = substr($title, 0, -4);
        $title = str_replace('_', ' ', $title);
        $title = ucwords($title);
        $title = rtrim($title, ".");
        return $title;
    }
	if(isset($_GET['image_id'])) {
		$imgID = filter_var($_GET['image_id'], FILTER_VALIDATE_INT);
		$getDetails= "SELECT * FROM proj_images WHERE image_id = ?";
		$stmt = mysqli_prepare($dbc, $getDetails);
		mysqli_stmt_bind_param($stmt, 'i', $imgID);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		$rows = mysqli_num_rows($result);
		if ($rows == 1) { // Valid print ID.
			// Fetch the information.
			$item = mysqli_fetch_assoc($result);
			// Retrieve the query results into scalar variables
			$filename = $item['filename'];

			$description = $item['details'];	
			$caption = $item['caption'];	
			$price = $item['price'];
		}
?>	
        <h2 class="text-2xl font-bold mb-4">Purchase: <?php echo shortTitle($filename); ?></h2>
        <div class="mb-4">
            <img src="./images/<?php echo $filename; ?>" alt="<?php echo $title; ?>">
        </div>
        <h3 class="text-lg font-bold mb-2"><strong>Description:</strong></h3>
        <h4 class="text-lg mb-2"><?php echo $caption; ?></h4>
        
        <h4 class="text-lg mb-2"><?php echo $description; ?></h4>
        <h4 class="text-lg font-bold mb-4"><strong>Price: </strong>$<?php echo $price; ?></h4>

        <!-- Insert Add to Cart button here -->
        <form action="cart.php" method="post">
            <input type="hidden" name="image_id" value="<?php echo $imgID; ?>">
            <input type="hidden" name="action" value="add">
            <button type="submit" class="border border-orange-600  font-bold py-2 px-4 rounded">
                Add to Cart
            </button>
        </form>
        <?php if (isset($_SESSION['cart'])) { ?>
            <a href="cart.php" class="border border-orange-600  font-bold py-2 px-4 rounded">View Cart</a>
        <?php } else { ?>
            <h2 class="text-xl font-bold mb-4">We are unable to process your request at this time.</h2>
            <h3 class="text-lg">Please try again later.</h3>
            <?php
                include './includes/footer.php';
                exit;
            ?>
        <?php }
    } else {
        ?>
        <h2 class="text-2xl font-bold mb-4">You have reached this page in error</h2>
        <h3 class="text-lg">Use the menu at the left to view our products.</h3>
        <?php
            include './includes/footer.php';
            exit;
    }
    echo '</main>';
    include './includes/footer.php';
?>
