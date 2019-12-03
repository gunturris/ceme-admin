<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('user_add');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Add";

if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    if( $validate = user_add_validation($id)){
        $content = message_multi_error($validate);
        $content .= user_add_form($id  ); 
    }else{
        
        user_add_submit();
        my_direct("index.php?com=user_list");
    }
    
}else{ 
	load_facebox_script();
	$content = user_add_form($id) ;	
}
generate_my_web($content, $pagename  );