<?php

    function list_tournaments(){
    global $box;
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
		'Title' => array( 'style'=>'width:10%;text-align:left;' ), 
		'Game type' => array( 'style'=>'width:5%;text-align:left;' ), 
		'Schedule' => array( 'style'=>'width:10%;text-align:left;' ), 
		'Start time' => array( 'style'=>'width:5%;text-align:left;' ), 
		'Buy in' => array( 'style'=>'width:5%;text-align:left;' ), 
		'Entry fee' => array( 'style'=>'width:5%;text-align:left;' ), 
		'Min. players' => array( 'style'=>'width:10%;text-align:center;' ), 
		'Max. players' => array( 'style'=>'width:10%;text-align:center;' ), 
		'Rebuys' => array( 'style'=>'width:5%;text-align:center;' ), 
		'Late entry' => array( 'style'=>'width:10%;text-align:center;' ), 
		'StrcID' => array( 'style'=>'width:5%;text-align:center;' ), 
		'Sts' => array( 'style'=>'width:10%;text-align:center;' ), 
	//	'act'=>array('width:5%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM tournaments ";
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
		$i++;
		$editproperty = array(
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['tournamentId'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['tournamentId'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array(   
		'title' => $ey['title'],  
		'gameType' => position_text_align($ey['gameType'],  'center'),
		'schedule' => position_text_align($ey['schedule'],  'center'),
		'startAtTime' =>position_text_align( date('Y-m-d' , $ey['startAtTime']),  'center'),
		'buyIn' => position_text_align( $ey['buyIn'],  'center'),
		'entryFee' => position_text_align( $ey['entryFee'],  'center'),
		'minPlayers' =>  position_text_align($ey['minPlayers'],  'center'),
		'maxPlayers' => position_text_align( $ey['maxPlayers'],  'center'), 
		'rebuys' =>position_text_align( $ey['rebuys'],  'center'),
		'lateEntry' => position_text_align($ey['lateEntry'],  'center'),
		'structureId' => position_text_align( $ey['structureId'],  'center'),
		'status' => position_text_align( $ey['status'],  'center'),
		//		'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( ' ' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_tournaments($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_tournaments" , "form_tournaments"  );
	$fields = my_get_data_by_id('tournaments','tournamentId', $id);


	 

	
	$title = array(
			'name'=>'title',
			'value'=>(isset($_POST['title'])? $_POST['title'] : $fields['title']),
			'id'=>'title',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_title = form_dynamic($title);
	$view .= form_field_display( $form_title  , "Title"  );
	
	

	
	$gameType = array(
			'name'=>'gameType',
			'value'=>(isset($_POST['gameType'])? $_POST['gameType'] : $fields['gameType']),
			'id'=>'gameType',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_gameType = form_dynamic($gameType);
	$view .= form_field_display( $form_gameType  , "Game type"  );
	
	

	
	$schedule = array(
			'name'=>'schedule',
			'value'=>(isset($_POST['schedule'])? $_POST['schedule'] : $fields['schedule']),
			'id'=>'schedule',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_schedule = form_dynamic($schedule);
	$view .= form_field_display( $form_schedule  , "Schedule"  );
	
	

	
	$startAtTime = array(
			'name'=>'startAtTime',
			'value'=>(isset($_POST['startAtTime'])? $_POST['startAtTime'] : $fields['startAtTime']),
			'id'=>'startAtTime',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_startAtTime = form_dynamic($startAtTime);
	$view .= form_field_display( $form_startAtTime  , "Start time"  );
	
	

	
	$buyIn = array(
			'name'=>'buyIn',
			'value'=>(isset($_POST['buyIn'])? $_POST['buyIn'] : $fields['buyIn']),
			'id'=>'buyIn',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_buyIn = form_dynamic($buyIn);
	$view .= form_field_display( $form_buyIn  , "Buy in"  );
	
	

	
	$entryFee = array(
			'name'=>'entryFee',
			'value'=>(isset($_POST['entryFee'])? $_POST['entryFee'] : $fields['entryFee']),
			'id'=>'entryFee',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_entryFee = form_dynamic($entryFee);
	$view .= form_field_display( $form_entryFee  , "Entry fee"  );
	
	

	
	$minPlayers = array(
			'name'=>'minPlayers',
			'value'=>(isset($_POST['minPlayers'])? $_POST['minPlayers'] : $fields['minPlayers']),
			'id'=>'minPlayers',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_minPlayers = form_dynamic($minPlayers);
	$view .= form_field_display( $form_minPlayers  , "Min. players"  );
	
	

	
	$maxPlayers = array(
			'name'=>'maxPlayers',
			'value'=>(isset($_POST['maxPlayers'])? $_POST['maxPlayers'] : $fields['maxPlayers']),
			'id'=>'maxPlayers',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_maxPlayers = form_dynamic($maxPlayers);
	$view .= form_field_display( $form_maxPlayers  , "Max. players"  );
	
	

	
	$rebuys = array(
			'name'=>'rebuys',
			'value'=>(isset($_POST['rebuys'])? $_POST['rebuys'] : $fields['rebuys']),
			'id'=>'rebuys',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rebuys = form_dynamic($rebuys);
	$view .= form_field_display( $form_rebuys  , "Rebuys"  );
	
	

	
	$lateEntry = array(
			'name'=>'lateEntry',
			'value'=>(isset($_POST['lateEntry'])? $_POST['lateEntry'] : $fields['lateEntry']),
			'id'=>'lateEntry',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_lateEntry = form_dynamic($lateEntry);
	$view .= form_field_display( $form_lateEntry  , "Late entry"  );
	
	

	
	$structureId = array(
			'name'=>'structureId',
			'value'=>(isset($_POST['structureId'])? $_POST['structureId'] : $fields['structureId']),
			'id'=>'structureId',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_structureId = form_dynamic($structureId);
	$view .= form_field_display( $form_structureId  , "StructureID"  );
	
	

	
	$status = array(
			'name'=>'status',
			'value'=>(isset($_POST['status'])? $_POST['status'] : $fields['status']),
			'id'=>'status',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_status = form_dynamic($status);
	$view .= form_field_display( $form_status  , "Status"  );
	
		 
	$submit = array(
		'value' => ( $id ==0 ? ' Save ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'submit-green'
	);
	$form_submit= form_dynamic($submit); 
	
	$cancel = array(
		'value' => ( '  Cancel  '),
		'name' => 'cancel', 
		'type'=>'button',
		'onclick'=>'location.href=\'index.php?com='.$_GET['com'].'\'',
		'class'=>'submit-gray'
	);
	$form_cancel = form_dynamic($cancel );
	 
	$view .= form_field_display( $form_submit.' '.$form_cancel, "&nbsp;" , "<hr />"  );
	$view .= form_footer( );	
	
	return  $view;
} 

function submit_tournaments($id){
	 
	$datas = array(); $datas['tournamentId']	=  my_type_data_str($_POST['tournamentId']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['game']	=  my_type_data_str($_POST['game']);
	 $datas['title']	=  my_type_data_str($_POST['title']);
	 $datas['startAtTime']	=  my_type_data_str($_POST['startAtTime']);
	 $datas['startASAP']	=  my_type_data_str($_POST['startASAP']);
	 $datas['timeLabel']	=  my_type_data_str($_POST['timeLabel']);
	 $datas['finishTime']	=  my_type_data_str($_POST['finishTime']);
	 $datas['registrationOpens']	=  my_type_data_str($_POST['registrationOpens']);
	 $datas['buyIn']	=  my_type_data_str($_POST['buyIn']);
	 $datas['entryFee']	=  my_type_data_str($_POST['entryFee']);
	 $datas['prizePool']	=  my_type_data_str($_POST['prizePool']);
	 $datas['minPlayers']	=  my_type_data_str($_POST['minPlayers']);
	 $datas['maxPlayers']	=  my_type_data_str($_POST['maxPlayers']);
	 $datas['rebuys']	=  my_type_data_str($_POST['rebuys']);
	 $datas['lateEntry']	=  my_type_data_str($_POST['lateEntry']);
	 $datas['lateEntryExpireMins']	=  my_type_data_str($_POST['lateEntryExpireMins']);
	 $datas['gameType']	=  my_type_data_str($_POST['gameType']);
	 $datas['structureId']	=  my_type_data_str($_POST['structureId']);
	 $datas['currentLevel']	=  my_type_data_str($_POST['currentLevel']);
	 $datas['registeredPlayers']	=  my_type_data_str($_POST['registeredPlayers']);
	 $datas['activePlayers']	=  my_type_data_str($_POST['activePlayers']);
	 $datas['finishedPlayers']	=  my_type_data_str($_POST['finishedPlayers']);
	 $datas['startingChips']	=  my_type_data_str($_POST['startingChips']);
	 $datas['rebuyExpireMins']	=  my_type_data_str($_POST['rebuyExpireMins']);
	 $datas['rebuyPrice']	=  my_type_data_str($_POST['rebuyPrice']);
	 $datas['rebuyChips']	=  my_type_data_str($_POST['rebuyChips']);
	 $datas['addOn']	=  my_type_data_str($_POST['addOn']);
	 $datas['addOnChips']	=  my_type_data_str($_POST['addOnChips']);
	 $datas['addOnPrice']	=  my_type_data_str($_POST['addOnPrice']);
	 $datas['addOnExpireMins']	=  my_type_data_str($_POST['addOnExpireMins']);
	 $datas['promoImageUrl']	=  my_type_data_str($_POST['promoImageUrl']);
	 $datas['status']	=  my_type_data_str($_POST['status']);
	 $datas['bots']	=  my_type_data_str($_POST['bots']);
	 $datas['tspeed']	=  my_type_data_str($_POST['tspeed']);
	 $datas['leagueId']	=  my_type_data_str($_POST['leagueId']);
	 $datas['payoutId']	=  my_type_data_str($_POST['payoutId']);
	 $datas['schedule']	=  my_type_data_str($_POST['schedule']);
	 $datas['copied']	=  my_type_data_str($_POST['copied']);
	 $datas['timezone']	=  my_type_data_str($_POST['timezone']);
	 $datas['tableCount']	=  my_type_data_str($_POST['tableCount']);
	 $datas['ts']	=  my_type_data_str($_POST['ts']);
	 $datas['clubId']	=  my_type_data_str($_POST['clubId']);
	 $datas['tplayMoney']	=  my_type_data_str($_POST['tplayMoney']);
	 
	if($id > 0){
		return my_update_record( 'tournaments' , 'id' , $id , $datas );
	}
	return my_insert_record( 'tournaments' , $datas );
}

function form_tournaments_validate(){
	return false;
}
	