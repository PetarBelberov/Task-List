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

?>
<?php if ($msg <> "") { ?>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
</head>
  <div class="alert alert-dismissable alert" id="alertActivate"<?php echo $msgType; ?>>
  <?php include ('server.php'); ?>
    <p><?php echo $msg; ?></p>
    <?php if ($msgType == "success") { ?>
        <a href="<?php echo SITE_URL . '/login.php '?>">You can login to your account</a>
   <?php } ?> 
  </div>
<?php } ?>