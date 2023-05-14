<?php require 'includes/header.php'; ?>
<main>
    <h2>Your Cart</h2>
    <?php if (empty($_SESSION['cart']) || !isset($_SESSION['cart'])) { 
        echo '<h2>There are no products in your cart.</h2>';
		echo '<h3>Please use the Purchase Prints link to the left to shop.</h3>';
	} else { 
		//display cart if not empty
		$total = 0; // Initialize cart total to recalculate according to current values. ?>
        <h4 class="text-xl font-bold">To remove an item from your cart, change its quantity to 0.</h4>
<form action="cart.php" method="post" class="mt-8">
  <input type="hidden" name="action" value="update">
  <table>
    <tr id="cart_header">
      <th class="text-left">Image</th>
      <th class="text-right">Price</th>
      <th class="text-right">Quantity</th>
      <th class="text-right">Total</th>
    </tr>
    <?php foreach($_SESSION['cart'] as $img => $item){ ?>
    <!--Print the row: -->
    <tr>
      <td>
        <?php echo $item["title"]; ?>
      </td>
      <td class="text-right">$<?php echo $item['price']; ?></td>
      <td class="text-right">
        <input type="number" class="cart_qty" name="newqty[<?php echo $img; ?>]" value="<?php echo $item['quantity'];?>" class="w-16 p-3">
      </td>
      <?php 
      // Calculate the total and sub-totals.
      if (!isset ($item['quantity']))
        $item['quantity']=0;
      $subtotal = $item['quantity'] * $item['price'];
      $total += $subtotal;?>
      <td class="text-right">$<?php echo number_format($subtotal, 2); ?></td>
    </tr>
    <?php 
    } // End of the foreach loop.?> 
    <!-- Print the total, close the table, and the form:-->
    <tr></tr>
    <tr id="cart_footer">
      <td class="text-right" colspan="3"><strong>Total:</strong></td>
      <td class="text-right"><strong>$<?php echo number_format($total, 2);?></strong></td>
    </tr>
    <tr></tr>
    <tr>
      <td></td><td></td>
      <td><input type="submit" name="submit" value="Update My Cart" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"></td>
      <td></td>
    </tr>
  </table>
</form>
<br><br>
<!-- Print the "Checkout" button and form: -->
<p><a href="checkout.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Checkout</a></p>
<p><a href="cart.php?action=empty" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Empty Cart</a></p>
<p><a href="checkout.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Checkout</a></p>
		
		<?php } //end else?>			
 
</main>
<?php include 'includes/footer.php'; ?>