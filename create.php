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
 
    // initialize errors variable
	$errors = "";

	// connect to database
	$db = mysqli_connect("localhost", "root", "", "todo");

	$username = $_SESSION['username'];

    // Get Last URL Segment
    $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $lastUriSegment = array_pop($uriSegments);    

	$listQueryId = mysqli_query($db, "SELECT id FROM lists WHERE id='$lastUriSegment'");
	$listId = mysqli_fetch_row($listQueryId);
	$listId = $listId[0];
    
    $listQueryName = mysqli_query($db, "SELECT name FROM lists WHERE id='$lastUriSegment'");
	$listName = mysqli_fetch_row($listQueryName);
	$listName = $listName[0];
  
	$user = mysqli_query($db, "SELECT id, username, email FROM users WHERE username='$username'");
	$userColumns = mysqli_fetch_row($user);
	$userId = $userColumns[0];
	
	
	$tasks = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM tasks WHERE list_id='$listId'"));

	$taskUserId = null;
	foreach	($tasks as $t) {
		$taskUserId = $t[2];	
	}
	
    // add task
	if (isset($_POST['submit-task'])) {
				
		if (empty($_POST['task'])) {	
			$errors = "You must fill in the task";
		}
		else {
			$task = $_POST['task'];
			$description = $_POST['description'];
			$sql = "INSERT INTO tasks (task, list_id, description, status) VALUES ('$task', '$listId',  '$description', 'undone')";
			
			mysqli_query($db, $sql);
			header('location: ' . SITE_URL . '/lists.php/' . $listId);
		}
	}

include_once 'templates/create.php';
?>
	


