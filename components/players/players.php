<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('players' );

require_once(__DIR__ .'/custom.players.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$subtask = isset($_GET['subtask']) ? $_GET['subtask'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Data players';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_players_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_players($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_players($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
    }elseif($task == 'detail' ) { 
		$content =  player_detail($id , $subtask) ;
	}else{
		 
		load_facebox_script();
		$content =  list_players() ; 
	}
endif; 
generate_my_web($content, $modulname );
