<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('__generator_com' );

$headers = array();
$headers['Username'] = "'width'=>'35%','style'=>'text-align:center;'"; 
$headers['Firstname'] = "'width'=>'25%','style'=>'text-align:center;'"; 
$headers['Lastname'] = "'width'=>'25%','style'=>'text-align:center;'";   
$headers['Rights'] = "'width'=>'15%','style'=>'text-align:center;'";    
$module_name = "dealer_users";
 
generate_files($module_name,  $headers) ;
exit;