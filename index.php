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
        $db = new DBConnection("localhost", $config["DB_user"], $config["DB_password"], $config["DB_databaseName"]);
     ?>
</body>
</html>

<?php

?>