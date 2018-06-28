<?php 

session_start(); 

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

	$user = mysqli_query($db, "SELECT id, username, email FROM users WHERE username='$username'");
	$userColumns = mysqli_fetch_row($user);
	$userId = $userColumns[0];
	
	$tasks = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM tasks WHERE user_id='$userColumns[0]'"));
	$taskUserId = null;
	foreach	($tasks as $t) {
		$taskUserId = $t[2];
	}
	
	if (isset($_POST['submit'])) {
					
		if (empty($_POST['task'])) {
			$errors = "You must fill in the task";
		}
		else {
			$task = $_POST['task'];
			
			$sql = "INSERT INTO tasks (task, user_id) VALUES ('$task', '$userColumns[0]')";
			
			mysqli_query($db, $sql);
			
			header('location: index.php');
			}
	}
	
	// delete task
	if (isset($_GET['del_task'])) {
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
	if (isset($_GET['edit_task'])) {
		if  ($userId == $taskUserId) {
	    $id = $_GET['edit_task'];
		$update = true;
		$record = mysqli_query($db, "SELECT task FROM tasks WHERE id=$id");
		
		$n = mysqli_fetch_array($record);
		$task = $n['task'];
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
	if (isset($_POST['update'])) {
		$id = $_POST['id'];
		$task = $_POST['task'];
		
		mysqli_query($db, "UPDATE tasks SET task = '$task' WHERE id = $id");
		
		header('location: index.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>ToDo List Application PHP and MySQL</title>
</head>
<body>

<div class="header">
	<h2><?php echo $_SESSION['username']; ?> 's ToDo List</h2>
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

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
</div>

	<form method="post" action="index.php" class="input_form">
	<?php if (isset($errors)) { ?>
		<p><?php echo $errors; ?></p>
	<?php } ?>
	
		<input type="hidden" name="id" value="">
		
		<?php if (isset($update)) : ?>
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="text" name="task" class="task_input" value="<?php echo $task; ?>">
			<button type="submit" name="update" id="update_btn" class="button"><i class="fa fa-edit"></i></button>
		<?php else: ?>
			<input type="text" name="task" class="task_input">
			<button type="submit" name="submit" id="add_btn" class="button"><i class="fa fa-plus"></i></button>
		<?php endif ?>
	</form>
	<table>
	<tbody>
		<?php foreach($tasks as $tasksRows): ?>
			
			<tr>
				<td> <?php echo $tasksRows[0]; ?> </td>
				<td class="task"> <?php echo $tasksRows[1]; ?> </td>
				<td class="edit"> 
					<a href="index.php?edit_task=<?php echo $tasksRows[0] ?>"><button type="text" name="edit" id="edit_btn" class="button"><i class="fa fa-edit"></i></button></a> 
				</td>
				<td class="delete"> 
					<a href="index.php?del_task=<?php echo $tasksRows[0] ?>"><button type="text" name="delete" id="delete_btn" class="button"><i class="fa fa-remove"></i></button></a>
				</td>
			</tr>
			<?php endforeach; ?>	
	</tbody>
</table>
</body>
</html>

