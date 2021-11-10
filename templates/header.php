<?php require_once './config.php' ?>
<!DOCTYPE html>
    <html>
        <head>
            <link rel="stylesheet" type="text/css" href="<?php echo SITE_URL . '/style.css' ?>">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        </head>
        <body>
            <header>
                <div class="nav">
                    <a class="active" href="<?php echo SITE_URL ?>">Home</a>
                    <a class="logout" href="<?php echo SITE_URL . '/logout.php' ?>">Logout</a>
                </div>
            </header>
        