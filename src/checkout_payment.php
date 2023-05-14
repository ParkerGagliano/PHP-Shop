<?php //no header.php yet because page could be redirected at line 44
require '../secure_conn.php';
session_start();
if ((!isset($_SESSION['cart']))|| empty($_SESSION['cart']) || empty($_SESSION['email'])) {
		require 'includes/header.php';
		echo "<main><h2>You have reached this page in error</h2></main>";
		include 'includes/footer.php';
		exit;
	} else {
		if (isset($_POST['pay'])){
			$missing = array();
			$errors = array();
			$card_type= trim(filter_input(INPUT_POST, 'card_type', FILTER_VALIDATE_INT));
			if (empty($card_type)|| $card_type==0)
				$missing[] = 'card_type';
			else
				$valid_card = $card_type;
			
			$card_number= trim(filter_input(INPUT_POST, 'card_number', FILTER_SANITIZE_STRING));
			if (empty($card_number))
				$missing[] = 'card_number';
			elseif (strlen($card_number) != 16)
				$errors[] = 'card_number';
			else
				$valid_number = $card_number;
			
			$card_cvv = trim(filter_input(INPUT_POST, 'card_cvv', FILTER_SANITIZE_STRING));
			if (empty($card_cvv))
					$missing[] = 'card_cvv';
				elseif (strlen($card_cvv) != 3)
					$errors[] = 'card_cvv';
				else
					$valid_cvv=$card_cvv;
			
			$card_exp = trim(filter_input(INPUT_POST, 'card_exp', FILTER_SANITIZE_STRING));
			if (empty($card_exp))
					$missing[] = 'card_exp';
				elseif (strlen($card_exp) != 4)
					$errors[] = 'card_exp';
				else
					$valid_exp = $card_exp;
			
			if (!$missing && !$errors){  //Assume successful payment
				$_SESSION['payment']=TRUE;
				header("Location: checkout_complete.php");
				exit;
			}
		} // submit
	} //main else
	
require 'includes/header.php'; ?>
	<main class="mx-auto max-w-md py-8 px-4">
    <h2 class="text-2xl font-bold mb-4">Payment Information</h2>
    <h3 class="font-medium mb-6">We do not store your credit card information</h3>
    <?php if ($missing || $errors) { ?>
        <p class="text-red-500 mb-6">Please fix the item(s) indicated.</p>
    <?php } ?>
    <form action="checkout_payment.php" method="post">
        <p class="mb-4">
            <label for="ct" class="block font-medium mb-2">
                Card Type:
                <?php if ($missing && in_array('card_type', $missing)) { ?>
                    <span class="text-red-500 font-bold ml-2">Please select your card type</span>
                <?php } ?>
            </label>
            <select name="card_type" id="ct" class="border border-gray-300 rounded-md p-2 w-full">
                <option value="0">Card Type</option>
                <option value="1"<?php if(isset($valid_card) && $valid_card==1) echo "selected"; ?>>MasterCard</option>
                <option value="2"<?php if(isset($valid_card) && $valid_card==2) echo "selected"; ?>>Visa</option>
                <option value="3"<?php if(isset($valid_card) && $valid_card==3) echo "selected"; ?>>Discover</option>
                <option value="4"<?php if(isset($valid_card) && $valid_card==4) echo "selected"; ?>>American Express</option>
            </select>
        </p>
        <p class="mb-4">
            <label for="cn" class="block font-medium mb-2">
                Card Number:
                <?php if ($missing && in_array('card_number', $missing)) { ?>
                    <span class="text-red-500 font-bold ml-2">Please enter your card number</span>
                <?php }
                if ($errors && in_array('card_number', $errors)) { ?>
                    <span class="text-red-500 font-bold ml-2">Please enter a valid credit card number</span>
                <?php } ?>
            </label>
            <input type="text" id="cn" name="card_number" class="border border-gray-300 rounded-md p-2 w-full"
                   <?php if (isset($valid_number)){
                       echo 'value="'.htmlspecialchars($valid_number). '"'; }?>>
        </p>
        <p class="mb-4">
            <label for="cvv" class="block font-medium mb-2">
                CVV:
                <?php if ($missing && in_array('cvv', $missing)) { ?>
                    <span class="text-red-500 font-bold ml-2">Please enter the CVV code</span>
                <?php }
                if ($errors && in_array('cvv', $errors)) { ?>
                    <span class="text-red-500 font-bold ml-2">Please enter the 3-digit CVV code from the back of your card</span>
                <?php } ?>
            </label>
            <input type="text" id="cvv" name="card_cvv" class="border border-gray-300 rounded-md p-2 w-full"
                   <?php if(isset($valid_cvv)) echo 'value="'. htmlspecialchars($valid_cvv).'"'; ?>>
        </p>
        <p class="mb-4">
            <label for="exp" class="block font-medium mb-2">Expiration:
			<?php if ($missing && in_array('card_exp', $missing)|| $errors && in_array('card_exp', $errors)) { ?>
							<span class="warning">Please enter the expiration date as MMYY</span>
						<?php }  ?>
			</label>
			<input class="border border-gray-300 rounded-md p-2 w-full" type="text" name="card_exp" placeholder="MMYY" id="exp"
				  <?php if(isset($valid_exp)) echo 'value="'. htmlspecialchars($valid_exp).'"'; ?>>  
		</p>
		<br>
        <input class="border border-gray-300 rounded-md p-2 w-full" type="submit" name = "pay" value="Place Order">&nbsp;&nbsp;
    </form>
</main>
            </div>
<?php include 'includes/footer.php'; ?>