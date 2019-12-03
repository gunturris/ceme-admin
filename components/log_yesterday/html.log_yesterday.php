<?php

function list_log(){ 
	global $box;
	$header = array(
		'FingerID'=>array( 'width'=>'15%','style'=>'text-align:center;'),    
		'Swap Time'=>array( 'width'=>'20%','style'=>'text-align:left;'), 
		'Machine'=>array('width'=>'25%','style'=>'text-align:left;'),  
		'Downtime'=>array('width'=>'20','style'=>'text-align:center;'), 
		'Synch'=>array('width'=>'10%','style'=>'text-align:center;'), 
	);
	$query = " SELECT * FROM  swaptime
		WHERE DATE( swap_datetime_log ) = ( curdate() - INTERVAL 1 DAY ) 
		ORDER BY swap_datetime_log DESC ";
	$result = my_query($query );
	
	
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
	
	
	
	
	$row = array();
	$n=1;
	while( $ey = my_fetch_array($result) ){
	
		$row[] = array(
			'Fid'=>position_text_align ($ey['finger_id'], 'center'), 
			'swaptime'=>position_text_align ($ey['swap_datetime_log'], 'center'),   	 
			'mesin'=>  $ey['mecine_finger_id'] ,  
			'downtime'=> position_text_align($ey['datetime_added'],   'center'), 
			'synch'=> position_text_align($ey['synch_status'],   'center'), 
		);
		
		$n++;
	}
	
	$datas = table_rows($row); 
	 
	$navigasi = array(
	//	'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
	//	'<input class="button" type="button" value="Excel" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=excel_export\'"/>'
	); 
	 
	$box = header_box( '&nbsp;' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($header , $datas , 5 , false , $paging  ); 
}