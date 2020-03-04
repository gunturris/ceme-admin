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
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/deposit.statistics.php');
    $content =  deposit_tabs( $player_id , $subtask ) ;

}elseif($task =="withdraw"){ 
    $modulname = 'Statistics - Withdraw'; 
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/withdraw.statistics.php');
    $content =  withdraw_tabs( $player_id , $subtask ); 
 
    
    
}elseif($task =="turn_over"){  
    $modulname = 'Statistics - Turn over';
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/turn_over.statistics.php');
    $content =  turn_over_tabs( $player_id , $subtask ); 
 
}elseif($task =="high_lose"){
    $modulname = 'Statistics - High lose';
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/high_lose.statistics.php');
    $content = high_lose_tabs($player_id , $subtask);
    
}elseif($task =="high_chip"){
    $modulname = 'Statistics - High chip';
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/high_chip.statistics.php'); 
    $content = high_chip_tabs($player_id , $subtask);
     
}elseif($task =="high_winner"){
    $modulname = 'Statistics - High winner';
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/high_winner.statistics.php');
    $content =  high_winner_tabs( $player_id , $subtask ); 
    
    
}elseif($task =="buyin_megajackpot"){ 
    $modulname = 'Statistics - Buy in mega jackpot';
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/buyin_megajackpot.statistics.php');
    $content =  buyin_megajackpot_tabs($player_id , $subtask) ; 
    
}elseif($task =="payout_megajackpot"){ 
    $modulname = 'Statistics - Payout mega jackpot';
    $player_id = isset($_GET['pid'] ) ? (int) $_GET['pid'] : 0;
    $subtask = isset($_GET['subtask'] ) ?  trim($_GET['subtask'] ) : "";
    require_once( __DIR__ . '/payout_megajackpot.statistics.php');
    $content =  payout_megajackpot_tabs( $player_id , $subtask ) ; 
     

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
