<?php
    include_once 'requireAll.php';
    $db = new DBConnection($config["DB_host"], $config["DB_user"], $config["DB_password"], $config["DB_databaseName"]);
    $logger = new Logger();

    if(isset($_POST["token"]) && isset($_POST["username"]) && !empty($_FILES["image"]["name"])){
        $target_dir = "images/galery/";
        $db->prepare("SELECT * FROM users WHERE user_name = :username AND user_token = :token");
        $db->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
        $db->bindParam(':token', $_POST["token"], PDO::PARAM_STR);
        if($row = $db->query()){
            $newfilename = round(microtime(true)) . '.' . strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));      
            $target_file = $target_dir . $newfilename;                  
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $file_dir = str_replace('..', '.', $target_file);
                    $db->prepare("INSERT INTO images (`img_path`, `img_userId`, `img_uploadDate`) VALUES (:dir, :userId, NOW())");
                    $db->bindParam(":dir", $target_file, PDO::PARAM_STR);
                    $db->bindParam(":userId", $row["user_id"], PDO::PARAM_STR);
                    $db->query();
                    echo generateLink($target_file);
                    $logger->info("New Image uploaded:\n
                    \tUser:".$_POST["username"]."\n
                    \tPath: ". $target_file);
                } else {
                    exit();
                }
            } else {
                exit();
            }
        } else {
            echo ("Wrong Username Or Token!");
            exit();
        }
    }
?>