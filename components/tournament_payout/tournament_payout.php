<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('tournament_payout' );

require_once(__DIR__ .'/custom.tournament_payout.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Tournament payout';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_tournament_payout_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_tournament_payout($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_tournament_payout($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_tournament_payout($id) ;
	}else{
		 
		load_facebox_script();
		$content =  list_tournament_payout() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
