<?php 

define('MAX_SIZE', 350);  //350x350 is the biggest size for a "main" image on the gallery page
require 'includes/header.php';
//show errors


if(isset($_POST['submit'])) {
	if(isset($_FILES['site_img'])){
		$filename= $_FILES['site_img']['name'];
		$destination = $_SERVER['CONTEXT_DOCUMENT_ROOT']."/finalproject/images/$filename";
		if(move_uploaded_file($_FILES['site_img']['tmp_name'], $destination )){
			$img = getimagesize("images/$filename");
			$width= $img[0];
			$height= $img[1];
			$type = $img['mime'];
			if ($width <= MAX_SIZE && $height <= MAX_SIZE) {
				$ratio = 1;
			} elseif ($width > $height) {
				$ratio = MAX_SIZE/$width;
			} else {
				$ratio = MAX_SIZE/$height;
			}
			$new_w = round($width * $ratio);
			$new_h = round($height * $ratio);
			$shortType = substr($type, 6);  //strip off MIME: image/
			if ($shortType=='gif') {
				$resource = imagecreatefromgif($destination);
			}elseif ($shortType=='png') {
				$resource = imagecreatefrompng($destination); 
			} else {
				$resource = imagecreatefromjpeg($destination);
			}
			$resized = imagecreatetruecolor($new_w, $new_h);  //Create a new blank image of the specified size.
			imagecopyresampled($resized, $resource, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
			$new_destination = "images/$filename";
			if ($shortType == 'gif') {
				imagegif($resized, $new_destination);
			} elseif ($shortType == 'png') {
				imagepng($resized, $new_destination);
            } else {
				imagejpeg($resized, $new_destination);
			}
			
			//Send image data to db:
			if(isset($_POST['caption']))
				$caption = filter_var(trim($_POST['caption']), FILTER_SANITIZE_STRING);
			else
				$caption = NULL;

			if(isset($_POST['price']))
				$price = filter_var(trim($_POST['price']), FILTER_SANITIZE_STRING);
			else
				$price = NULL;

			if(isset($_POST['details']))
				$details = filter_var(trim($_POST['details']), FILTER_SANITIZE_STRING);
			else
				$details = NULL;
			
			require_once ('../../mysqli_connect.php'); // Connect to the db.
			$sql = "INSERT into proj_images(filename, caption, price, details) VALUES (?, ?, ?, ?)";
			$stmt = mysqli_prepare($dbc, $sql);
			mysqli_stmt_bind_param($stmt, 'ssis', $filename, $caption, $price, $details);
			include('includes/create_thumb.php');
			mysqli_stmt_execute($stmt);
			if (mysqli_stmt_affected_rows($stmt)){
				echo "<main><h2>We have saved the new item</h2>";
				echo "<img src = 'images/$filename'></main>";
				mysqli_free_result($stmt);
			}
			else {
				echo"<main><h3>There was a problem saving to the database</h3></main>";
                echo"</div>";
				include 'includes/footer.php';

				exit;
			}
		}
		elseif ($_FILES['site_img']['error'] > 0) {
			echo '<p class="error">The file could not be uploaded because: <strong>';

			// Print a message based upon the error.
			switch ($_FILES['site_img']['error']) {
				case 1:
					echo 'The file exceeds the upload_max_filesize setting in php.ini.';
					break;
				case 2:
					echo 'The file exceeds the MAX_FILE_SIZE setting in the HTML form.';
					break;
				case 3:
					echo 'The file was only partially uploaded.';
					break;
				case 4:
					echo 'No file was uploaded.';
					break;
				case 6:
					echo 'No temporary folder was available.';
					break;
				case 7:
					echo 'Unable to write to the disk.';
					break;
				case 8:
					echo 'File upload stopped.';
					break;
				default:
					echo 'A system error occurred.';
					break;
			} // End of switch.
			echo '</strong></p>';
		} // End of error IF.
		else {
			echo"<main><h3>Some unknown error has occurred.</h3></main>";
			//exit;
		}
	} //isset $_FILES
    echo"</div>";
	include 'includes/footer.php';
	//release the uploaded file resource
	if(file_exists($_FILES['site_img']['tmp_name']) && is_file($_FILES['site_img']['tmp_name']))
				unlink($_FILES['site_img']['tmp_name']);
	exit;
}
?>
	<main class="mx-auto">
    <h2 class="text-xl font-bold mb-4">Admin Upload Site Products</h2>
		<?php
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_FILES['site_img'])) {
  $caption = $_POST['caption'];
  $file_name = $_FILES['site_img']['name'];
  $file_size = $_FILES['site_img']['size'];
  $file_tmp = $_FILES['site_img']['tmp_name'];
  $file_type = $_FILES['site_img']['type'];


  if($file_size > 2097152){
    echo 'File size must be less than 2 MB';
  }

  move_uploaded_file($file_tmp,"images/".$file_name);
  echo "Upload successful!";
}

?>

<form action="site_images.php" method="post" enctype="multipart/form-data" class="w-full max-w-md mx-auto">
  <div class="mb-4">
    <label for="site_img" class="block text-gray-700 font-bold mb-2">Image:</label>
    <input type="file" name="site_img" id="site_img" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
  </div>
  <div class="mb-4">
    <label for="caption" class="block text-gray-700 font-bold mb-2">Caption:</label>
    <input type="text" name="caption" id="caption" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
  </div>
  <div class="mb-4">
    <label for="price" class="block text-gray-700 font-bold mb-2">Price:</label>
    <input type="number" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
  </div>
  <div class="mb-4">
    <label for="details" class="block text-gray-700 font-bold mb-2">Description:</label>
    <textarea name="details" id="details" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
  </div>
  <div class="mb-4">
    <input type="submit" name="submit" value="Submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
  </div>
</form>

		

	</main>
</div>
	<?php // Include the footer and quit the script:
	include ('includes/footer.php'); 
	?>
	