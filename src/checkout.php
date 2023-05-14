<?php 
	require 'includes/header.php'; 
?>
<main class="mx-auto w-full p-4">
    <?php if (empty($_SESSION['cart']) || !isset($_SESSION['cart'])) { 
        echo '<h2 class="text-3xl font-bold mb-4">There are no products in your cart.</h2>';
		echo '<h3 class="text-xl mb-4">Please use the Purchase Prints link to the left to shop.</h3>';
	} elseif (empty($_SESSION['email']) || !isset($_SESSION['email'])) { //user is not logged in ?>
		<h3 class="text-xl mb-4">If you are a registered user, please log in using the link at the left</h3>
		<h3 class="text-xl mb-4">Or choose one of the other options below</h3>
		<h3 class="text-xl"><a class="underline" href='register.php'>Register as a new user</a> or <a class="underline" href='address.php'>Continue checkout as a guest</a></h3>
	<?php } 
	else  { //user logged in
		$firstName = $_SESSION['firstname']; //set at login ?>	
		<h3 class="text-xl mb-4">Hello <?php echo $firstName;?>,</h3>
		<h3 class="text-xl mb-4">Please choose one of the options below:</h3>
		<h3 class="text-xl"><a class="underline text-orange-500" href='product_list.php'>Keep Shopping</a> or <a class="underline text-orange-500" href="cart_view.php">View Cart</a> or <a class="underline text-orange-500" href='address.php'>Proceed to Checkout</a></h3>
	<?php } ?>
</main>

    </div>
<?php include 'includes/footer.php'; ?>