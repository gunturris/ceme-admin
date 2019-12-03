<?php

function list_shifts(){
    
    global $box;
	$header = array(    
		'Code'=>array('style'=>'text-align:center;border-bottom:2px solid;width:35%'),  
		'Shift name'=>array('style'=>'text-align:center;border-bottom:2px solid;width:54%'),  
		'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
	);
    
    
    $query = "SELECT * FROM shift_group  LIMIT 100";
    $result = my_query($query);
    $row = array();
    while( $ey = my_fetch_array($result) ){
	   $editproperty = array(
						'href'=>'index.php?com='.$_GET['com'].'&task=edit&id='.$ey['shift_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
  
		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:confirmDelete('.$ey['shift_id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
        $detailproperty = array(
						'href'=>'index.php?com=ref_shift&task=detail&id='.$ey['shift_id'], 
						'title'=>'Detail'
				);
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
        
		$row[] = array( 
			'code'=>  position_text_align (  $ey['shift_code'] ,   'center'), 
			'name'=> $ey['shift_group_name'],
			'action'=> position_text_align ( $detail_button." ".$edit_button ." ". $delete_button ,   'center')
		);
		 
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="submit-green" type="button" value="Add data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	//	'<input class="button" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi ); 
	return table_builder($header , $datas , 4 , false    ); 
    
    
}

function list_shift_days($shift_id){
    
    $sh = my_get_data_by_id('shift_group' , 'shift_id' , $shift_id );
     
    $view = '
    <table width="100%">
        
        <tr>
            <td width="15%" style="border-right:0"><b>Shift ID</b></td>
            <td width="84%">'. $sh['shift_code'] .' /'. $sh['shift_group_name'].'</td>
        </tr>  
    
    </table>
    ';
    return $view . list_days($shift_id);
}

function shift_submit($id = 0){
    $datas = array();
    $datas['shift_code'] = my_type_data_str($_POST['shift_code']);
    $datas['shift_group_name'] = my_type_data_str($_POST['shift_group_name']);
    
    if( $id > 0 ){
        return my_update_record('shift_group' , 'shift__id' , $id , $datas);
    }
    $nid = my_insert_record('shift_group' , $datas);
    
    return shift_detail_generate($nid);
}

function shift_detail_generate($shift_id){
    $defTime = '00:00:00';
    for($i = 1 ; $i<=7; $i++){
        $datas = array();
        $datas['shift_id'] = my_type_data_int($shift_id);
        $datas['day_id'] = my_type_data_int($i); 
        
        $datas['schedule_in_time'] = my_type_data_str($defTime);
        $datas['lower_in_time_range'] = my_type_data_str($defTime);
        $datas['higher_in_time_range'] = my_type_data_str($defTime);
        
        $datas['schedule_out_time'] = my_type_data_str($defTime);
        $datas['lower_out_time_range'] = my_type_data_str($defTime);
        $datas['higher_out_time_range'] = my_type_data_str($defTime);
        
        $datas['default_off_day'] = my_type_data_int(0);
        
        my_insert_record('shift_group_times' , $datas);
        
    }
    return true;
}

function shift_form($id = 0){

    
	$view = form_header( "form_shift_add" , "form_shift_add"  );
	$fields = my_get_data_by_id('shift_group','shift_id', $id);
 
	 
	$shift_code = array(
			'name'=>'shift_code',
			'value'=>(isset($_POST['shift_code'])? $_POST['shift_code'] : $fields['shift_code']),
			'id'=>'shift_code',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_shift_code = form_dynamic($shift_code);
	$view .= form_field_display( $form_shift_code  , "Shift code"  );
    
	$shift_group_name	 = array(
			'name'=>'shift_group_name',
			'value'=>(isset($_POST['shift_group_name'])? $_POST['shift_group_name'] : $fields['shift_group_name']),
			'id'=>'shift_group_name',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_name = form_dynamic($shift_group_name);
	$view .= form_field_display( $form_name  , "Shift name"  );
    
     
    
    $submit = array(
		'value' => ( $id ==0 ? ' Simpan ' :'  Update  '),
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



function list_days($shift_id){
    
    $header = array(         
		'Day label'=>array('style'=>'text-align:center;border-bottom:2px solid;width:20%'),        
		
        'Schedule In '=>array('style'=>'text-align:center;border-bottom:2px solid;width:12%'),  
		'Scan In <br/>Open'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Scan In <br/>Close'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10o%'), 
        
        'Schedule Out '=>array('style'=>'text-align:center;border-bottom:2px solid;width:12%'),  
		'Scan Out <br/>Open'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Scan Out <br/>Close'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'), 
        
		'Off day<br/>Default'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'), 
        
        
		'Act'=>array('style'=>'text-align:center;border-bottom:2px solid;width:6%'),     
	);

    
    $query = "SELECT * FROM shift_group_times WHERE shift_id = {$shift_id} ORDER BY day_id ASC";
    $result = my_query($query);
    $row = array();
    while( $ey = my_fetch_array($result) ){
        
        
	   $editproperty = array(
						'href'=>'index.php?com='.$_GET['com'].'&task=edit_detail&day='.$ey['day_id'].'&id='.$ey['shift_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );
        
        $off_day_default = $ey['default_off_day'] == '1' ? 'Yes' : 'No';
        $row[] = array(
            'day'   => get_day_by_init($ey['day_id']) ,
            'schin' => position_text_align($ey['schedule_in_time'],'center'),
            'openin' => position_text_align($ey['lower_in_time_range'],'center'),
            'closein' => position_text_align($ey['higher_in_time_range'],'center'),
            'schout' => position_text_align($ey['schedule_out_time'],'center'),
            'openout' => position_text_align($ey['lower_out_time_range'],'center'),
            'closeout' => position_text_align($ey['higher_out_time_range'],'center'),
            'off_reguler' => position_text_align($off_day_default, 'center'),
            'act' =>position_text_align($edit_button , 'center')
        );
    }
    
    $datas = table_rows($row);
    return table_builder($header , $datas , 8, false    );
}

function get_day_by_init($init){
    $days  = array(
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday' ,
        7 => 'Sunday',
    );
    return $days[$init];
}

function shift_detail_form( $day_id , $shift_id = 0){

    
    
    my_set_file_js(
		array(
			'assets/jquery/clockpicker/clockpicker.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
    my_set_file_css(
        array(
            'assets/jquery/clockpicker/clockpicker.css',
            'assets/jquery/clockpicker/standalone.css',
        ) 
    );
    
	$view = form_header( "form_shift_detail_add" , "form_shift_detail_add"  );
    
    $shift = my_get_data_by_id('shift_group','shift_id', $shift_id);
    
    $query = "SELECT * FROM shift_group_times WHERE day_id = {$day_id} AND shift_id = {$shift_id}";
    $result = my_query($query);
	$fields = my_fetch_array($result);
 
    
	$view .= form_field_display( '&nbsp; &nbsp; &nbsp; '.$shift['shift_code'] ."/ ".$shift['shift_group_name'], "<b>Shift info</b>"  );
	$view .= form_field_display( '&nbsp; &nbsp; &nbsp; '. get_day_by_init($day_id) , "<b>Dayname</b>"  );
	 
	$schedule_in_time = array(
			'name'=>'schedule_in_time',
			'value'=>(isset($_POST['schedule_in_time'])? $_POST['schedule_in_time'] : $fields['schedule_in_time']),
			'id'=>'schedule_in_time',
			'type'=>'textfield',
			'size'=>'15'
		);
    
    $formsa = '
    <!-- https://weareoutman.github.io/clockpicker/jquery.html //-->
    <div class="input-group clockpicker">
    <input type="text" class="form-control" value="09:30">
    <span class="input-group-addon">
        <span class="glyphicon glyphicon-time"></span>
    </span>
</div>
<script type="text/javascript">
$(\'.clockpicker\').clockpicker();
</script>';
	$form_schedule_in_time = form_dynamic($schedule_in_time);
	$view .= form_field_display( /* $formsa */ $form_schedule_in_time, "Schedule in"  );
    
	$lower_in_time_range	 = array(
			'name'=>'lower_in_time_range',
			'value'=>(isset($_POST['lower_in_time_range'])? $_POST['lower_in_time_range'] : $fields['lower_in_time_range']),
			'id'=>'lower_in_time_range',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_lower_in_time_range = form_dynamic($lower_in_time_range);
	$view .= form_field_display( $form_lower_in_time_range  , "Scan in open"  );
    
	$higher_in_time_range	 = array(
			'name'=>'higher_in_time_range',
			'value'=>(isset($_POST['higher_in_time_range'])? $_POST['higher_in_time_range'] : $fields['higher_in_time_range']),
			'id'=>'higher_in_time_range',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_higher_in_time_range = form_dynamic($higher_in_time_range);
	$view .= form_field_display( $form_higher_in_time_range  , "Scan in close"  );
    
     
	$schedule_out_time = array(
			'name'=>'schedule_out_time',
			'value'=>(isset($_POST['schedule_out_time'])? $_POST['schedule_out_time'] : $fields['schedule_out_time']),
			'id'=>'schedule_out_time',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_schedule_out_time = form_dynamic($schedule_out_time);
	$view .= form_field_display( $form_schedule_out_time  , "Schedule out"  );
    
	$lower_out_time_range	 = array(
			'name'=>'lower_out_time_range',
			'value'=>(isset($_POST['lower_out_time_range'])? $_POST['lower_out_time_range'] : $fields['lower_out_time_range']),
			'id'=>'lower_out_time_range',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_lower_out_time_range = form_dynamic($lower_out_time_range );
	$view .= form_field_display( $form_lower_out_time_range  , "Scan out open"  );
    
	$higher_out_time_range	 = array(
			'name'=>'higher_out_time_range',
			'value'=>(isset($_POST['higher_out_time_range'])? $_POST['higher_out_time_range'] : $fields['higher_out_time_range']),
			'id'=>'higher_out_time_range',
			'type'=>'textfield',
			'size'=>'15'
		);
	$form_higher_out_time_range = form_dynamic($higher_out_time_range );
	$view .= form_field_display( $form_higher_out_time_range  , "Scan out close"  );
    
    
    $default_off_day	 = array(
			'name'=>'default_off_day',
			'value'=>(isset($_POST['default_off_day'])? $_POST['default_off_day'] : $fields['default_off_day']),
			'id'=>'default_off_day' 
		);
    $yn = array(0 => 'No' , 1 => 'Yes');
	$form_default_off_day = form_radiobutton($default_off_day , $yn );
	$view .= form_field_display( $form_default_off_day  , "Default off day"  );
    
    
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

function shift_detail_validate(){
    $errsubmit = false;
	$err = array();
    
	
	if( ! is_time_format($_POST['schedule_in_time']) ){
		$errsubmit = true;
		$err[] = "Invalid schedule in time format";
	} 
     
	if( ! is_time_format($_POST['lower_in_time_range']) ){
		$errsubmit = true;
		$err[] = "Invalid scan in open time format";
	} 
    
	if( ! is_time_format($_POST['higher_in_time_range']) ){
		$errsubmit = true;
		$err[] = "Invalid scan in close time format";
	} 
    
    
	if( ! is_time_format($_POST['schedule_out_time']) ){
		$errsubmit = true;
		$err[] = "Invalid schedule out time format";
	} 
    
	if( ! is_time_format($_POST['lower_out_time_range']) ){
		$errsubmit = true;
		$err[] = "Invalid scan out  open time format";
	} 
    
	if( ! is_time_format($_POST['higher_out_time_range']) ){
		$errsubmit = true;
		$err[] = "Invalid scan out  close time format";
	} 
     
    
	if( $errsubmit){
		return $err;
	}
	return false;
}

 

function shift_detail_submit( $day_id , $shift_id = 0){
    $query = " UPDATE shift_group_times SET ";
    
    $query .= " schedule_in_time = '{$_POST['schedule_in_time']}' , ";
    $query .= " lower_in_time_range = '{$_POST['lower_in_time_range']}' , ";
    $query .= " higher_in_time_range = '{$_POST['higher_in_time_range']}'  , ";
    
    $query .= " schedule_out_time = '{$_POST['schedule_out_time']}'  , ";
    $query .= " lower_out_time_range = '{$_POST['lower_out_time_range']}'  , ";
    $query .= " higher_out_time_range = '{$_POST['higher_out_time_range']}' , ";
    
    $query .= " default_off_day = '{$_POST['default_off_day']}'  ";
    
    $query .= " WHERE day_id = {$day_id} AND shift_id = {$shift_id} "; 
    return my_query($query);
}