<input type="text" name="<?php echo $name ?>" class="<?php echo $class ?>" id="<?php echo id ?>" value="<?php echo get_option($id); ?>" />

<?php  if(isset($desc)):?>
   <br>
    <div class="desc"><?php echo $desc ?></div>
<?php  endif;?>