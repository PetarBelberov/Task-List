<?php if (count($verificationMessages) > 0) : ?>
 <div class="successReg">
  	<?php foreach ($verificationMessages as $verificationMessage) : ?>
  	  <p><?php echo $verificationMessage ?></p>
  	<?php endforeach ?>
  </div>
<?php  endif ?>
  