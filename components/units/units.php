<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('units');

$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Units";


if( $_SERVER['REQUEST_METHOD'] == "POST" ){
    if( $task == 'edit'){
        if( $validate = unit_add_validate($id)){
            $content = message_multi_error($validate);
            $content .= unit_add_form($id  ); 
            
        }else{ 
            unit_add_submit($id);
            my_direct("index.php?com=units");
            
        }
    }
}else{
    if( $task == 'edit'){
        $content = unit_add_form($id);
        
    }elseif($task == 'delete'){
        my_query("DELETE FROM departments WHERE departemen_id = {$id} ");
        my_direct("index.php?com={$_GET['com']}");
        
    }else{ 
        load_facebox_script();
        $content = list_units();	
        
    }
}
generate_my_web($content, $pagename  );