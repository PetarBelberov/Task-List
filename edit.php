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


    $listQuery = mysqli_query($db, "SELECT id,name FROM lists WHERE id='$lastUriSegment'");
	$listName = mysqli_fetch_row($listQuery);
	$idList = $listName[0];
	$listName = $listName[1];
  

    // edit task GET Request
	if (isset($_GET['edit_task'])) {
		if  ($taskUserId == $listId) {
            $id = $_GET['edit_task'];
            $update = true;
            $record = mysqli_query($db, "SELECT task, description FROM tasks WHERE id=$id");

            $task_arr = mysqli_fetch_array($record);
			$task = $task_arr[0];
			$description = $task_arr[1];
		}
		else {
			function exception_handler($exception) {
				echo "Uncaught exception: " , $exception->getMessage(), "\n";
			}
			set_exception_handler('exception_handler');

			throw new Exception('Uncaught Exception');
		}
	}
	
	// edit task POST Request
	if (isset($_POST['update-task'])) {
		$id = $_POST['id'];
		$task = $_POST['task'];
		$description = $_POST['description'];

		mysqli_query($db, "UPDATE tasks SET task = '$task', description = '$description' WHERE id = $id");
		
		header('location: ../lists.php/' . $idList);
	}
	include_once 'templates/edit.php';
    ?>