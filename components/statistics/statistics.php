<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('statistics' );

//require_once(__DIR__ .'/custom.players.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Statistics';
  	
load_facebox_script();

if($task == "deposit"){ 
    $content =  list_statistic_deposit() ;
}elseif($task =="turn_over"){ 
    $content =  list_statistic_turn_over() ; 

}elseif($task =="high_chip"){
    $content =  list_statistic_high_chip();
}elseif($task =="high_lose"){
    $content =  list_statistic_high_lose();
}elseif($task =="high_winner"){
    $content =  list_statistic_high_winner();
}elseif($task =="withdraw"){ 
    
    $content =  list_statistic_withdraw() ; 

}else{

    $content =  list_statistic_turn_over() ; 
}  
generate_my_web($content, $modulname );
