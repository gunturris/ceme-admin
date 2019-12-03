<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('ref_off_dates');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Settings _BN_ Off dates";
	


if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    if( $task == 'edit'){
        if( $validate = off_date_validate($id)){
            $content = message_multi_error($validate);
            $content .= off_date_form($id  ); 
            
        }else{ 
            off_date_submit($id);
            my_direct("index.php?com={$_GET['com']}");
            
        }
    }
}else{
    if( $task == 'edit'){
        $content = off_date_form($id);
      
    }elseif($task == "delete"){
        my_query("DELETE FROM off_dates WHERE id = {$id} ");
        my_direct("index.php?com={$_GET['com']}");
    }else{  
        load_facebox_script();
        $content = list_off_dates();
        
    }
}

generate_my_web($content, $pagename  );