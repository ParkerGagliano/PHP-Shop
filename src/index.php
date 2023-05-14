
<?php
    include('./includes/header.php');
    require_once('../../mysqli_connect.php');
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<main class='p-5'><h2 class='text-3xl font-bold'>Welcome back: $username</h2></main>";

    } else {
        echo "<main class='p-5'><h2 class='text-3xl font-bold'>Welcome to Marketio</h2></main>";
    }
    if (!isset($_SESSION['email'])) {    //user not logged in
        echo "<main class='p-5'><h2 class='text-3xl font-bold'>Log in to save your address for future orders</h2></main>";
        echo "<main class='p-5'><h2 class='font-bold'>Admins must log in to add more items for sale</h2></main>";
    } else {
        $validemail = $_SESSION['email'];
        $sql = "SELECT customerEmail, street1, street2, city, state, zip FROM proj_addresses WHERE customerEmail = ?";
        $stmt = mysqli_prepare($dbc, $sql);
        mysqli_stmt_bind_param($stmt, 's', $validemail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = mysqli_num_rows($result);
        if ($rows == 0) {
            if (isset($_SESSION['username'])) {
                echo "<main class='p-5'><h2 class='text-3xl font-bold'>Check out to save a new address</h2></main>";
            }
        } else { // email found, validate password
            $result2 = mysqli_fetch_assoc($result); //convert the result object pointer to an associative array 
            $firstname = $result2['firstname'];
            $lastname = $result2['lastname'];
            $email = $result2['customerEmail'];
            $line1 = $result2['street1'];
            $line2 = $result2['street2'];
            $city = $result2['city'];
            $state = $result2['state'];
            $zip = $result2['zip'];
            echo "<main class='p-5'><h2 class='text-3xl font-bold'>Your current address info: $line1 $city $state $zip</h2></main>";


        }
    }
    echo "</div></div>";
    include('includes/footer.php');
?>
