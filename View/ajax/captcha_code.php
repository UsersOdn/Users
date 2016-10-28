<?php
    session_start();
    $random_alpha = md5(rand());
    $captcha_code = substr($random_alpha, 0, 4);
    $_SESSION["captcha_code"] = $captcha_code;
    $target_layer = imagecreatetruecolor(250,50);
    $captcha_background = imagecolorallocate($target_layer, 250 , 250, 250);
    imagefill($target_layer,10,10,$captcha_background);
    $captcha_text_color = imagecolorallocate($target_layer, 0, 0, 0);
    imagestring($target_layer, 5, 110, 17, $captcha_code, $captcha_text_color);
    header("Content-type: image/jpeg");
    imagejpeg($target_layer);
?>