<?php 
    global $site;
    
    $username = helper::message('knews', 'teste');
    $username3 = helper::message('teste', 'teste');
 $array    = array(
    'foo' =>array(
        'bar'=>'teste ',
        'bar2'=>'teste 2'
    )
 );
function cube($n)
{
    return $n . $n ;
}

pr(helper::arraymap("cube" ,  $array));

 $value = helper::arraypath($array, 'foo.bar');

    pr($username);
    pr($username3);
    
 pr($value);

    $site->index();
    //$site->set_content('page' , array('template' =>'index'));
    
   
?>