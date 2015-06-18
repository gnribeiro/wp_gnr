<?php foreach($options as $value => $label): ?>

   <label for="">
    <input type="radio" name="<?php echo $name ?>"  class="<?php echo $class ?>"  value="<?php echo $value ?>" id="<?php echo $id ?>" <?php checked($value, get_option($id), true); ?> /><span><?php echo  $label ?></span></label>
<br>
<?php endforeach ?>


<?php  if(isset($desc)):?>
    <p class="description "><?php echo $desc ?></p>
<?php  endif;?>