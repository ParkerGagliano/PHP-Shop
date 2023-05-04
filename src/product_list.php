<?php 
	require './includes/header.php';
	require_once '../../mysqli_connect.php';
	$sql = 'SELECT * FROM proj_images';
	$result = mysqli_query($dbc, $sql);
	if (!$result) {
		echo "<p class='text-red-500'>We are unable to process your request at this time. Please try again later.</p></div>";
		include './includes/footer.php'; 
        
		exit;
	}
	
	function shortTitle ($title){
        $title = substr($title, 0, -4);
        $title = str_replace('_', ' ', $title);
        $title = ucwords($title);
        $title = rtrim($title, ".");
        return $title;
    }
		
	?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
	<h2 class="text-3xl font-bold mb-4 text-center">All currently listed products</h2>

	<?php if (isset($_SESSION['cart'])) {
		echo '<a href="cart.php" class="mx-auto inline-block text-sm px-4 py-2 leading-none border rounded text-white bg-blue-500 hover:bg-blue-600 mt-4 mb-15">View Cart</a>';
	} ?>
	<table class="w-full border-collapse border border-gray-300 mt-10">
		<thead>
			<tr class="bg-gray-200 text-gray-700">
				<th class="text-left py-2 px-4">Title</th>
				<th class="text-center py-2 px-4">Image</th>
				<th class="text-center py-2 px-4">View Details</th>
			</tr>
		</thead>
		<tbody>
			<?php while($row = mysqli_fetch_assoc($result)) { ?> 
			<tr class="border-b border-gray-300">
				<td class="text-gray-700 py-2 px-4"><?php echo shortTitle($row['filename']); ?></td>
				<td class="py-2 px-4 text-center"><img src="./images/thumbs/<?php echo $row['filename']; ?>" alt="<?php echo $row['title']; ?>" class="mx-auto"></td>
				<td class="py-2 px-4 text-center"><form action="product_details.php" method="get"> 
						<input type="hidden" name="image_id" value="<?php echo $row['image_id']; ?>">
						<button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mx-auto">View Details</button>
					</form></td> 
			</tr>
			<?php } //end while loop ?>
		</tbody>
	</table>
</main>
            </div>
<?php include './includes/footer.php'; ?>
