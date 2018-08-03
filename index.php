<!DOCTYPE html>
<html lang="en">
<head>
     <?php
        require 'requireAll.php';
        setHeader("Media Center")
     ?>
</head>
<body>
     <?php
        $db = new DBConnection("localhost", "root", "", "mediacenter");
        $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $db->bindParam(":user_id", 1, PDO::PARAM_INT);
        $lol = $db->query();
        print_r($lol);
     ?>
</body>
</html>

<?php

?>