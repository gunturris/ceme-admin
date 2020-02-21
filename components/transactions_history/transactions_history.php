<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('transactions_history' );
 

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Transactions - History';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_leagues_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_leagues($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_leagues($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_leagues($id) ;
	}else{
		 
		load_facebox_script();
		$content =  list_transaction_history() ; 
	}
endif; 
generate_my_web($content, $modulname ); 