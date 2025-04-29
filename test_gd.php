<?php
header("Content-type: image/png");
$image = imagecreatetruecolor(200, 200);
$background_color = imagecolorallocate($image, 255, 255, 255);  // white background
imagefilledrectangle($image, 0, 0, 200, 200, $background_color);

$text_color = imagecolorallocate($image, 0, 0, 0);  // black text
imagestring($image, 4, 50, 90, 'GD Test', $text_color);

imagepng($image);
imagedestroy($image);
?>

