<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('texas_games' );

require_once(__DIR__ .'/custom.texas_games.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Texas games';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_texas_games_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_texas_games($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_texas_games($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_texas_games($id) ;
	}else{
		 
		load_facebox_script();
		$content =  list_texas_games() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
