<?php //This is the login page for registered users
require '../secure_conn.php';
require 'includes/header.php';
if (isset($_POST['send'])) {
	
	//display php errors

	$errors = array();
	
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
	
	$password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
	if (empty($password))
		$errors['pw']= "A password is required";

	while (!$errors){ 
		require_once ('../../mysqli_connect.php'); // Connect to the db.
		//Query for email
		$sql = "SELECT firstname, email, pass, username, isadmin FROM proj_users WHERE email = ?";
		$stmt = mysqli_prepare($dbc, $sql);
		mysqli_stmt_bind_param($stmt, 's', $email);
		mysqli_stmt_execute($stmt);
		$result=mysqli_stmt_get_result($stmt);
		$rows = mysqli_num_rows($result);
		if ($rows==0) 
			$errors['no_email'] = "That email address wasn't found";
		else { // email found, validate password
			$result2=mysqli_fetch_assoc($result); //convert the result object pointer to an associative array 
			$pw_hash=$result2['pass'];
			if (password_verify($password, $pw_hash )) { //passwords match
				$firstName = $result2['firstname'];
				$email = $result2['email'];
                $username = $result2['username'];
				$admin = $result2['isadmin'];
				session_start();
				$_SESSION['firstname'] = $firstName;
				$_SESSION['email'] = $email;
				$_SESSION['username'] = $username;
				$_SESSION['admin'] = $admin;
				header('Location: logged_in.php');
				exit;
			}
			else {
				$errors['wrong_pw'] = "That isn't the correct password";
			}
		} 
	   } // end while 	
} //end isset $_POST['send']
?>
	<main class="w-full ">

    <form method="post" action="login.php" class="w-full max-w-xs mx-auto">
        <fieldset>
            <legend class="text-lg font-bold mb-2 text-center">Registered Users Login</legend>
            <?php if ($errors): ?>
                <h2 class="text-red-600 font-bold mb-4">Please fix the item(s) indicated.</h2>
            <?php endif; ?>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input name="email" id="email" type="text" class="w-full px-3 py-2 border rounded <?php echo ($errors['email'] || $errors['no_email']) ? 'border-red-500' : 'border-gray-400'; ?>" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                <?php if ($errors['email']): ?>
                    <p class="text-red-500"><?php echo $errors['email']; ?></p>
                <?php endif; ?>
                <?php if ($errors['no_email']): ?>
                    <p class="text-red-500"><?php echo $errors['no_email']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                <input name="password" id="password" type="password" class="w-full px-3 py-2 border rounded <?php echo ($errors['pw'] || $errors['wrong_pw']) ? 'border-red-500' : 'border-gray-400'; ?>" required>
                <?php if ($errors['pw']): ?>
                    <p class="text-red-500"><?php echo $errors['pw']; ?></p>
                <?php endif; ?>
                <?php if ($errors['wrong_pw']): ?>
                    <p class="text-red-500"><?php echo $errors['wrong_pw']; ?></p>
                <?php endif; ?>
            </div>
            <button name="send" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Login
            </button>
        </fieldset>
    </form>
</main>

			</div>
<?php include './includes/footer.php'; ?>
