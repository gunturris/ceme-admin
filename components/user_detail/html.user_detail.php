<?php

function user_detail($user_id){
    
    my_set_code_css(
    "
.switch {
  position: relative;
  display: inline-block;
  width: 32px;
  height: 20px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: '';
  height: 12px;
  width: 12px;
  left: 2px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 10px;
}

.slider.round:before {
  border-radius: 50%;
}
" );
 
    
my_set_code_js(
"
function updateManIn( mdate, deftime ){ 
 
    $.get( 'index.php' , { 
            com: '{$_GET['com']}', 
            task:'checkin', 
            date:  mdate  , 
            id: {$_GET['id']} 
        } , 
        function( data ) { 
            console.log(data)
             $('#time_in_'+mdate).html(data)
             $('#remark_'+mdate).html('')
        }
    );
  
}

function updateManOut(mdate, deftime){
     $.get( 'index.php' , { 
            com: '{$_GET['com']}', 
            task:'checkout', 
            date:  mdate  , 
            id: {$_GET['id']} 
        } , 
        function( data ) {
            console.log(data)
             $('#time_out_'+mdate).html(data)
             $('#remark_'+mdate).html('')
        }
    );
    
    
}

function openPDFAttendance(id){
	xwin = window.open('index.php?com=__report&task=rpt_attendance&id='+id , '".rand(1000,9999)."'+id , 'toolbar=0,menubar=0,location=0,width:800px,height:800px' );
	if( window.focus  ){
		xwin.focus()
	}
	return;
}
"
);    
    
    global $box;
    $navigasi = array(
		'<input class="submit-green" type="button" value="Print" onclick="javascript:openPDFAttendance('.$user_id.')" />',
	 	'<input class="submit-green" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail_excel&id='.$user_id.'\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi );
    
    $emp = my_get_data_by_id('emp' , 'finger_id' , $user_id );
    $unit =  my_get_data_by_id('departments' , 'departemen_id' , $emp['departemen_id'] );
    $shift =  my_get_data_by_id('shift_group' , 'shift_id' , $emp['shift_id'] );
     
    $view = '
    <table width="100%" style="border-top:2px solid;">
        
        <tr>
            <td width="15%" style="border-right:0"><b>Employee ID</b></td>
            <td width="84%">'. $emp['nik'] .' / '. $emp['realname'] .' </td>
        </tr>
        <tr>
            <td style="border-right:0"><b>Unit</b></td>
            <td>'. $unit['departemen_code'] .' / '. $unit['departemen_name'] .'</td>
        </tr>
        <tr>
            <td style="border-right:0"><b>Shift</b></td>
            <td>'. $shift['shift_code'] .' / '. $shift['shift_group_name'] .'</td>
        </tr>
    
    </table>
    ';
    return $view . list_presensi($user_id ,  $emp['shift_id']);
}

function list_presensi($user_id , $shift_id){
    $query = "SELECT * FROM shift_group_times WHERE shift_id = {$shift_id} ORDER BY day_id ";
    $result = my_query($query);
    $sched = array();
    while($ey = my_fetch_array($result)){
        $sched[$ey['day_id']] = array(
                'in'=>$ey['schedule_in_time'],
                'out'=>$ey['schedule_out_time'],
            );
    }
     
    $header = array( 
		'Date'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),        
		'Day'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),        
		'Schedule'=>array('style'=>'text-align:center;border-bottom:2px solid;width:15%'),  
		'Clock In'=>array('style'=>'text-align:center;border-bottom:2px solid;width:12%'),  
		'Clock Out'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Manual In'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Manual Out'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Remark'=>array('style'=>'text-align:center;border-bottom:2px solid;width:30%'),   
	);
    
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    $month_dates = get_range_date_of_month( $periode_year , $periode_month );
     
    $dates = list_kalender( $month_dates['start_date'] , $month_dates['end_date']);
    $row = array();
    foreach( $dates as $date ){
        $nday = date('N' , strtotime($date) );
        $info = get_info_attendance($user_id , $date );
         
            $calcTime = calculate_in_out_time( $info['in_time'] , $info['out_time'] );
            $default_check_in = date("H:i" , strtotime($sched[$nday]['in'] ));
            $default_check_out = date("H:i" , strtotime($sched[$nday]['out'] ));
            
            if( (int) $info['in_time'] > 0 ){ 
                $check_in_manual = '<label class="switch"> &nbsp;</label>';
            }else{
                $check_in_manual = '<label class="switch"> <input value="'.$date.'" type="checkbox" onchange="javascript:updateManIn( this.value , \''.$default_check_out.'\')" '.( $info['man_in'] == '1' ? "checked" : "") .'/><span class="slider round"></span></label>';
            }
            
            if( (int) $info['out_time'] > 0 ){
                $check_out_manual = '<label class="switch"> &nbsp;</label>';
            }else{
                
                $check_out_manual = '<label class="switch"> <input value="'.$date.'"  type="checkbox" onchange="javascript:updateManOut(this.value , \''.$default_check_in.'\')" '.( $info['man_out'] == '1' ? "checked" : "") .'/><span class="slider round"></span></label>';
            }
            
            if($info['man_in'] == '1'){
                $value_in = $default_check_in; 
                
            }else{
                $value_in = date("H:i" , strtotime($info['in_time'] ));
                
            }
            
            if($info['man_out'] == '1'){
                $value_out = $default_check_out;
                
            }else{
                $value_out = date("H:i" , strtotime($info['out_time'] ));
                
            }
            
            /*
            if($info['man_out'] =='0' AND $info['man_in'] =='0' AND ( $info['in_time'] != '00:00:00' AND $info['out_time'] != '00:00:00') )
                $remark = 'Work for '.$calcTime.'  Hours';
            
            elseif( $info['in_time'] == '00:00:00' AND $info['out_time'] == '00:00:00' )
                $remark = ' ';
            
            elseif( $info['in_time'] == '00:00:00' )
                $remark = 'Finger clock in not found!';
            
            elseif( $info['out_time'] == '00:00:00' )
                $remark = 'Finger clock out not found!';
            
            else
                $remark = '-';
            */
            
            $remark = $info['remark'];
            if( $info['present_status'] == '1' ){ 
                $row[] = array(
                    'Date'  => $date ,
                    'Day'   => date('l' , strtotime($date) ) ,
                    'Sched'   => date("H:i" , strtotime(  $sched[$nday]['in'] )) .' s. d ' . date("H:i" , strtotime( $sched[$nday]['out'])) ,
                    'In'    => position_text_align(  '<span id="time_in_'.$date.'">'  . $value_in  .'</span>', 'center' ),
                    'Out'   => position_text_align(  '<span id="time_out_'.$date.'">' . $value_out .'</span>', 'center' ),
                    'Min'    => position_text_align( $check_in_manual ,'center' ),
                    'Mout'   => position_text_align( $check_out_manual ,'center' ),
                    'Remark'   => $remark,
                ); 
            }else{
                $row[] = array(
                    'Date'  => '<font color="red">'.$date .'</font>' ,
                    'Day'   => '<font color="red">'.date('l' , strtotime($date) ).'</font>' ,
                    'Sched'   => date("H:i" , strtotime(  $sched[$nday]['in'] )) .' s. d ' . date("H:i" , strtotime( $sched[$nday]['out'])) ,
                    'In'    => position_text_align(  '<span id="time_in_'.$date.'">'  . $value_in  .'</span>', 'center' ),
                    'Out'   => position_text_align(  '<span id="time_out_'.$date.'">' . $value_out .'</span>', 'center' ),
                    'Min'    => position_text_align( $check_in_manual ,'center' ),
                    'Mout'   => position_text_align( $check_out_manual ,'center' ),
                    'Remark'   => '<span id="remark_'.$date.'">'.'<font color="red">'.$remark .'</font>' .'</span>',
                ); 
                
            }
        }
        
     
    $datas = table_rows($row);
	return table_builder( $header , $datas , 4 , false );  
    
}


function get_info_attendance($finger_id , $date){
    $query = "SELECT * FROM attendance_status_log WHERE finger_id = {$finger_id} AND attendance_date = '{$date}' LIMIT 1 ";
    $result = my_query($query);
    if(my_num_rows($result) > 0 ){
        
        $row = my_fetch_array($result);
        return $row;
    }
    
    return false;
}

function calculate_in_out_time( $starttime , $endtime ){
    $start = strtotime($starttime);
    $end   = strtotime($endtime);
    
    $calc = $end - $start;
    return date("H:i" ,$calc);
    
}

function defaultScheduleTime($fingerID , $date){
    $day = $nday = date('N' , strtotime($date) );
    $query = "SELECT * FROM shift_group_times a  
                INNER JOIN emp c ON c.shift_id = a.shift_id WHERE finger_id = {$fingerID} AND day_id = {$nday} ";
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row;
}

function checkStatusManual($finger_id , $date , $io){
    $query = "SELECT man_in , man_out FROM attendance_status_log   WHERE  attendance_date = '{$date}' AND finger_id = {$finger_id} ";
    
    $result = my_query($query);
    $row = my_fetch_array($result);
    
    return $row[$io];
}

function updatePresentStatus(  $finger_id , $date ){
    $query = "SELECT man_in , man_out FROM attendance_status_log   WHERE  attendance_date = '{$date}' AND finger_id = {$finger_id} ";
    
    $result = my_query($query);
    $row = my_fetch_array($result);
    if(  $row['man_in'] == '0' and $row['man_out'] =='0' ){
        $query_update = "
            UPDATE attendance_status_log 
                SET present_status = 0  , remark = 'Manual reset '
                WHERE attendance_date = '{$date}' AND finger_id = {$finger_id} "; 
    }else{
        $query_update = "
            UPDATE attendance_status_log 
                SET present_status = 1  , remark = 'Manual attendance correction '
                WHERE attendance_date = '{$date}' AND finger_id = {$finger_id} "; 
    }
    return my_query($query_update);
}

function checkInManual( ){
    $finger_id = (int) $_GET['id'];
    $date = isset($_GET['date']) ? $_GET['date'] : "";
    
    $status = checkStatusManual($finger_id , $date , 'man_in');
    
    if( $status == '1'){
        $query = "UPDATE attendance_status_log SET man_in = 0  WHERE attendance_date = '{$date}' AND finger_id = {$finger_id} ";  
        my_query($query);
        
        updatePresentStatus(  $finger_id , $date );
        
        return '00:00'; 
        
    }else{
        $query = "UPDATE attendance_status_log SET man_in = 1 WHERE attendance_date = '{$date}' AND finger_id = {$finger_id} "; 
        my_query($query);
        
        updatePresentStatus(  $finger_id , $date );
        
        $info = defaultScheduleTime($finger_id , $date);
        return date("H:i" , strtotime($info['schedule_in_time']));         
    }
}

function checkOutManual( ){
    $finger_id = (int) $_GET['id'];
    $date = isset($_GET['date']) ? $_GET['date'] : "";
    
    $status = checkStatusManual($finger_id , $date , 'man_out');
    
    if( $status == '1'){
        $query = "UPDATE attendance_status_log SET man_out = 0 WHERE attendance_date = '{$date}' AND finger_id = {$finger_id} "; 
        my_query($query);
        
        updatePresentStatus(  $finger_id , $date );
    
        return '00:00';
        
    }else{
        $query = "UPDATE attendance_status_log SET man_out = 1 WHERE attendance_date = '{$date}' AND finger_id = {$finger_id} "; 
        my_query($query);
        
        updatePresentStatus(  $finger_id , $date );
        
        $info = defaultScheduleTime($finger_id , $date);
        return date("H:i" , strtotime($info['schedule_out_time']));
        
    }
}




function user_attendance_detail_excel_report($finger_id){
    
    my_component_load('xl_builder' , false);
    
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    $month_dates = get_range_date_of_month( $periode_year ,$periode_month );
    
    $emp = my_get_data_by_id('emp' , 'finger_id' , $finger_id);
    
    $query = "SELECT * FROM shift_group_times WHERE shift_id = {$emp['shift_id']} ORDER BY day_id ";
    $result = my_query($query);
    $sched = array();
    while($ey = my_fetch_array($result)){
        $sched[$ey['day_id']] = array(
                'in'=>$ey['schedule_in_time'],
                'out'=>$ey['schedule_out_time'],
            );
    }
    
    
    $header = array( 
		'PIN'=>array('style'=>'border-bottom:2px solid;'),  
		'NIK'=>array('style'=>'border-bottom:2px solid;'),  
		'Name'=>array('style'=>'border-bottom:2px solid;'), 
        
		'Date'=>array('style'=>'border-bottom:2px solid;'),  
		'Shift Code'=>array('style'=>'border-bottom:2px solid;'),  
		'Schedule In'=>array('style'=>'border-bottom:2px solid;'),  
		'Schedule Out'=>array('style'=>'border-bottom:2px solid;'),  
        
		'Clock In'=>array('style'=>'border-bottom:2px solid;'),  
		'Clock Out'=>array('style'=>'border-bottom:2px solid;') ,
		'Man In'=>array('style'=>'border-bottom:2px solid;') ,
		'Man Out'=>array('style'=>'border-bottom:2px solid;') ,
		'Remark'=>array('style'=>'border-bottom:2px solid;')  
    );
	
    $shf = my_get_data_by_id( 'shift_group' , 'shift_id' , $emp['shift_id'] );	   
    
    $dates = list_kalender( $month_dates['start_date'] , $month_dates['end_date']);
    
    $row = array();
    foreach( $dates as $date ){
        $nday = date('N' , strtotime($date) );
        $info = get_info_attendance($finger_id , $date );
    
        $default_check_in = date("H:i:s" , strtotime($sched[$nday]['in'] ));
        $default_check_out = date("H:i:s" , strtotime($sched[$nday]['out'] ));
        
        if($info['man_in'] == '1'){
            $man_in = 'Active';
            $value_in = $default_check_in; 

        }else{
            $man_in = 'Void';
            $value_in = date("H:i:s" , strtotime($info['in_time'] ));

        }

        if($info['man_out'] == '1'){
            $man_out = 'Active';
            $value_out = $default_check_out;

        }else{
            $man_out = 'Void';
            $value_out = date("H:i:s" , strtotime($info['out_time'] ));

        }

        $row[] = array(
            'pin'=>$emp['finger_id'],
            'nik'=>"'" . $emp['nik'],
            'name'=>  $emp['realname'],
            
            'date'=>  $date,
            'shift'=>  $shf['shift_code'],
            'sch_in'=>  date("H:i:s" , strtotime( $sched[$nday]['in'])),
            'sch_out'=>  date("H:i:s" , strtotime( $sched[$nday]['out'])),
            
            'clc_in'=>  $value_in , 
            'clc_out'=> $value_out,
            'man_in'=> $man_in,
            'man_out'=> $man_out,
            
            'remark'=> $info['remark'],
            
            
        );
    
    }
    
    
    
    $datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 15 ,false ); 
}





#--------------------------------------------------------------- Processing area ---------------------

function calculate_all_emp(){
    
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    
    $month_dates = get_range_date_of_month($periode_year , $periode_month );
    
    
    
    //List of fingerID of emps
    $query = "SELECT finger_id , shift_id FROM emp LIMIT 0 , 700 ";
    $result = my_query($query);
    $n = 0;
    while($emps = my_fetch_array($result) ){
        
        if( (int)  $emps['shift_id']  == 0)continue;
            
        //Clear all datas of emp
        $query_delete = "DELETE FROM attendance_status_log 
                    WHERE finger_id = {$emps['finger_id']} 
                    AND ( attendance_date BETWEEN '{$month_dates['start_date']}' AND '{$month_dates['end_date']}' ) " ;  
        my_query($query_delete);
        
        //Calculate per emp
        calculate_by_emp($emps['finger_id'] , $emps['shift_id']  , $month_dates['start_date'] , $month_dates['end_date'] );
        $n++;
    }
    return $n;
}

function calculate_by_emp($finger_id , $shift_id , $startdate , $enddate ){
    /*
    $emp = my_get_data_by_id('emp' , 'finger_id' , $finger_id );
    $shift_id = $emp['shift_id'];
    */
    
    
    $query = "SELECT * FROM shift_group_times WHERE shift_id = {$shift_id} ORDER BY day_id ";
    $result = my_query($query);
    $sched = array();
    while($ey = my_fetch_array($result)){
        $sched[$ey['day_id']] = array(
                'in_low'=>$ey['lower_in_time_range'],
                'in_high'=>$ey['higher_in_time_range'],
                'out_low'=>$ey['lower_out_time_range'],
                'out_high'=>$ey['higher_out_time_range'],
                'weekend'=>$ey['default_off_day'],
            );
    }
    
    $dates = list_kalender( $startdate  , $enddate);
    foreach( $dates as $date ){
        $nday = date('N' , strtotime($date) );
        
        
        $inx_time = get_swap_in_time( $finger_id , $date, $sched[$nday]['in_low'] , $sched[$nday]['in_high']  );
        $out_time = get_swap_out_time( $finger_id , $date,$sched[$nday]['out_low'] , $sched[$nday]['out_high']  );
        if( $nday == 6 or $nday == 7 ){
            $inx_time = $out_time = '00:00:00';
        }
        
        $absen_status =  present_status_emp( $finger_id , $date , $inx_time , $out_time , $sched[$nday]['weekend'] );
         
        $sts = $absen_status ? $absen_status['present_status'] : 1;
        $remark = $absen_status ? $absen_status['remark'] :  '';
        
        
        $datas = array();
        $datas['finger_id']         = my_type_data_int($finger_id);
        $datas['attendance_date']   = my_type_data_str($date);
        $datas['absen_type_id']     = my_type_data_int( $absen_status['absen_type_id'] );
        $datas['in_time']           = my_type_data_str($inx_time);
        $datas['out_time']          = my_type_data_str($out_time);
        $datas['present_status']    = my_type_data_str($sts);
        $datas['remark']            = my_type_data_str($remark); 
        my_insert_record('attendance_status_log' , $datas );
    }
    return true;
}

function present_status_emp( $finger_id , $date , $in_time , $out_time , $shifts_default_off_status){
    
    //Check absen logs
    if( $request_absen = present_status_by_absen_log( $finger_id , $date ) ){
        return $request_absen;
    }
    elseif( $weekend = present_status_by_default_of_days($shifts_default_off_status) ){
        
        return $weekend;
    }
    //Check finger in out
    return present_status_by_in_out_clock( $in_time , $out_time  );
}

function present_status_by_default_of_days( $shifts_default_off_status ){
    if( $shifts_default_off_status == '1' ){
        $datas = array();
        $datas['absen_type_id'] = '0';
        $datas['present_status'] = '0';
        $datas['remark'] = 'Reguler weekend';
        return $datas;
    }
    return false;
}

function present_status_by_in_out_clock( $in_time , $out_time  ){
    
    if( (trim($in_time) == "")   and ( trim($out_time ) == "" ) ){
        $datas = array();
        $datas['absen_type_id'] = '0';
        $datas['present_status'] = '0';
        $datas['remark'] = 'Finger status not found (IO1)';
        return $datas;
    } 
    if( $in_time == '00:00:00' and  $out_time == '00:00:00' ){
        $datas = array();
        $datas['absen_type_id'] = '0';
        $datas['present_status'] = '0';
        $datas['remark'] = 'Finger status not found (IO2)';
        return $datas;
    } 
    return false;
}

function present_status_by_absen_log( $finger_id , $date ){
    $query = "
        SELECT a.absen_type_id , a.remark , b.present_status FROM absen_logs a 
        INNER JOIN absen_types b ON a.absen_type_id = b.type_id 
        WHERE a.request_date = '{$date}' AND a.emp_finger_id = {$finger_id}
            ";
    $result = my_query($query);
    if( my_num_rows($result) > 0 ){
        $row = my_fetch_array($result);
        return $row;
    }
    return false;
}
 

function get_swap_in_time( $finger_id , $date, $lower_time , $higer_time ){
   /* $jam = rand(7, 8);
    $menit = rand(25,40);
    return sprintf('%02d' , $jam).':'.$menit.":00";*/
    $query = "SELECT swap_datetime_log FROM swaptime 
        WHERE finger_id = {$finger_id} AND ( swap_datetime_log BETWEEN '{$date} {$lower_time}' AND '{$date} {$higer_time}' ) 
        ORDER BY swap_datetime_log ASC LIMIT 1";
    
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['swap_datetime_log'];
}

function get_swap_out_time( $finger_id , $date, $lower_time , $higer_time ){
  /*  $jam = rand(16, 17);
    $menit = rand(25,40);
    return sprintf('%02d' , $jam).':'.$menit.":00";*/
    $query = "SELECT swap_datetime_log FROM swaptime 
        WHERE finger_id = {$finger_id} AND ( swap_datetime_log BETWEEN '{$date} {$lower_time}' AND '{$date} {$higer_time}' ) 
        ORDER BY swap_datetime_log DESC LIMIT 1 ";
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['swap_datetime_log'];
}