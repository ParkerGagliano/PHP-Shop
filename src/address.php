<?php //This page is part of the checkout process if the user hasn't yet entered an email address.
	//This page allows multiple addresses for a given emaiil address.
	//A more complete solution would query the database for all addresses associated with an entered email address and provide a choice to use an existing address or enter a new one
	require '../secure_conn.php';
	require 'includes/header.php';
	//show all errors php

    
    require_once ('../../mysqli_connect.php'); // Connect to the db.
	
	if(!isset($_SESSION['email'])) {	//user not logged in
		$guest_info = TRUE; //name and email must be collected
	}
	else
		$validemail = $_SESSION['email'];
        $sql = "SELECT customerEmail, street1, street2, city, state, zip FROM proj_addresses WHERE customerEmail = ?";
        $stmt = mysqli_prepare($dbc, $sql);
        mysqli_stmt_bind_param($stmt, 's', $validemail);
        mysqli_stmt_execute($stmt);
        $result=mysqli_stmt_get_result($stmt);
        $rows = mysqli_num_rows($result);
        if ($rows==0) 
            $guest_info = TRUE; //name and email must be collected
        else { // email found, validate password
            $result2=mysqli_fetch_assoc($result); //convert the result object pointer to an associative array 
            $firstname = $result2['firstname'];
            $lastname = $result2['lastname'];
            $email = $result2['customerEmail'];
            $line1 = $result2['street1'];
            $line2 = $result2['street2'];
            $city = $result2['city'];
            $state = $result2['state'];
            $zip = $result2['zip'];
            header('Location: confirm_cart.php');
        }
        
        

	if (isset($_POST['send'])) {
		$missing = array();
		$errors = array();
		$line1= filter_var(trim($_POST['line1']), FILTER_SANITIZE_STRING);
		if (empty($line1))
			$missing[] = 'line1';
		$line2= filter_var(trim($_POST['line2']), FILTER_SANITIZE_STRING);
		$city = filter_var(trim($_POST['city']), FILTER_SANITIZE_STRING);
		if (empty($city))
			$missing[] = 'city';
		$state = filter_var(trim($_POST['state']), FILTER_SANITIZE_STRING);
		if (empty($state))
			$missing[] = 'state';
		elseif (strlen($state) != 2)
			$errors[] = 'state';
		else {
			$validstate=$state;
			$validstate= strtoupper($validstate);
		}
		$zip = filter_var(trim($_POST['zip']), FILTER_SANITIZE_STRING);
		if (empty($zip))
			$missing[] = 'zip';
		elseif (strlen($zip) != 5)
			$errors[] = 'zip';
		else $validzip=$zip;
		if($guest_info){
			$firstname = filter_var(trim($_POST['firstname']), FILTER_SANITIZE_STRING);
			if (empty($firstname))
				$missing[] = 'firstname';
			$lastname = filter_var(trim($_POST['lastname']), FILTER_SANITIZE_STRING);
			if (empty($lastname))
				$missing[] = 'lastname';
			$email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);	//returns a string or null if empty or false if not valid	
			if (trim($_POST['email']==''))
				$missing[] = 'email';
			elseif (!$email)
			$errors[] = 'email';
			else 
				$validemail = $email;
			}//guest info
		if (!$missing && !$errors) {
			
			$sql = "INSERT INTO proj_addresses(customerEmail, street1, street2, city, state, zip) values (?,?,?,?,?,?)";
			$stmt = mysqli_prepare($dbc, $sql);
			mysqli_stmt_bind_param($stmt, 'sssssi', $validemail, $line1, $line2, $city, $validstate, $validzip);
			$result = mysqli_stmt_execute($stmt);
			if (!$result){
				echo "We are unable to process your request at  this  time. Please try again later.";
				include 'includes/footer.php'; 
				exit;
			}
			else {
				$_SESSION['email'] = $validemail;
				$_SESSION['fn'] = $firstname;
				$_SESSION['ln'] = $lastname;
			?>
			<main>
			<h2>We have saved your address</h2>
			<a href= "confirm_cart.php" class="underline">Please proceed to checkout</a>
			</main>
            </div>
			<?php
				include 'includes/footer.php';
				exit;
			}
		} // missing || errors 
	} //$_POST['send']	?>
	<main class="p-6">
    <form class="max-w-lg mx-auto" method="post" action="address.php">
        <fieldset>
            <legend class="text-lg font-medium mb-4">Please enter your shipping address:</legend>
            <?php if ($missing || $errors) { ?>
            <p class="bg-red-100 text-red-800 py-2 px-4 rounded mb-4">Please fix the item(s) indicated.</p>
            <?php } ?>
            <?php if ($guest_info){ //only collect name and email if user is a guest/not logged in.?>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="fn" class="block text-sm font-medium text-gray-700 mb-1">First Name:</label>
                        <input name="firstname" id="fn" type="text" class="form-input rounded-md shadow-sm border-gray-300 block w-full" 
                        <?php if (isset($firstname)) {
                            echo 'value="' . htmlspecialchars($firstname) . '"';
                        } ?>>
                        <?php if ($missing && in_array('firstname', $missing)) { ?>
                            <p class="text-red-500 mt-1 text-sm">Please enter your first name</p>
                        <?php } ?> 
                    </div>
                    <div>
                        <label for="ln" class="block text-sm font-medium text-gray-700 mb-1">Last Name:</label>
                        <input name="lastname" id="ln" type="text" class="form-input rounded-md shadow-sm border-gray-300 block w-full" 
                        <?php if (isset($lastname)) {
                            echo 'value="' . htmlspecialchars($lastname) . '"';
                        } ?>>
                        <?php if ($missing && in_array('lastname', $missing)) { ?>
                            <p class="text-red-500 mt-1 text-sm">Please enter your last name</p>
                        <?php } ?>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
                    <input name="email" id="email" type="text" class="form-input rounded-md shadow-sm border-gray-300 block w-full"
                    <?php if (isset($email) && !$errors['email']) {
                        echo 'value="' . htmlspecialchars($email) . '"';
                    } ?>>
                    <?php if ($missing && in_array('email', $missing)) { ?>
                        <p class="text-red-500 mt-1 text-sm">Please enter your email address</p>
                    <?php } ?>
                    <?php if ($errors && in_array('email', $errors)) { ?>
                        <p class="text-red-500 mt-1 text-sm">The email address you provided is not valid</p>
                    <?php } ?>
                </div>
            <?php } //end guest_info ?>
			<p>
    <label for="l1" class="block font-medium text-gray-700">Street (Line 1): 
        <?php if ($missing && in_array('line1', $missing)) { ?>
            <span class="warning text-red-600">Please enter your street address</span>
        <?php } ?> 
    </label>
    <input name="line1" id="l1" type="text" 
        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        <?php if (isset($line1)) {
            echo 'value="'.htmlspecialchars($line1).'"';
        } ?>
    >
</p>
<p>
    <label for="l2" class="block font-medium text-gray-700">Street (Line 2 / Optional): </label>
    <input name="line2" id="l2" type="text"
        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        <?php if (isset($line2)) {
            echo 'value="'.htmlspecialchars($line2).'"';
        } ?>
    >
</p>
<p>
    <label for="city" class="block font-medium text-gray-700">City: 
        <?php if ($missing && in_array('city', $missing)) { ?>
            <span class="warning text-red-600">Please enter your city</span>
        <?php } ?>
    </label>
    <input name="city" id="city" type="text"
        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        <?php if (isset($city)) {
            echo 'value="'.htmlspecialchars($city).'"';
        } ?>
    >
</p>
<p>
    <label for="state" class="block font-medium text-gray-700">State:
        <?php if ($errors && in_array('state', $errors) || $missing && in_array('state', $missing)) { ?>
            <span class="warning text-red-600">Please enter your two-letter state code</span>
        <?php } ?> 
    </label>
    <input name="state" id="state" type="text"
        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        <?php if (isset($state)) {
            echo 'value="'.htmlspecialchars($state).'"';
        } ?>
    >
</p>
<p>
    <label for="zip" class="block font-medium text-gray-700">Zip code:
        <?php if ($missing && in_array('zip', $missing)) { ?>
            <span class="warning text-red-600">Please enter your zip code</span>
        <?php } 
            if ($errors && in_array('zip', $errors)) { ?>
            <span class="warning text-red-600">Please use just your 5-digit zip code</span>
        <?php } ?>
    </label>
    <input name="zip" id="zip" type="text"
        class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
        <?php if (isset($zip)) {
            echo 'value="'.htmlspecialchars($zip).'"';
        } ?>
    >
            </p>
            <p>
            <input name="send" type="submit" value="Save Address" class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
            </p>
		</fieldset>
        </form>
	</main>
            </div>
<?php include 'includes/footer.php'; ?>