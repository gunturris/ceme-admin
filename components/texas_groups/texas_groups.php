<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('texas_groups' );

require_once(__DIR__ .'/custom.texas_groups.php');

$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Texas groups';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_texas_groups_validate($id);
			if($validatepost){
				$content =   message_multi_error($validatepost); 
				$content .= edit_texas_groups($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_texas_groups($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_texas_groups($id) ;
	}else{
		 
		load_facebox_script();
		$content =  list_texas_groups() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
