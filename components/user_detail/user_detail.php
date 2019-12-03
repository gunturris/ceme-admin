<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('list_kalender' , false);  
my_component_load('user_detail');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Detail";

if($task == 'calc'){
    
    //calculate_by_emp($id);
    print('Done! ..<a href="index.php?com=user_attendance">Back</a>');
    exit;
}elseif($task == 'checkin'){
    $newtime = checkInManual();
    print($newtime);
    exit;
}elseif($task == 'checkout'){
    $newtime = checkOutManual();
    print($newtime);
    exit;
}elseif($task == 'detail_excel'){
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment;filename=data_attendance_detail.xls");
     
    echo user_attendance_detail_excel_report($id);
    exit;
}else{ 
    load_facebox_script();
    $content = user_detail($id);	
}
generate_my_web($content, $pagename  );