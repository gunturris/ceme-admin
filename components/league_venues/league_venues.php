<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('league_venues' );

require_once(__DIR__ .'/custom.league_venues.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Data venues';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_league_venues_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_league_venues($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_league_venues($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_league_venues($id) ;
	}else{
		 
		load_facebox_script();
		$content =  list_league_venues() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
