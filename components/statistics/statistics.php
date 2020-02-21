<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('statistics' );
 

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Statistics';
  	
load_facebox_script();

if($task == "deposit"){ 
    $modulname = 'Statistics - Deposit';
    require_once( __DIR__ . '/deposit.statistics.php');
    $content =  list_statistic_deposit() ;
    
}elseif($task =="turn_over"){  
    $modulname = 'Statistics - Turn over';
    require_once( __DIR__ . '/turn_over.statistics.php');
    $content =  list_statistic_turn_over() ; 

 
}elseif($task =="high_chip"){
    $content =  list_statistic_high_chip();
    
}elseif($task =="high_lose"){
    $content =  list_statistic_high_lose();
 
}elseif($task =="high_lose"){
    $modulname = 'Statistics - High lose';
    require_once( __DIR__ . '/high_lose.statistics.php');
    $content = list_statistic_high_lose();
    
}elseif($task =="high_chip"){
    $modulname = 'Statistics - High chip';
    require_once( __DIR__ . '/high_chip.statistics.php'); 
    $content = list_statistic_high_chip();
     
}elseif($task =="high_winner"){
    $modulname = 'Statistics - High winner';
    require_once( __DIR__ . '/high_winner.statistics.php');
    $content =  list_statistic_high_winner();
    
}elseif($task =="buyin_megajackpot"){ 
    $modulname = 'Statistics - Buy in mega jackpot';
    require_once( __DIR__ . '/buyin_megajackpot.statistics.php');
    $content =  list_buyin_megajackpot() ; 
    
}elseif($task =="payout_megajackpot"){ 
    $modulname = 'Statistics - Payout mega jackpot';
    require_once( __DIR__ . '/payout_megajackpot.statistics.php');
    $content =  list_payout_megajackpot() ; 
     
}elseif($task =="withdraw"){ 
    $modulname = 'Statistics - Withdraw';
    require_once( __DIR__ . '/withdraw.statistics.php');
    $content =  list_statistic_withdraw() ; 

}elseif($task =="player_last_login"){ 
    $modulname = 'Statistics - Member login';
    require_once( __DIR__ . '/players.statistics/login_history.php');
    $content =  list_statistic_login_history() ; 

}elseif($task =="player_new_register"){ 
    $modulname = 'Statistics - Member new register';
    require_once( __DIR__ . '/players.statistics/new_register.php');
    $content =  list_statistic_new_register() ; 
    
}elseif($task =="player_active"){ 
    $modulname = 'Statistics - Member active';
    require_once( __DIR__ . '/players.statistics/player_active.php');
    $content =  list_statistic_player_active() ; 

}else{

    $content =  list_statistic_turn_over() ; 
}  
generate_my_web($content, $modulname );
