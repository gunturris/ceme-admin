<?php

function user_list(){
    global $box;
	$header = array( 
		'FingerID'=>array('style'=>'text-align:center;border-bottom:2px solid;width:8%'),   
		'Employee Code'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'),  
		'Employee Name'=>array('style'=>'text-align:center;border-bottom:2px solid;width:22%'), 		
		'Gender'=>array('style'=>'text-align:center;border-bottom:2px solid;width:10%'), 		
		'Unit'=>array('style'=>'text-align:center;border-bottom:2px solid;width:25%'),  
		'Shift'=>array('style'=>'text-align:center;border-bottom:2px solid;width:17%'),  
		'Action'=>array('style'=>'text-align:center;border-bottom:2px solid;width:8%'),  
	);
	
	$query = "SELECT * FROM emp";
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
        
		$row[] = array(
			'fid'    =>position_text_align($ey['finger_id'], 'center'), 
			'nik'    =>position_text_align($ey['nik'], 'center'),   	 
			'realname'=>  $ey['realname'] ,  
			'gender'=>  position_text_align ($genders[$ey['gender']],   'center'), 
			'department'=> position_text_align ( $departemens[$ey['departemen_id']],    'center'), 
			'shift'=> position_text_align ( $shifts[$ey['shift_id']],   'center'), 
			'action'=> position_text_align (  $edit_button ." ". $delete_button ,   'center')
		);
		
		$n++;
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
		'<input class="submit-green" type="button" value="Add data" onclick="javascript:location.href=\'index.php?com=user_add&task=edit&create_new='.rand(1000,9999).'\'"/>',
	 	'<input class="submit-green" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	$box = header_box('&nbsp;', $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 4 , false , $paging  ); 
}

function emp_excel_report(){
    my_component_load('xl_builder' , false);
    $header = array( 
		'PIN'=>array('style'=>'border-bottom:2px solid;'),  
		'NIK'=>array('style'=>'border-bottom:2px solid;'),  
		'Name'=>array('style'=>'border-bottom:2px solid;'),  
		'Shift code'=>array('style'=>'border-bottom:2px solid;'),  
		'Shift label'=>array('style'=>'border-bottom:2px solid;'),  
		'Unit code'=>array('style'=>'border-bottom:2px solid;'),  
		'Unit name'=>array('style'=>'border-bottom:2px solid;') 
    );
	
	$query = "
        SELECT 
            a.finger_id , a.realname , a.nik , 
            b.shift_code , b.shift_group_name , 
            c.departemen_code , c.departemen_name FROM emp a 
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
            'dcode'   =>  $ey['departemen_code'],
            'dlbl'   =>  $ey['departemen_name'],
        
        );
    }
    
    $datas = table_rows_excel($row); 
	return table_builder_excel($header , $datas , 15 ,false ); 
}
