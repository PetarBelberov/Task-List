
<?php include_once "templates/header.php"; ?>
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

<?php include_once "templates/footer.php"; ?>