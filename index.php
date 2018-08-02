<!DOCTYPE html>
<html lang="en">
<head>
     <?php
        require 'functions/functions.php';
        setHeader("Media Center")
     ?>
</head>
<body>
     
</body>
</html>

<?php
    include "classes/DBConnection.php";

    $db = new DBConnection("localhost", "root", "", "mediacenter");
    $db->connect();
    $db->prepare("SELECT * from users");
    $db->query();
    $lol = $db->getResults();
    print_r($lol);
?>