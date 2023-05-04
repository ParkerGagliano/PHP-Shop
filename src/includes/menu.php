<?php 
    session_start();

    $currentPage = basename($_SERVER['SCRIPT_FILENAME']); 
?>
<div class="w-auto">
<ul id="nav" class="flex flex-col space-y-4 ">
    <li><a href="index.php" <?php if ($currentPage == 'index.php') {echo 'class="text-orange-300"'; } ?>>Home</a></li>
    <li><a href="gallery.php" <?php if ($currentPage == 'gallery.php') {echo 'class="text-orange-300"'; } ?>>Gallery</a></li>
    <li><a href="product_list.php" <?php if ($currentPage == 'product_list.php') {echo 'class="text-orange-300"'; } ?>>Purchase Items</a></li>
    <li><a href="cart.php" <?php if ($currentPage == 'cart.php') {echo 'class="text-orange-300"'; } ?>>View Cart</a></li>

        
    
    <?php if (isset($_SESSION['firstname'])) { ?>
        <li><a href="logged_out.php" <?php if ($currentPage == 'logout.php') {echo 'class="text-orange-300"'; } ?>>Logout</a></li>
    <?php } else { ?>
        <li><a href="register.php" <?php if ($currentPage == 'register.php') {echo 'class="text-orange-300"'; } ?>>Register</a></li>
        <li><a href="login.php" <?php if ($currentPage == 'login.php') {echo 'class="text-orange-300"'; } ?>>Login</a></li>

    <?php } ?>
</ul>
</div>

