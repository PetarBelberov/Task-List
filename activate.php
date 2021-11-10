<?php
require_once './config.php';
include ('server.php');

if (isset($_GET["id"])) {
    
    $hash = ($_GET["id"]);
    
    $user12 = mysqli_query($db, "SELECT * FROM users WHERE hash='$hash'");
    $userColumns = mysqli_fetch_row($user12);
    $userId = $userColumns[0];
    try {
        if (count($userColumns) > 0) {
            
            if ($userColumns[4] == "approved") {
                $msg = "Your account has already been activated.";
                $msgType = "info";
            } 
            else {
                $sql = "UPDATE `users` SET  `status` =  'approved' WHERE `id` = $userId";
                $stmt = $DB->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();
                $msg = "Your account has been activated. Thank you for registering with us.";
                $msgType = "success";
            }
        } else {
            $msg = "No account found";
            $msgType = "warning";
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}
include_once 'templates/activate.php';
?>
