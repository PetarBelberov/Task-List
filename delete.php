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


    $listQueryName = mysqli_query($db, "SELECT name FROM lists WHERE id='$lastUriSegment'");
	$listName = mysqli_fetch_row($listQueryName);
	$listName = $listName[0];


    // delete task
	if (isset($_GET['del_task'])) {
		if  ($userId == $taskUserId) {
			$id = $_GET['del_task'];
			
			mysqli_query($db, "DELETE FROM tasks WHERE id=$id");
			header('location: ' . SITE_URL . '/lists.php/' . $lastUriSegment);
		}
		else {
			function exception_handler($exception) {
				echo "Uncaught exception: " , $exception->getMessage(), "\n";
			}
			set_exception_handler('exception_handler');

			throw new Exception('Uncaught Exception');
		}
	}