<?php 
	session_start();
    include('includes/header.php');
    echo "<main class='mx-auto w-full text-center'>";
	if (empty($_SESSION['cart']) || !isset($_SESSION['cart'])) { 
        echo '<h2 class="text-xl font-medium">There are no products in your cart.</h2>';
		echo '<h3 class="text-lg">Please use the Purchase Prints link to the left to shop.</h3>';
	}
	else { ?>
		<table class="w-full border-collapse border-gray-300 my-4">
		  <tr id="cart_header" class="text-left bg-gray-200 border-b-2 border-gray-300">
			<th class="p-2">Name</th>
			<th class="p-2 text-right">Price</th>
			<th class="p-2 text-right">Quantity</th>
			<th class="p-2 text-right">Total</th>
		  </tr>
		  <?php foreach($_SESSION['cart'] as $img => $item){	?>
		  <!--Print the row: -->
		  <tr class="border-b-2 border-gray-300">
			<td class="p-2"><?php echo $item['title']; ?></td>
			<td class="p-2 text-right">$<?php echo $item['price']; ?></td>
			<td class="p-2 text-right"><?php echo $item['quantity'];?></td>
			<?php 
			// Calculate the total and sub-totals.
			if (!isset ($item['quantity']))
				$item['quantity']=0;
			$subtotal = $item['quantity'] * $item['price'];
			$total += $subtotal;?>
			<td class="p-2 text-right">$<?php echo number_format($subtotal, 2); ?></td>
		  </tr>
	<?php 
	} // End of the foreach loop.?> 
	<!-- Print the total, close the table, and the form:-->
		<tr id="cart_footer">
			<td class="p-2 text-right" colspan="3"><strong>Total:</strong></td>
			<td class="p-2 text-right"><strong>$<?php echo number_format($total, 2);?></strong></td>
		</tr>
		<?php $_SESSION['total']=$total; ?>
	</table>
	<a href = "checkout_payment.php" class="inline-block py-2 px-4 font-bold text-white bg-blue-500 hover:bg-blue-700 rounded">Proceed to payment</a>
	<?php }
?>
</main>
</div>

<?php include('includes/footer.php'); ?>