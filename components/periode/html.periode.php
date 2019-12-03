<?php

function list_periodes(){
    
    my_set_code_js(
        '
        function monthEnd(){
            console.log(\'sdfsdf\')
            f = confirm(\'This procedure cannot be undo\nAre you sure to month end?\')
            if( f){ 
                location.href=\'index.php?com=periode&task=monthend\';
            }
            return
        }'
    );
    
    
    global $box;
	$header = array(    
		'Period'=>array('style'=>'text-align:center;border-bottom:2px solid;width:20%'),  
		'Start date'=>array('style'=>'text-align:center;border-bottom:2px solid;width:30%'),  
		'End date'=>array('style'=>'text-align:center;border-bottom:2px solid;width:30%'),  
		'Status'=>array('style'=>'text-align:center;border-bottom:2px solid;width:20%'),   
	);
    
    $query = "SELECT * FROM periode ORDER BY periode_id DESC";
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
    
    $status = array(
        '0' => 'Process',
        '1' => 'Passed',
        '2' => 'Waiting',
    );
    while( $ey = my_fetch_array($result) ){ 
  
		$deleteproperty = array(
						'href'=>'javascript:; ',
						'onclick'=>'javascript:deleteConfirm('.$ey['periode_id'].');',
						'title'=>'Delete'
				);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );
        
        if( $ey['is_close'] == '0' ){
            $sts_label = '<span style="color:red;letter-spacing:1.5px;"><b>'.$status[$ey['is_close']].'</b></span>';
        }else{
            $sts_label = $status[$ey['is_close']];
        }
        
		$row[] = array( 
			'periode'=>  position_text_align (date( 'm-Y' , strtotime($ey['start_date']) ) ,   'center'), 
			'start_date'=>  position_text_align(date( 'd-m-Y' , strtotime($ey['start_date']) ) ,   'center'), 
			'end_date'=> position_text_align(date( 'd-m-Y' , strtotime($ey['end_date']) ) ,   'center'), 
			'status'=> position_text_align ( $sts_label  ,   'center'),
			//'action'=> position_text_align ( $edit_button ." ". $delete_button ,   'center')
		);
		 
	}
	
	$datas = table_rows($row); 
     
	$navigasi = array(
		'<input class="submit-green" type="button" value="Month end" onclick="javascript:monthEnd()"/>',
	//	'<input class="button" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi );
    
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 4 , false ,$paging   ); 
    
}


function month_end(){
    
    $start_date_current_periode = $_SESSION['periode_year']."-".$_SESSION['periode_month']."-01";
    $last_date_current_periode = date("Y-m-t" , strtotime($start_date_current_periode) );
    
    //Check Current Date > LastDateOnPeriode + 2Days
    $is_date_passed = last_date_periode_is_passed();
    var_dump($is_date_passed);
    if( ! $is_date_passed ){
        
        return false;
    }
    
    //Check Calculate Present Already Processed
    $count_calc_attendance_persons = calculation_attendance_passed($last_date_current_periode);
    var_dump($count_calc_attendance_persons);
    if(  $count_calc_attendance_persons < 4 ){
        
        return false;
    } 
    
    //Check Overtime Already Processed
    $count_calc_overtime_persons = calculation_overtime_passed($last_date_current_periode);
    var_dump($count_calc_overtime_persons);
    if(  $count_calc_overtime_persons < 4 ){
        
        return false;
    } 
    
    //Process month end
    my_query("UPDATE periode SET is_close = 1 WHERE start_date = '{$start_date_current_periode}' ");
    
    
    if( $_SESSION['periode_month'] == '12' ){
        $_SESSION['periode_month'] = '01';
        $_SESSION['periode_year'] = $_SESSION['periode_year'] + 1;
        
    }else{ 
        $newMonth = 1 + (int) $_SESSION['periode_month'] ;
        $_SESSION['periode_month'] = sprintf('%02d' , $newMonth );
        
    }
    
    $start_date_new_periode = $_SESSION['periode_year']."-".$_SESSION['periode_month']."-01";
    $last_date_new_periode = date("Y-m-t" , strtotime( $start_date_new_periode ) );
    $datas = array(
        'start_date'    => my_type_data_str( $start_date_new_periode ),
        'end_date'      => my_type_data_str( $last_date_new_periode ),
        'is_close'      => my_type_data_int( 0 ),
    );
    my_insert_record('periode' , $datas );
    
    my_query("TRUNCATE overtime_log");
    my_query("TRUNCATE attendance_status_log");
    return true;
}

function calculation_attendance_passed($last_date_periode){
    $query = "SELECT COUNT(*) AS te FROM attendance_status_log WHERE attendance_date = '{$last_date_periode}' ";
    
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['te'];
}

function calculation_overtime_passed($last_date_periode){
    $query = "SELECT COUNT(*) AS te FROM overtime_log WHERE overtime_date = '{$last_date_periode}' ";
    
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['te'];
}

function last_date_periode_is_passed(){
    
    $periode_year  = $_SESSION['periode_year']; 
    $periode_month = $_SESSION['periode_month'];
    
    $last_date_periode = date("Y-m-t" , strtotime($periode_year."-".$periode_month."-01" ) );
    
    // ? Apakah tanggal sekarang lebih besar 3 hari
    $date = new DateTime($last_date_periode); // Y-m-d
    $date->add(new DateInterval('P2D'));    #Tambah 3 hari
    
    if( time() > strtotime($date->format('Y-m-d') )  ){
        return true;
    }
    return false;
}