<?php 
$th_width = 80;
$th_height = 54;

if ($shortType=='gif') {
    $resource2 = imagecreatefromgif($destination);
}
elseif ($shortType=='png') {
    $resource2 = imagecreatefrompng($destination); 
}
else {
    $resource2 = imagecreatefromjpeg($destination);
}

$thumb = imagecreatetruecolor($th_width, $th_height); // Create a new blank image of the specified size.
imagecopyresampled($thumb, $resource2, 0, 0, 0, 0, $th_width, $th_height, $width, $height);

$new_destination = "images/thumbs/".$filename;// Adjust the path to include the thumbs folder.

if ($shortType == 'gif') {  
    imagegif($thumb, $new_destination);
}
elseif ($shortType == 'png') {
    imagepng($thumb, $new_destination);
}
else {
    imagejpeg($thumb, $new_destination);
}
?>
