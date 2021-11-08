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
			$sql = "INSERT INTO tasks (task, list_id, status) VALUES ('$task', '$listId', 'undone')";
			
			mysqli_query($db, $sql);
			header('location: ' . $_SERVER['REQUEST_URI']);
		}
	}
	
	// delete task
	if (isset($_GET['del-task-' . $tasksRows])) {
		if  ($userId == $taskUserId) {
	    $id = $_GET['del_task'];
	    
	    mysqli_query($db, "DELETE FROM tasks WHERE id=$id");
	    header('location: index.php');
		}
		else {
			function exception_handler($exception) {
				echo "Uncaught exception: " , $exception->getMessage(), "\n";
			}
			set_exception_handler('exception_handler');

			throw new Exception('Uncaught Exception');
		}
	}
	 
	// edit task GET Request
	if (isset($_GET['edit-task-' . $tasksRows])) {
		if  ($taskUserId == $listId) {
            $id = $_GET['edit-task-' . $tasksRows];
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
		
		// header('location: index.php');
		header('location: ' . $_SERVER['REQUEST_URI']);
		// header('Location: '.$_SERVER['REQUEST_URI']);

	}
		
	// Task status
	function checked($tasksRows)
	{
		
		$db = mysqli_connect("localhost", "root", "", "todo");	
		$checkboxName = $_POST['checkbox-' . $tasksRows];

        if (isset($checkboxName)) {
            foreach ($checkboxName as $aa) {
				var_dump($aa);
				$checked = 'checked';
				$status = 'done';
				mysqli_query($db, "UPDATE `tasks` SET `status`='$status' WHERE id='$tasksRows'");
			}
        }
		else {
			$checked = '';
			$status = 'undone';
			mysqli_query($db, "UPDATE `tasks` SET `status`='$status' WHERE id='$tasksRows'");
		}
		return $checked;
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
	<table>
		<tbody>		
			<form method="POST" action="">
				<?php foreach($tasks as $tasksRows): ?>
					<tr>
						<td> <?php echo $tasksRows[0]; ?> </td>
						<td class="task"> <?php echo $tasksRows[1]; ?> </td>
						<td>
							<input type="checkbox" class="checkbox" name="<?php echo 'checkbox-' . $tasksRows[0] . '[]' ?>" value="<?php echo $tasksRows[3] ?>" <?php echo checked($tasksRows[0]) ?> onchange="this.form.submit()">
						</td>
						<td class="edit"> 
							<a href="<?php echo $listId . '?edit_task=' . $tasksRows[0] ?>"><button type="text" name="<?php echo 'edit-task-' . $tasksRows[0] ?>" id="edit_btn" class="button"><i class="fa fa-edit"></i></button></a> 
						</td>
						<td class="delete"> 
							<a href="<?php echo $listId . '?del_task=' . $tasksRows[0] ?>"><button type="text" name="<?php echo 'del-task-' . $tasksRows[0] ?>" id="delete_btn" class="button"><i class="fa fa-remove"></i></button></a>
						</td>
					</tr>
				<?php endforeach; ?>
			</form>
		</tbody>
	</table>
</body>
</html>

