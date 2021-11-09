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
			header('location: ' . $_SERVER['REQUEST_URI']);
		}
	}

    ?>

	<form method="post" action="" class="input_form">
		<?php if (isset($errors)) { ?>
			<p><?php echo $errors; ?></p>
		<?php } ?>
	
		<input type="hidden" name="id" value="">
		
		<?php if (isset($update)) : ?>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="text" name="task" class="task_input" placeholder="Name" value="<?php echo $task; ?>">
			<input type="text" name="description" class="description_input" placeholder="Description" value="<?php echo $description; ?>">
			<button type="submit" name="update-task" id="update_task_btn" class="button"><i class="fa fa-edit"></i></button>
		<?php else: ?>
			<input type="text" name="task" class="task_input" placeholder="Name" >
			<input type="text" name="description" class="description_input" placeholder="Description">
			<button type="submit" name="submit-task" id="add_btn" class="button"><i class="fa fa-plus"></i></button>
		<?php endif ?>
	</form>


