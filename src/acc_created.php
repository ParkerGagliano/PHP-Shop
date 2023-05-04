
<?php

require_once('../secure_conn.php');
require('includes/header.php');
session_start();
echo"<main class='mx-auto w-full text-center'>";
if(isset($_SESSION['createFN'])) {
    $firstname = $_SESSION['createFN'];
    echo "<main><h2>Thank you for registering $firstname</h2><h3>We have saved your information</h3></main>";
    echo "<main><a class='text-orange-300'href='login.php'>Click here to login with your new account</a></main>";
    unset($_SESSION['createFN']);
} else {
    echo "<main><h2>You have reached this page in error</h2><h3>Please use the menu at the right</h3></main>";
}
echo "</main></div>";
include('includes/footer.php');
?>

