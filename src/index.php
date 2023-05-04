<?php

include('./includes/header.php');
if (isset($_SESSION['username']) )  {
    $username = $_SESSION['username'];
    echo "<main><h2>User: $username</h2><h3>We have saved your information</h3></main>";

} else {
    echo "<main><h2>Welcome to Marketio</h2></main>";
}
echo "</div>";
include('includes/footer.php');
?>
