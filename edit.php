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
  

    // edit task GET Request
	if (isset($_GET['edit_task'])) {
		if  ($taskUserId == $listId) {
            $id = $_GET['edit_task'];
            $update = true;
            $record = mysqli_query($db, "SELECT task FROM tasks WHERE id=$id");

            $task = mysqli_fetch_array($record);
            $task = $task['task'];
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
		
		mysqli_query($db, "UPDATE tasks SET task = '$task' WHERE id = $id");
		
		header('location: ' . $_SERVER['REQUEST_URI']);
	}

    ?>
    <!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL . '/style.css' ?>">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<title>ToDo List Application PHP and MySQL</title>
</head>
<body>
	<header>
		<div class="nav">
			<a class="active" href="<?php echo SITE_URL ?>">Home</a>
			<a href="TODO">Create List</a>
			<a class="logout" href="<?php echo SITE_URL . '/logout.php' ?>">Logout</a>
		</div>
	</header>
<div class="header">
	<h2><?php echo $listName ?></h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?> 
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>
</div>

	<form method="post" action="" class="input_form">
        <?php if (isset($errors)) { ?>
            <p><?php echo $errors; ?></p>
        <?php } ?>
	
		<input type="hidden" name="id" value="">
		
		<?php if (isset($update)) : ?>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="text" name="task" class="task_input" value="<?php echo $task; ?>">
			<button type="submit" name="update-task" id="update_task_btn" class="button"><i class="fa fa-edit"></i></button>
		<?php else: ?>
			<input type="text" name="task" class="task_input">
			<button type="submit" name="submit-task" id="add_btn" class="button"><i class="fa fa-plus"></i></button>
		<?php endif ?>
	</form>
</body>
</html>