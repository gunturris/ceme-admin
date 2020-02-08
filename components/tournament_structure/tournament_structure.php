<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('tournament_structure' );

require_once(__DIR__ .'/custom.tournament_structure.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Tournament structure';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_tournament_structure_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_tournament_structure($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_tournament_structure($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_tournament_structure($id) ;
	}else{
		 
		load_facebox_script();
		$content =  list_tournament_structure() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
