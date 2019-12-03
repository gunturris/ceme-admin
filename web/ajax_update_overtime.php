<?php
require_once("../config.php");

if( isset($_GET['reset'] ) ){
    $finger_id = (int) $_GET['finger_id'] ;
    $date =   $_GET['date'] ;
    my_query("DELETE FROM overtime_activity_log WHERE emp_finger_id = {$finger_id} AND request_date = '{$date}' ");
    echo "1";
    exit;
}

function update_overtime_activity_log( $start_end_field ,$finger_id , $date  , $time){
    $data_activity_overtime = get_overtime_log( $finger_id , $date );
    
    
    if( $start_end_field == 'start_time' ){
        $start_time = $time;
        $end_time   = "00:00:00";
        $field = "ot_start_time";
    }else{
        $start_time = "00:00:00";
        $end_time   = $time;
        $field = "ot_end_time";
    }
    
    update_overtime_log($finger_id , $field , $date , $time);
    
    if( $data_activity_overtime ){
        $now = date("Y-m-d H:i");
        $query = "UPDATE overtime_activity_log SET `{$start_end_field}` = '{$time}' , remark = 'Correction on {$now}' WHERE id = {$data_activity_overtime['id']} ";
        return my_query($query);
    } 
     
    
    $id = rand( 10000000 , 99999999 );
    $datas = array();
    $datas['id']                = my_type_data_int($id);
    $datas['emp_finger_id']     = my_type_data_int($finger_id);
    $datas['request_date']      = my_type_data_str($date);
    $datas['start_time']        = my_type_data_str($start_time);
    $datas['end_time']          = my_type_data_str($end_time);
    $datas['remark']            = my_type_data_str("Correction manual");
    $datas['created_on']        = my_type_data_function("NOW()");
    return my_insert_record('overtime_activity_log' , $datas);
    
}

function update_overtime_log($finger_id , $field , $date , $time){
    $query = "UPDATE overtime_log SET `{$field}` = '{$time}'  WHERE emp_finger_id = {$finger_id} AND overtime_date = '{$date}' ";
    $result = my_query($query);
}

function get_overtime_log( $finger_id , $date ){
    $query = "
            SELECT id 
                FROM overtime_activity_log 
            WHERE emp_finger_id = {$finger_id} 
                AND request_date = '{$date}' ";
    $result = my_query($query);
    if( my_num_rows($result ) > 0 ){
        $row = my_fetch_array($result);
        return $row;
    }
    return false;
}

$finger_id = $_GET['finger_id'];
$date = $_GET['date'];
$time = $_GET['time'];

if( $_GET['s'] == '1'){ 
    $start_end_field = 'start_time'; 
}else{
    $start_end_field = 'end_time';
}

update_overtime_activity_log( $start_end_field ,$finger_id , $date  , $time);
print(date("H:i" , strtotime($time) ) );
//print(json_encode($_GET) );
exit;