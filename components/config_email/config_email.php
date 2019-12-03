<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('config_email' );
$task = isset($_GET['task']) ? $_GET['task'] : ''; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0;
$modulname = 'Aplikasi _BN_ Agenda surat _BN_ Konfigurasi email';

		
if($_SERVER['REQUEST_METHOD'] == "POST" ): 
global $errors;
	$validatepost = form_config_email_validate();
	if($validatepost){
		$errors = message_multi_error($validatepost); 
		 
	}else{  
		$message = "Data telah berhasil di ubah";
		$errors = message_correct($message);
	}
endif;	  
	load_facebox_script();
	$content =  form_config_email() ;  
 
generate_my_web($content, $modulname );
?>