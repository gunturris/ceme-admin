<?php
function user_overtime_log(){
    
    my_set_code_js('
    function openCalculateOvertimeModal(){ 
        jQuery.facebox({ ajax: \'index.php?com='.$_GET['com'].'&task=calculate_overtime\' });
        return;
    }
    ');
    
    global $box;
	$header = array(  
		'Employee Name'=>array('style'=>'text-align:center;border-bottom:2px solid;width:35%'),  
		'Periode'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'), 		
		'Total Time'=>array('style'=>'text-align:center;border-bottom:2px solid;width:15%'),  
		'Total Hours'=>array('style'=>'text-align:center;border-bottom:2px solid;width:15%'),  
		'Compensated OT <br/>(in Hours)'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Compensated OT <br/>(in Days)'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Action'=>array('style'=>'text-align:center;border-bottom:2px solid;width:5%'),  
	);
	 
    if( isset($_GET['keyword'])){
	   $query = "SELECT * FROM emp WHERE nik = '{$_GET['keyword']}' ";
           
    }else{ 
	   $query = "SELECT * FROM emp";
	
    }
	$result = my_query($query);
	
	//PAGING CONTROL START
	$total_records = my_num_rows($result );
	$scroll_page = SCROLL_PERHALAMAN;  
	$per_page = PAGING_PERHALAMAN;  
	$current_page = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1 ; 
	if($current_page < 1){
		$current_page = 1;
	}		 
	$task = isset($_GET['task']) ?$_GET['task'] :'' ;
	$field = isset($_GET['field']) ?$_GET['field'] :'' ;
	$key = isset($_GET['key']) ?$_GET['key'] :'' ;
	$pager_url  ="index.php?com={$_GET['com']}&task={$task}&field={$field}&key={$key}&halaman=";	 
	$pager_url_last='';
	$inactive_page_tag = 'style="padding:4px;background-color:#BBBBBB"';  
	$previous_page_text = ' Prev '; 
	$next_page_text = ' Next ';  
	$first_page_text = ' First '; 
	$last_page_text = ' Last ';
	
	$kgPagerOBJ = new kgPager();
	$kgPagerOBJ->pager_set(
		$pager_url, 
		$total_records, 
		$scroll_page, 
		$per_page, 
		$current_page, 
		$inactive_page_tag, 
		$previous_page_text, 
		$next_page_text, 
		$first_page_text, 
		$last_page_text ,
		$pager_url_last
		); 
	 		
	$result = my_query($query ." LIMIT ".$kgPagerOBJ->start.", ".$kgPagerOBJ->per_page);  
	$i = ($current_page  - 1 ) * $per_page ;
	//PAGING CONTROL END
	
	
	$genders = array(
        0   => 'Female',
        1   => 'Male',
    );
	
    $shifts = array();
    $query_shifts = "SELECT * FROM shift_group";
    $result_shift = my_query($query_shifts);
    $shifts[0] = 'N/A';
    while( $row_shift = my_fetch_array($result_shift) ){
        $shifts[$row_shift['shift_id']] = $row_shift['shift_code'] .' / ' . $row_shift['shift_group_name'];
    }
    
    
    $departemens = array();
    $query_deps = "SELECT * FROM departments";
    $result_deps = my_query($query_deps);
    $departemens[0] = 'N/A';
    while( $row_dep = my_fetch_array($result_deps) ){
        $departemens[$row_dep['departemen_id']] = $row_dep['departemen_code'] .' / ' . $row_dep['departemen_name'];
    }
    
    $periode = $_SESSION['periode_year'] ."-". $_SESSION['periode_month'] ."-01";
        
	$row = array();
	$n=1;
	
	while( $ey = my_fetch_array($result) ){
	   $editproperty = array(
						'href'=>'index.php?com=user_add&task=edit&id='.$ey['finger_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$detailproperty = array(
						'href'=>'index.php?com=user_overtime_detail&task=detail&id='.$ey['finger_id'], 
						'title'=>'Detail'
				);
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
         
        $total_time = get_total_ot_periode($periode , $ey['finger_id']);
		$row[] = array( 
			'employee'    => $ey['nik'] ."/ ".$ey['realname'],  	 
			'periode'=>  position_text_align ( $_SESSION['periode_year'] ."-". $_SESSION['periode_month'] ,   'center'), 
			'total_time'=>  position_text_align ($total_time ,   'center'), 
			'total_hour'=> position_text_align ( time_to_decimal($total_time) , 'center'),  
			'cot_hour'=> position_text_align (  '?' , 'center'),  
			'cot_days'=> position_text_align ( '?' , 'center'),  
			'action'=> position_text_align ($detail_button   ,   'center')
		);
        
		$n++;
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		
		'<input class="submit-green" type="button" value="Create new" onclick="javascript:location.href=\'index.php?com=user_overtime&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Calculate All" onclick="javascript:openCalculateOvertimeModal();"/>',
		//'<input class="submit-green" type="button" value="Calculate All" onclick="javascript:location.href=\'index.php?com=user_overtime&task=calc&gen='.md5(rand(1000,9999)).'\'"/>',
	 	'<input class="submit-green" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
    
    $search_form = '<div style="width:350px;float:left"><form method="GET">
    <input type="hidden" name="com" value="user_overtime" />
    Find emp code : <input type="text" name="keyword" value="" style="height:22px;"/>
    <input type="submit" name="by" value=" GO " />
    </form></div>';
	$box = header_box($search_form, $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 5 , false , $paging  ); 
}

function get_total_time_info( $periode , $finder_id){
    
    list($yyyy , $mm , $dd) = explode("-", $periode);
	$start_date = $periode;
	$end_date = $yyyy ."-" . $mm . "-". date('t' , strtotime($periode)); 
	return 0;
} 


function get_total_ot_periode($periode , $finger_id ){
	list($yyyy , $mm , $dd) = explode("-", $periode);
	$start_date = $periode;
	$end_date = $yyyy ."-" . $mm . "-". date('t' , strtotime($periode));
	
	$query = "SELECT SUM(sum_hour) AS total_jam FROM overtime_log WHERE emp_finger_id = {$finger_id} AND ( overtime_date BETWEEN '{$start_date}' AND '{$end_date}' ) ";
	$result = my_query($query);
	$row = my_fetch_array($result);
	return (float) $row['total_jam'];
} 

function get_sub_time($start , $end ){
	
	$start = strtotime($start);
	$end = strtotime($end);
	
	$total = $end - $start;
	$hours = floor($total / 3600 );
	$minutes = round( ($total - ($hours * 3600) ) / 60 );
	
	$hours = sprintf('%02d' , $hours);
	$minutes = sprintf('%02d' , $minutes);
	$minutes = sprintf('%02d' , $minutes);
	return   "{$hours}:{$minutes}:00"; 
}

function time_to_decimal($time) {
	 
    $timeArr = explode(':', $time);
	
	if( count($timeArr ) > 1 ){
		$decTime = ($timeArr[0]*60) + ($timeArr[1])  ;
		
	}else{
		$decTime = ($timeArr[0]*60);
		
	}
 
    $fixed = round($decTime/60 , 2 );
    return number_format($fixed,2);
}


function form_overtime_activity_log($id){
    
    my_set_file_js(
		array(
			'assets/jquery/clockpicker/clockpicker.js',
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/jquery/autocomplete/jquery.autocomplete.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
    my_set_file_css(
        array(
            'assets/jquery/clockpicker/clockpicker.css',
            'assets/jquery/clockpicker/standalone.css',
            'assets/jquery/autocomplete/jquery.autocomplete.css'
        ) 
    );
      
    
    my_set_code_js('  
		function findValue(li) {
			if( li == null ) return alert("No match!"); 
			if( !!li.extra ) var sValue = li.extra[0]; 
			else var sValue = li.selectValue;
		}

		function selectItem(li) {
			findValue(li);
		}

		function formatItem(row) {
			return   row[0] ;
		}


		function lookupAjax(){
			var oSuggest = $("#nama_karyawan")[0].autocompleter;
			oSuggest.findValue(); 
			return false;
		}
		$(document).ready(function() {
			$("#nama_karyawan").autocomplete(
				"ajax_check_emp.php",{ com: \'off_day\' ,task: \'ajax_check_name\'  },
				{
					delay:10,
					minChars:2,
					matchSubset:1,
					matchContains:1,
					cacheLength:5,
					onItemSelect:selectItem,
					onFindValue:findValue,
					formatItem:formatItem,
					autoFill:true
				}
			);
			 
		});
        
        /*
		function checkName(name){
			$.get("check_karyawan_names.php", { nama: name  },
			   function(data){
				 if(data ==  \'0\' ){
					alert(\'Nama karyawan \'+ name+\' tidak ditemukan\nHarap diperiksa kembali\');
					$(\'#nama_karyawan\').val(\'\');
					return false;
				 } 
			   });
		}
        */
	');
    
	$view = form_header( "form_overtime_add" , "form_overtime_add"  );
    
    $fields = my_get_data_by_id('overtime_activity_log','id', $id); 
    
    $empinfo = my_get_data_by_id('emp','finger_id', (int) $fields['emp_finger_id']);
    
	$nik = array(
			'name'=>'nik',
			'value'=>(isset($_POST['nik'])? $_POST['nik'] : ( $empinfo ? $empinfo['nik'].'/ '.$empinfo['realname']  : "" ) ),
			'id'=>'nama_karyawan',
			'type'=>'textfield',
			'size'=>'45'
		);
	$form_finger_id = form_dynamic($nik);
	$view .= form_field_display( $form_finger_id  , "Employee <font size='1'>(Checking by code)</font>"  );
    
    if(  isset($fields['request_date'] ) ){
        $date_r = $fields['request_date'];
        
    }else{
        $date_r = date("Y-m-d");
        
        
    }
    $request_date = array(
			'name'=>'request_date',
			'value'=>(isset($_POST['request_date'])? $_POST['request_date'] : $date_r),
			'id'=>'request_date', 
			'size'=>'45'
		);
	$form_request_date = form_calendar($request_date);
	$view .= form_field_display( $form_request_date  , "Overtime date"  );
    
    $start_time	 = array(
			'name'=>'start_time',
			'value'=>(isset($_POST['start_time'])? $_POST['start_time'] : $fields['start_time']),
			'id'=>'start_time',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_start_time= form_dynamic($start_time );
	$view .= form_field_display( $form_start_time  , "OT start "  );
    
	$end_time	 = array(
			'name'=>'end_time',
			'value'=>(isset($_POST['end_time'])? $_POST['end_time'] : $fields['end_time']),
			'id'=>'end_time',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_end_time = form_dynamic($end_time );
	$view .= form_field_display( $form_end_time  , "OT end"  );
    
    
    $remark	 = array(
			'name'=>'remark',
			'value'=>( isset($_POST['remark']) ? $_POST['remark'] : $fields['remark'] ),
			'type'=>'textfield',
			'size'=>'35',
			'id'=>'remark' 
		);
	$form_remark = form_dynamic($remark );
	$view .= form_field_display( $form_remark  , "Remark"  );
    
    
    $submit = array(
		'value' =>  '  Update  ' ,
		'name' => 'simpan', 
		'type'=>'submit','class'=>'submit-green'
	);
	$form_submit= form_dynamic($submit); 
	 
	$cancel = array(
		'value' => ( '  Batal  '),
		'name' => 'cancel', 
		'type'=>'button',
		'onclick'=>'location.href=\'index.php?com='.$_GET['com'].'\'',
		'class'=>'submit-gray'
	);
	$form_cancel = form_dynamic($cancel );
	 
	$view .= form_field_display( $form_submit .' '.$form_cancel   , "<hr />"  );
	$view .= form_footer( );	
	
	return  $view;
}

function form_overtime_activity_submit($id){
    $datas = array();
    
    $emp = explode("/" , $_POST['nik'] );
     
    $nik = trim( $emp[0] );
    $emp_exists = get_emp_by_nik_name($nik );
    $datas['emp_finger_id']     = my_type_data_int( $emp_exists['finger_id'] );
    $datas['request_date']      = my_type_data_str( $_POST['request_date'] );
    $datas['start_time']        = my_type_data_str( $_POST['start_time'] );
    $datas['end_time']          = my_type_data_str( $_POST['end_time'] );
    $datas['remark']            = my_type_data_str( $_POST['remark'] );
    
    if( $id > 0 ){
        my_update_record( 'overtime_activity_log' , 'id' , $id , $datas);
    
    }
    
    $id = rand(1000000, 9999999);
    
    $datas['id']            = my_type_data_int($id);
    $datas['created_on']    = my_type_data_function( 'NOW()' );
    
    my_insert_record( 'overtime_activity_log' , $datas);
    
    update_overtime_log( $emp_exists['finger_id'] , $_POST['request_date'] , $_POST['start_time'] , $_POST['end_time']  );
    return $emp_exists['finger_id'];
}

function update_overtime_log($finger_id , $date , $start_time , $end_time ){
    $query = "UPDATE overtime_log SET ot_start_time = '{$start_time}' , ot_end_time ='{$end_time}' 
			WHERE overtime_date ='{$date}' AND emp_finger_id = {$finger_id} ";
     
    return my_query($query);
}

function overtime_activity_log_validation(){
	$errsubmit = false;
	$err = array();
	$emp = explode("/" , $_POST['nik'] );
     
    $nik = trim( $emp[0] );
    $emp_exists = get_emp_by_nik_name($nik );
     
	if( $_POST['nik'] == ""){
		$errsubmit = true;
		$err[] = "Employee data empty";
	}elseif( ! $emp_exists ){
        $errsubmit = true;
		$err[] = "Employee data not found";
    }
	
	if( ! is_time_format($_POST['start_time']) ){
		$errsubmit = true;
		$err[] = "Invalid OT start time format";
	} 
    
    if( ! is_time_format($_POST['end_time']) ){
		$errsubmit = true;
		$err[] = "Invalid OT end time format";
	} 
    
    
	if(trim($_POST['remark']) == ''){
		$errsubmit = true;
		$err[] = "Remark is empty";
	} 
    
     
    
	if( $errsubmit){
		return $err;
	}
	return false;

}

// ------------------calculation overtime --------------------

function calculation_overtime_all( ){
      
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
	$query_reset = "DELETE FROM overtime_log
			WHERE MONTH( overtime_date ) = '{$periode_month}' AND  YEAR( overtime_date ) = '{$periode_year}' ";
	my_query($query_reset);
    
    $query = "SELECT * FROM emp LIMIT 700";
    $result = my_query($query);
    
    while( $row = my_fetch_array($result)){
        
        calculation_overtime_emp($row['finger_id'] , $row['shift_id']  );
    }
    return true;
}

function calculation_overtime_emp($finger_id , $shift_id ){
     
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    
    $periode =  get_range_date_of_month( $periode_year , $periode_month );
    
    $query = "SELECT * FROM shift_group_times WHERE shift_id = {$shift_id} ORDER BY day_id ";
    $result = my_query($query);
    
    $sched = array();
    while($ey = my_fetch_array($result)){
        $sched[$ey['day_id']] = array(
                'sch_in'=>$ey['schedule_in_time'], 
                'sch_out'=>$ey['schedule_out_time'], 
                'weekend'=>$ey['default_off_day'],
            );
    }
    
    $dates = list_kalender( $periode['start_date']  , $periode['end_date'] );
     
    $ot_activity = get_ot_activity( $finger_id , $periode['start_date']  , $periode['end_date'] );
    
    foreach( $dates as $date ){
        
        $nday = date('N' , strtotime($date) );
        
        $early = get_early_time_in_on_day( $finger_id , $date ); 
        $latest = get_lastest_time_in_on_day( $finger_id , $date );
    
        $sdate =  date('d' , strtotime($date) );
        if ( isset($ot_activity[$sdate]) ){
            $ot_start_time = $ot_activity[$sdate]['start_time'];
            $ot_end_time = $ot_activity[$sdate]['end_time'];
            
        }else{
            $ot_start_time =  $ot_end_time =  '00:00:00';
            
        }
		
		$sum_time = get_sub_time($ot_start_time , $ot_end_time );
		 
        $sum_hour = time_to_decimal($sum_time);
		
        if( is_off_dates($date) ){
            $factor_sum = '2.0';
            
        }else{
            $factor_sum = $sched[$nday]['weekend'] == '1' ? '2.0' : '1.5' ;
            
        }
        
        $datas = array();
        $datas['overtime_date'] = my_type_data_str( $date );
        
        $datas['emp_finger_id'] = my_type_data_str( $finger_id );
        
        $datas['day_var']  = my_type_data_str( $factor_sum  );
        $datas['sched_in']  = my_type_data_str( $sched[$nday]['sch_in']  );
        $datas['sched_out'] = my_type_data_str( $sched[$nday]['sch_out'] );
        
        $datas['time_in'] = my_type_data_str( $early );
        $datas['time_out'] = my_type_data_str( $latest );
        
        $datas['ot_start_time'] = my_type_data_str( $ot_start_time );
        $datas['ot_end_time'] = my_type_data_str( $ot_end_time );
		
		//NEW
        $datas['sum_time'] = my_type_data_str( $sum_time );
        $datas['sum_hour'] = my_type_data_str( $sum_hour );
         	  
		$datas['created_on']   = my_type_data_function( 'NOW()' );
	
	
        my_insert_record( 'overtime_log'  , $datas );
        
    }
    return true;
}

function is_off_dates($date){
    $query = " SELECT * FROM off_dates WHERE off_date = '{$date}' ";
    $result = my_query($query);
    if( my_num_rows($result) > 0 ){
        return true;
    }
    return false;
}

function get_ot_activity( $finger_id , $startdate , $enddate ){
    $query = "
            SELECT * FROM overtime_activity_log 
            WHERE emp_finger_id = {$finger_id} 
            AND ( request_date BETWEEN '{$startdate}' AND '{$enddate}' ) ";
    $result = my_query($query);
    if( my_num_rows($result) > 0){
        $datas = array();
        while( $row = my_fetch_array($result) ){
            $day = date('d' , strtotime( $row['request_date'] ) );
            $datas[$day] = $row;
        }
        return $datas;
    }
    
    return false;
}

function get_early_time_in_on_day($finger_id , $date){
    $query = "SELECT swap_datetime_log FROM swaptime WHERE finger_id = {$finger_id} AND DATE(swap_datetime_log) = '{$date}' ORDER BY swap_datetime_log ASC LIMIT 1 ";
     
    $result = my_query($query);
    if( my_num_rows($result) > 0 ){
        $row = my_fetch_array($result);
        return $row['swap_datetime_log'];
    }
    return false;
}

function get_lastest_time_in_on_day($finger_id , $date){
    $query = "SELECT swap_datetime_log FROM swaptime WHERE finger_id = {$finger_id} AND DATE(swap_datetime_log) = '{$date}' ORDER BY swap_datetime_log DESC LIMIT 1 "; 
    $result = my_query($query);  
    if( my_num_rows($result) > 0 ){
        $row = my_fetch_array($result);
        return $row['swap_datetime_log'];
    }
    return false;
}


function page_kalkulasi_overtime(){
	my_set_code_js('
	$(document).ready(function() {
       	$(\'#onebar\').load(\'index.php?com='.$_GET['com'].'&task=calculation_overtime_process&'.rand(0,9).'='.rand(0,99999).'\'); 
	});
	');
	return '<div id="onebar" style="width:100%;text-align:center;"><img src="assets/jquery/facebox/loading.gif" /><br/>On processing!!!</div>';
}