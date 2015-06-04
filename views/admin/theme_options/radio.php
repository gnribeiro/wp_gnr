<?php foreach($options as $value => $label): ?>
<div>
   <label for=""><?php echo  $label ?></label>
    <input type="radio" name="<?php echo $name ?>"  class="<?php echo $class ?>"  value="<?php echo $value ?>" id="<?php echo $id ?>" <?php checked($value, get_option($id), true); ?> />
</div>
<?php endforeach ?>


<?php  if(isset($desc)):?>
   <br>
    <div class="desc"><?php echo $desc ?></div>
<?php  endif;?>