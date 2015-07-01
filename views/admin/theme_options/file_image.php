<style>
    .imgoption-container{
          width: 10%;
      /* height: 150px; */
      overflow: hidden;
    }
    
    .imgoption{
        height: auto;
        display: block;
        width: 100%;
        transform: translate(0% , 0%);
    }
    
    
    .imgoption-remove a{
        display:inline-block;
        margin-right:8px;
        margin-bottom:10px;
        margin-top:10px;
    }
    
    
    .imgoption-remove img{
        position: relative;
        top: 3px;
    }
   
</style>


<input type="file" name="<?php echo $name ?>"  id="<?php echo $id ?>"  /> 

<?php  if( get_option($id) && get_option($id) !=='' ):?>  
    <div id="placeImage-<?php echo $id?>">
        <div class="imgoption-remove">
             <a href="#" id="removeImage-<?php echo $id?>">Remove</a> <img src="/wp-admin/images/wpspin_light.gif" alt=""  id="loading-<?php echo $id?>" style="display:none">
        </div>  

        <div  class="imgoption-container"><img src=" <?php echo get_option($id); ?>" alt="" class="imgoption"></div>
    </div>  
<?php  endif;?>

<?php  if(isset($desc)):?>
    <p class="description "><?php echo $desc ?></p>
<?php  endif;?>

           

    
<?php  if(get_option($id) && get_option($id)!==''):?>    
<script>

    
    jQuery("#removeImage-<?php echo $id?>").on('click' , function(event){
        event.preventDefault();
        
        jQuery("#loading-<?php echo $id?>").show();
        
        jQuery.post(
            ajaxurl,
            {action:'themeoptionsfile', id:"<?php echo $id?>"}, function(response){
                jQuery("#placeImage-<?php echo $id?>").remove();
            }
        );
        
        <?php //delete_option( $id ); ?>
    })
    
</script>  
<?php  endif;?>         
          
           