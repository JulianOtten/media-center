<?php
function setHeader($title){
    echo '<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>'.$title.'</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta name="author" content="Julian Otten">
    <meta property="og:site_name" content="Media Center">
    <meta property="og:title" content="Media Center">
    <meta property="og:description" content="Automate the sharing of screenshots and store the images!">
    <meta property="og:image" content="images/og-image.png">
    <meta property="og:type" content="website">
    <meta name="keywords" content="HTML,CSS,PHP,JavaScript,Media Center, Screenshots">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <script src="js/index.js"></script>';
}