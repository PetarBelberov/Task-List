<?php

session_start();

require_once './config.php';
include('errors.php');
$errors = array();
$verificationMessages = array();

// Prevent user from accessing the login/register pages
if(isset($_SESSION['username'])){
    header('location: index.php');
}

if (isset($_POST["reg_user"])) {
    require_once "phpmailer/class.phpmailer.php";
    
    $name = trim($_POST["username"]);
    $pass = trim($_POST["password_1"]);
    $pass2 = trim($_POST["password_2"]);
    $email = trim($_POST["email"]);
    $hash = trim(bin2hex(openssl_random_pseudo_bytes(16)));
    if (empty($name)) { array_push($errors, "Username is required"); }
    if (empty($pass)) { array_push($errors, "Password is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    
    $sql = "SELECT COUNT(*) AS count from users where email = :email_id";
    try {
        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":email_id", $email);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        if ($result[0]["count"] > 0) {
            $msg = "Email already exist";
            $msgType = "warning";
            
        } elseif ($pass != $pass2) {
            array_push($errors, "Passwords don't match");
        }
        
        elseif (count($errors) == 0) {
            $sql = "INSERT INTO `users` (`username`, `password`, `email`, `hash`) VALUES " . "( :name, :pass, :email, :hash)";
            array_push($verificationMessages, "Check your email to activate your account");

            $stmt = $DB->prepare($sql);
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":pass", password_hash($pass, PASSWORD_BCRYPT));
            $stmt->bindValue(":email", $email);
            $stmt->bindValue(":hash", $hash);
           
            $stmt->execute();
            $result = $stmt->rowCount();
            
            if ($result > 0) {
                
                $lastID = $DB->lastInsertId();
                
                $message = '<html><head>
                <title>Email Verification</title>
                </head>
                <body>';
                $message .= '<h1>Hi ' . $name . '!</h1>';
                $message .= '<p><a href="' . SITE_URL . '/activate.php?id=' . $hash . '">CLICK TO ACTIVATE YOUR ACCOUNT</a>';
                $message .= "</body></html>";
                
                
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Port = 2525;
                $mail->Username = 'edb73ed32e8f83';
                $mail->Password = '671341dd0f0a45'; // set the SMTP port for the GMX server
                

                $mail->SetFrom('mailtrap', 'Task List Verification');
                $mail->AddAddress($email);
                
                $mail->Subject = trim("Email Verification - ToDo List");
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
                array_push($verificationMessages, "Failed to create User");
            }
        }
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}


// LOGIN USER
include('errors.php');
$errors = array();

$db = mysqli_connect("localhost", "root", "", "todo");

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
    
    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    
    
    if (count($errors) == 0) {
        $query = "SELECT * FROM users WHERE username='$username' AND status='approved'";
        $results = mysqli_query($db, $query);
        
        if (mysqli_num_rows($results) == 1 && $verifyPass) {
            $_SESSION['username'] = $username;
            header('location: ' . 'index.php');
        }
        else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

