<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7 ie6"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8 ie7"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9 ie8"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <title><?php echo helper::getSiteTitle()?></title>
    <meta charset="<?php echo $data['charset']?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <link rel="shortcut icon" href="<?php echo THEMEURL?>favicon.ico" />
    <link rel="stylesheet" href="<?php echo THEMEURL?>/assets/css/index.css">
    <script src="<?php echo THEMEURL?>/assets/js/vendor/modernizr-2.6.2.min.js"></script>


    <?php wp_head(); ?>
    <!--[if lt IE 9]>
        <script src="<?php echo THEMEURL?>/assets/js/vendor/selectivizr-min.js"></script> 
    <![endif]-->

    <script type='text/javascript'>
    /* <![CDATA[ */
    var vars_site = {"theme_url":"<?php  echo THEMEURL ?>","ajax_url":" <?php echo admin_url( 'admin-ajax.php' ) ?>"};
    /* ]]> */
    </script>

    
