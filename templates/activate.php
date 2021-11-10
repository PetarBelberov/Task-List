
<?php include_once "templates/header.php"; ?>

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

<?php include_once "templates/footer.php"; ?>