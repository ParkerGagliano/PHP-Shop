<?php require 'includes/header.php'; ?>
<main class="w-full mb-10">
    <h2>Your Cart</h2>
    <?php if (empty($_SESSION['cart']) || !isset($_SESSION['cart'])) { 
        echo '<h2>There are no products in your cart.</h2>';
	} else { 
		//display cart if not empty
		$total = 0; // Initialize cart total to recalculate according to current values. ?>
                <h4 class="text-xl font-bold">To remove an item from your cart, change its quantity to 0.</h4>
<form action="cart.php" method="post" class="mt-8 mx-auto w-auto">
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
        <input type="number" name="newqty[<?php echo $img; ?>]" value="<?php echo $item['quantity'];?>" class="border border-orange-300 rounded-md p-2 w-full">
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
<a href="checkout.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-5">Checkout</a>
<a href="cart.php?action=empty" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mb-5" >Empty Cart</a>
<a href="checkout.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-5">Checkout</a>
		
		
		<?php } //end else?>			
 
</main>
</div>
<?php include 'includes/footer.php'; ?>