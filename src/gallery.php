


<?php 
    error_reporting(E_ALL);
	require 'includes/header.php'; 
    require_once '../../mysqli_connect.php';

    function shortTitle ($title){
		$title = substr($title, 0, -4); 
		$title = str_replace('_', ' ', $title);
		$title = ucwords($title); 
		return $title;
	}

    $COLS = 2;
    $ROWS = 2;
    if (isset($_GET['image']))
        $img_num = filter_var(($_GET['image']),
        FILTER_SANITIZE_NUMBER_INT);
        else
        $img_num = 1; 
    if (isset($_GET['page']))
        $page_num = filter_var(($_GET['page']),
        FILTER_SANITIZE_NUMBER_INT);
        else
        $page_num = 1; 
    $img_per_page = 6;
    $offset = ($page_num - 1) * $img_per_page;
    $sql = "SELECT * FROM proj_images LIMIT $offset, $img_per_page;";
    $result = mysqli_query($dbc, $sql);

    $sql = "SELECT * FROM proj_images;";
    $original = mysqli_query($dbc, $sql);
    if (!$result) {
        $error = mysqli_stmt_error();
    } else {
        $numRows = mysqli_num_rows($result);
    }
   
    $sql = "SELECT COUNT(*) FROM proj_images;";
    $numcols = mysqli_query($dbc, $sql);
    if (!$numcols) {
        $error = mysqli_stmt_error();
    } else {
        $numcols = mysqli_fetch_array($numcols, MYSQLI_NUM);
        $numcols = $numcols[0];
    }

    $current = $img_per_page* $page_num;
    if ($current > $numcols) {
        $current = $numcols;
    }

?>



	<main>
        <h2>Japan Journey</h2>
		<?php echo" <p id='picCount'>Displaying $offset to $current of $numcols</p> "?>
        <section id="gallery">
        <table id="thumbs">
    <tr>
        <?php
        $count = 1;
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $real_page = $count + (($page_num - 1) * $img_per_page);
            $title = $row['filename'];
            $image = "images/thumbs/$title";
            echo "<td><a href=\"gallery.php?image=$real_page&page=$page_num\"><img src=\"$image\" alt=\"Japan thumbnail image\" width=\"80\" height=\"54\"></a></td>";
            if($count % 2 == 0) {
                echo "</tr><tr>";
            }
            $count++;
        }
        ?>
    </tr>
    <?php
    $count = 1;
    while($row = mysqli_fetch_array($original, MYSQLI_ASSOC)){
        if ($count == $img_num) {
            $main_img = $row['filename'];
            $main_caption = $row['caption'];
        }
        $count++;
    }
    ?>
    <?php if ($page_num > 1) {
        $prev_page = $page_num - 1;
        echo "<a href=\"gallery.php?page=$prev_page&image=$img_num\">&lt;&lt;Previous</a>";
    }
    if ($current < $numcols) {
        $next_page = $page_num + 1;
        echo "<a href=\"gallery.php?page=$next_page&image=$img_num\">Next&gt;&gt;</a>";
    }
    ?>

</table>
            <figure id="main_image">
                
                <img <?php echo "src='images/$main_img' alt=''shortTitle($main_img)"?>>
                <figcaption><?php echo "$main_caption"?></figcaption>
            </figure>
        </section>
    </main>
    </div>

<?php include 'includes/footer.php'; ?>


