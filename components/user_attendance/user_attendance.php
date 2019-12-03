<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false); 
my_component_load('list_kalender' , false);  
my_component_load('user_detail' , false);
my_component_load('user_attendance');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Application _BN_ Employee _BN_ Attendance";

if( $task == "excel_export"){
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment;filename=data_attendance_resumes.xls");
     
    echo user_attendance_excel_report();
    exit;
}elseif( $task == "calculate_attendance"){ 
    $title = "Calculation on progress ...";
    facebox_page('index.php?com='. $_GET['com'] .'&task=calculate_attendance_page' , $title , 145	); 
    
}elseif( $task == "calculation_process"){
    //sleep(10);
    calculate_all_emp();
    echo '<img src="icon/okdone.png" height="60px" /><br/>Done!';
    exit;
}elseif( $task == "calculate_attendance_page"){
    $content = page_kalkulasi(); 
    generate_my_web($content,"","plain_loading.php");
    exit;
    
}else{
    load_facebox_script();
    $content = user_attendance_list();	

}
generate_my_web($content, $pagename  );