<?php include_once "templates/header.php"; ?>

<div class="header">
    <h2>Create Task</h2>
</div>
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

<?php include_once "templates/footer.php"; ?>