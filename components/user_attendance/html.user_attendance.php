<?php
function user_attendance_list(){
    
    my_set_code_js('
    function openCalculateModal(){ 
        jQuery.facebox({ ajax: \'index.php?com='.$_GET['com'].'&task=calculate_attendance\' });
        return;
    }
    ');
    
    global $box;
	$header = array(    
		'Employee Code'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Employee Name'=>array('style'=>'text-align:center;border-bottom:2px solid;width:22%'), 	  
		'Shift'=>array('style'=>'text-align:center;border-bottom:2px solid;width:18%'),  
		'Period'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Working Days'=>array('style'=>'text-align:center;border-bottom:2px solid;width:12%'),  
		'Off Days'=>array('style'=>'text-align:center;border-bottom:2px solid;width:12%'),  
		'Action'=>array('style'=>'text-align:center;border-bottom:2px solid;width:6%'),  
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
    
	$row = array();
	$n=1;
	
    
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    
    $month_dates = get_range_date_of_month( $periode_year ,$periode_month );
    
	while( $ey = my_fetch_array($result) ){
	   $editproperty = array(
						'href'=>'index.php?com=user_add&task=edit&id='.$ey['finger_id'], 
						'title'=>'Edit'
				);
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$detailproperty = array(
						'href'=>'index.php?com=user_detail&task=detail&id='.$ey['finger_id'], 
						'title'=>'Detail'
				);
		$detail_button = button_icon( 'b_props.png' , $detailproperty  );
        
		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:confirmDelete('.$ey['finger_id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
        $present = count_present_days($ey['finger_id'] ,  $month_dates['start_date'] , $month_dates['end_date'] );
        $offs = count_off_days($ey['finger_id'] ,  $month_dates['start_date'] , $month_dates['end_date'] );
		$row[] = array( 
			'nik'    =>position_text_align($ey['nik'], 'center'),   	 
			'realname'=>  $ey['realname'] , 
			'shift'=> position_text_align ( $shifts[$ey['shift_id']],   'center'), 
			'period'=> position_text_align ( $periode_month."-".$periode_year ,   'center'), 
			'working_day'=> position_text_align ( $present ,   'center'), 
			'off_day'=> position_text_align (  $offs ,   'center'), 
			'action'=> position_text_align ($detail_button  ,   'center')
		);
		
		$n++;
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="submit-green" type="button" value="Calculate All" onclick="javascript:openCalculateModal()"/>',
		//'<input class="submit-green" type="button" value="Calculate All" onclick="javascript:location.href=\'index.php?com=user_detail&task=calc&gen='.md5(rand(1000,9999)).'\'"/>',
	 	'<input class="submit-green" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
    $search_form = '<div style="width:350px;float:left"><form method="GET">
    <input type="hidden" name="com" value="user_attendance" />
    Find emp code : <input type="text" name="keyword" value="" style="height:22px;"/>
    <input type="submit" name="by" value=" GO " />
    </form></div>';
	
    $box = header_box( $search_form , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 4 , false , $paging  ); 
}


function count_present_days($finger_id ,  $starttime , $endtime){
    $query = "SELECT COUNT(*) AS present_days FROM attendance_status_log 
        WHERE finger_id = {$finger_id} AND present_status = 1 AND ( attendance_date BETWEEN '{$starttime}' AND '{$endtime}' )";
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['present_days'];
}

function count_off_days($finger_id ,  $starttime , $endtime){
    $query = "SELECT COUNT(*) AS off_days FROM attendance_status_log 
        WHERE finger_id = {$finger_id} AND present_status = 0 AND ( attendance_date BETWEEN '{$starttime}' AND '{$endtime}' )";
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['off_days'];
}





function user_attendance_excel_report(){
    
    my_component_load('xl_builder' , false);
    
    
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month']; 
    
    $month_dates = get_range_date_of_month( $periode_year ,$periode_month );
    
    
    $header = array( 
		'PIN'=>array('style'=>'border-bottom:2px solid;'),  
		'NIK'=>array('style'=>'border-bottom:2px solid;'),  
		'Name'=>array('style'=>'border-bottom:2px solid;'),  
		'Shift code'=>array('style'=>'border-bottom:2px solid;'),  
		'Shift label'=>array('style'=>'border-bottom:2px solid;'),  
		'Periode'=>array('style'=>'border-bottom:2px solid;'),  
		'Working days'=>array('style'=>'border-bottom:2px solid;') ,
		'Off days'=>array('style'=>'border-bottom:2px solid;') 
    );
	
	$query = "
        SELECT 
            a.finger_id , a.realname , a.nik , 
            b.shift_code , b.shift_group_name  
            FROM emp a 
            INNER JOIN shift_group b ON a.shift_id = b.shift_id 
            INNER JOIN departments c ON a.departemen_id = c.departemen_id ORDER BY a.realname ASC LIMIT 1000";
    $result = my_query($query);    
    
    $row = array();
    while( $ey = my_fetch_array($result) ){
        $row[] = array(
            'pin'   =>   $ey['finger_id'],
            'nik'   => "'". $ey['nik'],
            'name'   =>  $ey['realname'],
            'scode'   =>  $ey['shift_code'],
            'slbl'   =>   $ey['shift_group_name'],
            'periode'   =>   "'" . $periode_month."-".$periode_year,
            'workd'   => count_present_days($ey['finger_id'], $month_dates['start_date'] , $month_dates['end_date']),
            'offd'   =>  count_off_days($ey['finger_id'] ,$month_dates['start_date'] , $month_dates['end_date']),
        
        );
    }
    
    $datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 15 ,false ); 
}


function page_kalkulasi(){
	my_set_code_js('
	$(document).ready(function() {
       	$(\'#onebar\').load(\'index.php?com='.$_GET['com'].'&task=calculation_process&'.rand(0,9).'='.rand(0,99999).'\'); 
	});
	');
	return '<div id="onebar" style="width:100%;text-align:center;"><img src="assets/jquery/facebox/loading.gif" /><br/>On processing!!!</div>';
}