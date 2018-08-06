<?php
include_once 'requireAll.php';

$logger = new Logger();
$db = new DBConnection($config["DB_host"], $config["DB_user"], $config["DB_password"], $config["DB_databaseName"]);

if(! (isset($_POST['token'], $_POST['username'], $_FILES['image']) && !empty($_FILES['image']['name']))) {
    exit('Invalid or missing required paramaters.');
}

$db->prepare("SELECT * FROM `users` WHERE `user_name` = :username AND `user_token` = :token");
$db->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
$db->bindParam(':token', $_POST["token"], PDO::PARAM_STR);

$row = $db->query();

if (! $row) {
    exit('Wrong Username Or Token!');
}

$target_file = 'images/galery/' . round(microtime(true)) . '.' . strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));                  
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if (! in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
    exit('Invalid image format given!');
}

if (! move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    exit('Failed to upload the file successfully, could\'t move the file to the permant folder.');
}

$db->prepare("INSERT INTO images (`img_path`, `img_userId`, `img_uploadDate`) VALUES (:dir, :userId, NOW())");
$db->bindParam(":dir", $target_file, PDO::PARAM_STR);
$db->bindParam(":userId", $row["user_id"], PDO::PARAM_STR);
$db->query();

echo generateLink($target_file);

$logger->info("New Image uploaded:\n\t\t\t\t\t\t\t  User: {$_POST['username']}\n\t\t\t\t\t\t\t  Path: ${target_file}");