<?php 

session_start(); 
require_once './config.php';

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
  
    header('location: login.php');
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}

// connect to database
$db = mysqli_connect("localhost", "root", "", "todo");

$username = $_SESSION['username'];

$user = mysqli_query($db, "SELECT list_id FROM tasks");

$user = mysqli_query($db, "SELECT id FROM users WHERE username='$username'");
$userColumns = mysqli_fetch_row($user);
$userId = $userColumns[0];

$lists = mysqli_query($db, "SELECT id, name FROM lists WHERE user_id='$userId'");
$lists = mysqli_fetch_all($lists);

// add list
if (isset($_POST['submit-list'])) {
					
    if (empty($_POST['list'])) {
        $errors = "You must fill in the list";
    }
    else {
        $list = $_POST['list'];
        $sql = "INSERT INTO lists (name, user_id) VALUES ('$list', '$userId')";
        
        mysqli_query($db, $sql);
        
        header('location: index.php');
    }
}

include_once 'templates/index.php';
?>

