<?php

function list_statistic_payout_megajackpot( $player = 0){ 
     
	my_set_code_js('
		function confirmDelete(id){
            var t = confirm(\'Yakin akan menghapus data ?\');
                if(t){
                    location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
                }
                return false;
            }
        ');	
    
	$headers= array( 
		'Date' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Payout Jackpot' => array( 'style' => 'width:70%;text-align:right;' ),
         
	);

	
	  
$query = "
select DATE(ts) as ts, SUM(playMoneyChips) as playMoneyChips from player_history group by DATE(ts) order by ts desc";
    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align( $ey['ts'], 'center' ),
            'payout' => position_text_align( rp_format($ey['playMoneyChips']) , 'center' ),  
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas , 9, false   ); 
} 


function list_statistic_payout_megajackpot_player(){
    
	my_set_code_js('
		function confirmDelete(id){
            var t = confirm(\'Yakin akan menghapus data ?\');
                if(t){
                    location.href=\'index.php?com='.$_GET['com'].'&task=delete&id=\'+id;
                }
                return false;
            }
        ');	
    
	$headers= array( 
		'Username' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Payout' => array( 'style' => 'width:50%;text-align:right;' ),
		'Action' => array( 'style' => 'width:20%;text-align:right;' ),
        
	);

	
	
	$query 	= "SELECT * FROM players ";
   // $result = my_query($query);
    
    //PAGING CONTROL START
	$total_records = players_total( );
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
	$previous_page_text = ' Mundur '; 
	$next_page_text = ' Maju ';  
	$first_page_text = ' Awal '; 
	$last_page_text = ' Akhir ';
	
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
	while($ey = my_fetch_array($result)){ 

        $detail_button = '<a href="index.php?com='.$_GET['com'].'&task=payout_megajackpot&subtask=total&pid='.$ey['ID'].'">Payout</a>';
        $player_payout = player_payout($ey['ID']);
		$row[] = array( 
            'username' => position_text_align(  $ey['username'],   'left' ),
            'winamount' => position_text_align( $player_payout ,  'right') ,
            'payout' => position_text_align( $detail_button ,  'right') ,
		);
	}
	
	$datas = table_rows($row);
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas , 3, false , $paging  ) ;
}

function payout_megajackpot_tabs($player_id , $page){
     my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
    
    $navigasi = array(
		'<input class="submit-green" type="button" value="By Player" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=payout_megajackpot&subtask=player\'"/>',
		'<input class="submit-green" type="button" value="By Dates" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=payout_megajackpot&subtask=total\'"/>' 
	);
	$box = header_box( '' , $navigasi );
    $player = my_get_data_by_id( 'players' , 'ID' , $player_id );
    $dealer = my_get_data_by_id('dealers' , 'dealerId' ,  (int) $player['dealerId']);
    $view = $box;
    $view .= '<div class="row"><form method="GET">';
    $view .= '<div class="col-md-6">';
    $view .= '<input type="hidden" name="com" value="'.$_GET['com'].'" />';
    $view .= '<input type="hidden" name="task" value="'.$_GET['task'].'" />';
    $view .= '<input type="hidden" name="subtask" value="'.$page.'" />';
    if( isset($_GET['pid']) )
    $view .= '<input type="hidden" name="pid" value="'.$_GET['pid'].'" />';
    
    $start_date = array(
        'name'  => 'start_date',
        'id'  => 'start_date',
        'value'  => ( isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d') ) 
    );
    $form_start_date = form_calendar( $start_date );
    $view .= form_field_display_header( $form_start_date , 'Start date'  );   
    
    
    
    $view .= '</div><div class="col-md-6">';
     
    $end_date = array(
        'name'  => 'end_date',
        'id'  => 'end_date',
        'value'  => ( isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d') ) 
    );
    $form_end_date = form_calendar( $end_date  );
    $view .= form_field_display_header( $form_end_date    , 'End date'  );    
    $view .= '</div></div>';
    $view .= '<div class="row"><div class="col-md-12">';
    $search = array(
        'name'  => 'search',
        'id'    => 'search',
        'value'  => ' Search ',
        'type'  => 'submit'
    );
    $form_search = form_dynamic( $search  );
    $view .= form_field_display_header( $form_search , '&nbsp;'  );
    if( (int) $player_id > 0){  
        $view .= form_field_display_header( $player['username'] , 'Player name'  );
    }
    $view .= '</div></form> </div>';
    
    switch($page){
        case "total"  :
            $view .= list_statistic_payout_megajackpot( $player['username'] );
        break;
        case "player"  :
            $view .= list_statistic_payout_megajackpot_player();
        break; 
        default:
            $view .= list_statistic_payout_megajackpot( 0 );
        break;
            
    }
     
    return $view;
    
}


function player_payout($id){
    $query = "SELECT 'unknown' AS dt_result ";
    $result = my_query($query);
    $row = my_fetch_array($result);
    return $row['dt_result'];
}