<?php //This page checks for required content, errors, and provides sticky output

    require './includes/header.php';
    //display errors php


	if (isset($_POST['send'])) {
	$errors = array();
	
	$firstname = filter_var(trim($_POST['firstname']), FILTER_SANITIZE_STRING); //returns a string
	if (empty($firstname)) 
		$errors['firstname']="First name is required";
	
	$lastname = filter_var(trim($_POST['lastname']), FILTER_SANITIZE_STRING); //returns a string
	if (empty($lastname)) 
		$errors['lastname']="Last name is required";

    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING); //returns a string
    if (empty($username))
        $errors['username']="Username is required";

	$email= filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
	if (empty($email))
		$errors['email'] = 'An email address is required:';
	else {
		//check validity
		$valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);	//returns a string or null if empty or false if not valid	
		if ($valid_email)
			$email = $valid_email;
		else
			$errors['email'] = 'A valid email is required:';
	}
	//Check to see if email address already exists
	//Handle as an error if yes 
	require_once '../../mysqli_connect.php';  //$dbc is the connection string set upon successful connection
	
	$sql="SELECT email from proj_users where email = ?";
	$stmt=mysqli_prepare($dbc, $sql);
	mysqli_stmt_bind_param($stmt, 's', $email);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) >=1)
		$errors['exists'] = 'That email already exists in the database. Please log in or enter a different email';
	mysqli_free_result($result);

    $sql="SELECT username from proj_users where username = ?";
	$stmt=mysqli_prepare($dbc, $sql);
	mysqli_stmt_bind_param($stmt, 's', $email);
	mysqli_stmt_execute($stmt);
	$result=mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) >=1)
		$errors['exists'] = 'That username already exists in the database. Please log in or enter a different username';
	mysqli_free_result($result);
	
	$password1 = filter_var(trim($_POST['password1']), FILTER_SANITIZE_STRING);
	$password2 = filter_var(trim($_POST['password2']), FILTER_SANITIZE_STRING);
	// Check for a password:
	if (empty($password1) || empty($password2)) 
		$errors['pw']= "Please enter the password twice";
	elseif ($password1 !== $password2) 
			$errors['pwmatch'] = "The passwords don't match";
	else $password = $password1;
	
	$accepted = filter_var($_POST['terms']);
	if (empty($accepted) || $accepted !='accepted')
		$errors['accepted'] = "You must accept the terms";
	
	if (!$errors) {	
		//Folder name is email stripped of non-alphanumeric characters
		$folder = preg_replace("/[^a-zA-Z0-9]/", "", $email);
		// make lowercase
		$folder = strtolower($folder);
		$sql2 = "INSERT into proj_users (firstname, lastname, email, pass, username) VALUES (?, ?, ?, ?, ?)";
		$stmt2 = mysqli_prepare($dbc, $sql2);
		$pw_hash= password_hash($password, PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt2, 'sssss', $firstname, $lastname, $email, $pw_hash, $username);
		mysqli_stmt_execute($stmt2);
		if (mysqli_stmt_affected_rows($stmt2)){
			session_start();
			$_SESSION['createFN'] = $firstname;
			header('Location: acc_created.php');
			mysqli_free_result($stmt2);
			
		}
		else {
			header('Location: acc_created.php');
		 }
		include 'includes/footer.php'; 
		exit; 	
	}// no errors 
	   
	} //isset
?>

<main class="w-full">
  <form method="post" action="register.php" class="max-w-md mx-auto">
    <fieldset class="p-4 bg-white  rounded-lg">
      <legend class="text-lg font-semibold text-center">Become a Registered User:</legend>
      <?php if ($errors) { ?>
        <h2 class="text-red-600 font-bold mb-4">Please fix the item(s) indicated.</h2>
      <?php } ?>

      <?php if ($errors['firstname']) echo "<h2 class=\"text-red-600 font-bold mb-4\">{$errors['firstname']}</h2>"; ?>
      <div class="mb-4">
        <label for="fn" class="block text-gray-700 font-semibold mb-2">First Name:</label>
        <input name="firstname" id="fn" type="text" class="border border-gray-300 p-2 w-full rounded-md" <?php if (isset($firstname)) {
          echo 'value="' . htmlspecialchars($firstname) . '"';
        } ?>>
      </div>

      <?php if ($errors['lastname']) echo "<h2 class=\"text-red-600 font-bold mb-4\">{$errors['lastname']}</h2>"; ?>
      <div class="mb-4">
        <label for="ln" class="block text-gray-700 font-semibold mb-2">Last Name:</label>
        <input name="lastname" id="ln" type="text" class="border border-gray-300 p-2 w-full rounded-md" <?php if (isset($lastname)) {
          echo 'value="' . htmlspecialchars($lastname) . '"';
        } ?>>
      </div>

      <?php 
      if ($errors['email']) echo "<h2 class=\"text-red-600 font-bold mb-4\">{$errors['email']}</h2>"; 
      if ($errors['exists']) echo "<h2 class=\"text-red-600 font-bold mb-4\">{$errors['exists']}</h2>"; 
      ?>
      <div class="mb-4">
        <label for="email" class="block text-gray-700 font-semibold mb-2">Email:</label>
        <input name="email" id="email" type="text" class="border border-gray-300 p-2 w-full rounded-md" <?php if (isset($email) && !$errors['email'] && !$errors['exists']) {
          echo 'value="' . htmlspecialchars($email) . '"';
        } ?>>
      </div>

      <div class="mb-4">
        <label for="username" class="block text-gray-700 font-semibold mb-2">Username:</label>
        <input name="username" id="username" type="text" class="border border-gray-300 p-2 w-full rounded-md" <?php if (isset($username) && !$errors['username'] && !$errors['username']) {
          echo 'value="' . htmlspecialchars($username) . '"';
        } ?>>
      </div>

      <?php if ($errors['pw']) echo "<h2 class=\"text-red-600 font-bold mb-4\">{$errors['pw']}</h2>";    
      if ($errors['pwmatch']) echo "<h2 class=\"text-red-600 font-bold mb-4\">{$errors['pwmatch']}</h2>"; 
      ?>
      <div class="mb-4">
        <label for="pw1" class="block text-gray-700 font-semibold">Password:</label>
                <input name="password1" class="border border-gray-300 p-2 w-full rounded-md" id="pw1" type="password">
       
			<p>
                <label for="pw2" class="block text-gray-700 font-semibold">Confirm Password: </label>
                <input name="password2" class="border border-gray-300 p-2 w-full rounded-md" id="pw2" type="password">
            </p>
         
           	<?php if ($errors['accepted']) echo "<h2 class=\"warning\">{$errors['accepted']}</h2>"; ?>
             <p>			  
                <input type="checkbox" name="terms" value="accepted" id="terms"
				     <?php if ($accepted) {
                                echo 'checked';
                            } ?>
				>
                <label for="terms">I accept the terms of using this website</label>
            </p>
            <button name="send" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Register
            </button>
		</fieldset>
        </form>
	</main>
						</div>


<?php include 'includes/footer.php'; ?>

