<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('dealer_users' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Admin _BN_ Konfigurasi Data _BN_ dealer_users';

if($_SERVER['REQUEST_METHOD'] == "POST" ):
 	switch($task){
		case   "edit" :
			$validatepost = form_dealer_users_validate();
			if($validatepost){
				$errors = message_multi_error($validatepost);
				$content = $errors;
				$content .= edit_dealer_users($id);
				generate_my_web($content,"","plain.php");
				exit; 
			}else{
				submit_dealer_users($id);
				$content =  "Updated";
				my_direct('index.php?com='.$_GET['com']);
			 }
			break; 
	}
else: 	
	if($task == "edit"){ 
		$content =  edit_dealer_users($id) ;
	}else{
		$pagename = 'Referensi '.symbol_breadcumb().' '; 
		load_facebox_script();
		$content =  list_dealer_users() ; 
	}
endif; 
generate_my_web($content, $modulname );
?>
