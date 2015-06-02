<input type="checkbox" name="<?php echo $name ?>"  class="<?php echo $class ?>"  value="<?php echo $value ?>" id="<?php echo id ?>" <?php checked(1, get_option($id), true); ?> />

<?php  if(isset($desc)):?>
   <br>
    <div class="desc"><?php echo $desc ?></div>
<?php  endif;?>