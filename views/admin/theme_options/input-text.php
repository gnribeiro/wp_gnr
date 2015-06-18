<input type="text" name="<?php echo $name ?>" class="<?php echo $class ?> regular-text code" id="<?php echo $id ?>" value="<?php echo get_option($id); ?>" />

<?php  if(isset($desc)):?>
   <p class="description "><?php echo $desc ?></p>
<?php  endif;?>