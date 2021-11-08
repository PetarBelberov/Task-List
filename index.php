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

// $tasks = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM tasks WHERE list_id='$listId'"));

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

?>
<!DOCTYPE html>
<html>
<head>
  <title>Lists</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
			<h2><?php echo $username . "'s To-Do List"?></h2>
		</div>
        <div class="container">
            <form method="post" action="" class="input_form">
                <?php if (isset($errors)) { ?>
                    <p><?php echo $errors; ?></p>
                <?php } ?>
        
                <input type="hidden" name="id" value="">
                
                <?php if (isset($update)) : ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="text" name="list" class="list_input" value="<?php echo $list; ?>">
                    <button type="submit" name="update-list" id="update_list_btn" class="button"><i class="fa fa-edit"></i></button>
                <?php else: ?>
                    <input type="text" name="list" class="list_input">
                    <button type="submit" name="submit-list" id="add_btn" class="button"><i class="fa fa-plus"></i></button>
                <?php endif ?>
	        </form>
            <table>
                <tbody>
                    <?php foreach($lists as $listId): ?>
                        <tr>
                            <td class="list"><a href="<?php echo '/lists.php/' . $listId[0] ?>"> <?php echo $listId[1]; ?> </a></td>
                        </tr>
                    <?php endforeach; ?>	
                </tbody>
            </table>
        </div><!-- .container -->
    </body>
</html>

