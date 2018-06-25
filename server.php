<?php

session_start();

require_once './config.php';
if (isset($_POST["reg_user"])) {
    require_once "phpmailer/class.phpmailer.php";
    
    $name = trim($_POST["username"]);
    $pass = trim($_POST["password_1"]);
    $email = trim($_POST["email"]);
    $sql = "SELECT COUNT(*) AS count from users where email = :email_id";
    try {
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":email_id", $email);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if ($result[0]["count"] > 0) {
            $msg = "Email already exist";
            $msgType = "warning";
        } else {
            $sql = "INSERT INTO `users` (`username`, `password`, `email`) VALUES " . "( :name, :pass, :email)";
            $stmt = $DB->prepare($sql);
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":pass", password_hash($pass, PASSWORD_BCRYPT));
            $stmt->bindValue(":email", $email);
            $stmt->execute();
            $result = $stmt->rowCount();
            
            if ($result > 0) {
                
                $lastID = $DB->lastInsertId();
                
                $message = '<html><head>
                <title>Email Verification</title>
                </head>
                <body>';
                $message .= '<h1>Hi ' . $name . '!</h1>';
                $message .= '<p><a href="'.SITE_URL.'activate.php?id=' . base64_encode($lastID) . '">CLICK TO ACTIVATE YOUR ACCOUNT</a>';
                $message .= "</body></html>";
                
                // php mailer code starts
                $mail = new PHPMailer(true);
                $mail->IsSMTP(); // telling the class to use SMTP
                
                $mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
                $mail->SMTPAuth = true;                  // enable SMTP authentication
                $mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
                $mail->Host = "mail.gmx.com";      // sets GMAIL as the SMTP server
                $mail->Port = 465;                   // set the SMTP port for the GMAIL server
                
                $mail->Username = 'petar_belberov@gmx.com';
                $mail->Password = 'sWink*mu_Emakq23101';
                
                $mail->SetFrom('petar_belberov@gmx.com', 'Petar Belberov');
                $mail->AddAddress($email);
                
                $mail->Subject = trim("Email Verifcation - www.thesoftwareguy.in");
                $mail->MsgHTML($message);
                
                try {
                    $mail->send();
                    $msg = "An email has been sent for verfication.";
                    $msgType = "success";
                } catch (Exception $ex) {
                    $msg = $ex->getMessage();
                    $msgType = "warning";
                }
            } else {
                $msg = "Failed to create User";
                $msgType = "warning";
            }
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

    //
    
// LOGIN USER
include('errors.php');
$errors = array();

    $db = mysqli_connect('localhost', 'root', '', 'todo');

if (isset($_POST['login_user'])) {

    // receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    
    $usernameData = mysqli_query($db,"SELECT password FROM users WHERE username='$username'");
    $usernameData = mysqli_fetch_row($usernameData);
    $usernameData = $usernameData[0];
    
    $passwordHash= mysqli_query($db,"SELECT password FROM users WHERE username='$username'");
    $passwordHash = mysqli_fetch_row($passwordHash);
    $passwordHash = $passwordHash[0];
    
    $verifyPass = password_verify($password, $passwordHash);
    //$userId = $userColumns[0];
    //$status = mysqli_real_escape_string($db, $_POST['status']);
    
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    
    
    if (count($errors) == 0) {
        //$password = password_hash($password, PASSWORD_DEFAULT);//encrypt the password before saving in the database
        $query = "SELECT * FROM users WHERE username='$username' AND status='approved'";
        $results = mysqli_query($db, $query);
        
        if (mysqli_num_rows($results) == 1 && $verifyPass) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }
        else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}
