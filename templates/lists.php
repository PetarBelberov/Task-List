<?php include_once "templates/header.php"; ?>

<div class="create-task">
    <a href="<?php echo SITE_URL . '/create.php/' . $listId ?>">Create Task</a>
</div>
<table>
    <tbody>		
        <form method="POST" action="" name="all-lists">
            <?php foreach($tasks as $tasksRows): ?>
                <tr>
                    <td> <?php echo $tasksRows[0]; ?> </td>
                    <td class="task"> <?php echo $tasksRows[1]; ?> </td>
                    <td> <?php echo $tasksRows[3]; ?> </td>
                    <td>
                        <input type="checkbox" class="checkbox" name="<?php echo 'checkbox-' . $tasksRows[0] . '[]' ?>" value="<?php echo $tasksRows[4] ?>" <?php echo checked($tasksRows[0])  ?> onchange="this.form.submit()">
                    </td>
                    <td class="edit"> 
                        <a href="<?php echo '../edit.php/' . $listId . '?edit_task=' . $tasksRows[0] ?>">
                            <i class="fa fa-edit button"></i><input type="submit" value="" name="<?php echo 'edit-' . $tasksRows[0] ?>" id="edit_btn" />
                        </a>
                    </td>
                    <td class="delete"> 
                        <a href="<?php echo '../delete.php/' . $listId . '?del_task=' . $tasksRows[0] ?>">
                            <i class="fa fa-remove button"></i><input type="submit" value="" name="<?php echo 'delete-' . $tasksRows[0] ?>" id="delete_btn" />
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </form>
    </tbody>
</table>

<?php include_once "templates/footer.php"; ?>