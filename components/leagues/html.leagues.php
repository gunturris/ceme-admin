<?php

    function list_leagues(){
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
		'Name'        => array( 'style'=> 'width:20%; text-align:left;' ), 
		'Fee chips'   => array( 'style'=> 'width:15%; text-align:center;' ), 
		'Fee gold'    => array( 'style'=>  'width:15%; text-align:left;' ), 
		'Fee dollars' => array( 'style'=> 'width:5%; text-align:left;' ), 
		'Min. Level'  => array( 'style'=> 'width:10%; text-align:right;' ), 
		'League type' => array( 'style'=> 'width:10%; text-align:left;' ), 
		'Point'   => array( 'style'=> 'width:5%; text-align:right;' ), 
		'Players' => array( 'style'=> 'width:15%; text-align:right;' ), 
		'act'     =>array('style'=> 'width:5% text-align:center')
	);

	
	
	$query 	= "SELECT * FROM leagues ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['id'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['id'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
            'leagueName' => $ey['leagueName'],  
            'joinFeeChips' => $ey['joinFeeChips'],  
            'joinFeeGold' => $ey['joinFeeGold'],  
            'joinFeeDollars' => $ey['joinFeeDollars'],  
            'minLevel' => $ey['minLevel'],  
            'leagueType' => $ey['leagueType'],  
            'point' => $ey['points'],  
            'players' => $ey['players'],  
			'op'=> position_text_align( $edit_button  .$delete_button , 'right')
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

	
function edit_leagues($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_leagues" , "form_leagues"  );
	$fields = my_get_data_by_id('leagues','id', $id);


	
	$leagueName = array(
			'name'=>'leagueName',
			'value'=>(isset($_POST['leagueName'])? $_POST['leagueName'] : $fields['leagueName']),
			'id'=>'leagueName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_leagueName = form_dynamic($leagueName);
	$view .= form_field_display( $form_leagueName  , "Name of league"  );
	
	

	
	$joinFeeChips = array(
			'name'=>'joinFeeChips',
			'value'=>(isset($_POST['joinFeeChips'])? $_POST['joinFeeChips'] : $fields['joinFeeChips']),
			'id'=>'joinFeeChips',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_joinFeeChips = form_dynamic($joinFeeChips);
	$view .= form_field_display( $form_joinFeeChips  , "Fee chips"  );
	
	

	
	$joinFeeGold = array(
			'name'=>'joinFeeGold',
			'value'=>(isset($_POST['joinFeeGold'])? $_POST['joinFeeGold'] : $fields['joinFeeGold']),
			'id'=>'joinFeeGold',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_joinFeeGold = form_dynamic($joinFeeGold);
	$view .= form_field_display( $form_joinFeeGold  , "Fee gold"  );
	
	

	
	$joinFeeDollars = array(
			'name'=>'joinFeeDollars',
			'value'=>(isset($_POST['joinFeeDollars'])? $_POST['joinFeeDollars'] : $fields['joinFeeDollars']),
			'id'=>'joinFeeDollars',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_joinFeeDollars = form_dynamic($joinFeeDollars);
	$view .= form_field_display( $form_joinFeeDollars  , "Fee dollars"  );
	
	

	
	$minLevel = array(
			'name'=>'minLevel',
			'value'=>(isset($_POST['minLevel'])? $_POST['minLevel'] : $fields['minLevel']),
			'id'=>'minLevel',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_minLevel = form_dynamic($minLevel);
	$view .= form_field_display( $form_minLevel  , "Min. Level"  );
	
	

	
	$leagueType = array(
			'name'=>'leagueType',
			'value'=>(isset($_POST['leagueType'])? $_POST['leagueType'] : $fields['leagueType']),
			'id'=>'leagueType',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_leagueType = form_dynamic($leagueType);
	$view .= form_field_display( $form_leagueType  , "Type of league"  );
	
	

	
	$point = array(
			'name'=>'points',
			'value'=>(isset($_POST['points'])? $_POST['points'] : $fields['points']),
			'id'=>'points',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_point = form_dynamic($point);
	$view .= form_field_display( $form_point  , "Points "  );
	
	

	
	$players = array(
			'name'=>'players',
			'value'=>(isset($_POST['players'])? $_POST['players'] : $fields['players']),
			'id'=>'players',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_players = form_dynamic($players);
	$view .= form_field_display( $form_players  , "Players count "  );
	
		 
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

function submit_leagues($id){
	 
	$datas = array();
     
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['leagueName']	=  my_type_data_str($_POST['leagueName']);
	 $datas['joinFeeChips']	=  my_type_data_str($_POST['joinFeeChips']);
	 $datas['joinFeeGold']	=  my_type_data_str($_POST['joinFeeGold']);
	 $datas['joinFeeDollars']	=  my_type_data_str($_POST['joinFeeDollars']);
	 $datas['minLevel']	=  my_type_data_str($_POST['minLevel']);
	 $datas['points']	=  my_type_data_str($_POST['points']);
	 $datas['leagueType']	=  my_type_data_str($_POST['leagueType']);
	 $datas['info']	=  my_type_data_str($_POST['info']);
	 $datas['imageUrl']	=  my_type_data_str($_POST['imageUrl']);
	 $datas['award']	=  my_type_data_str($_POST['award']);
	 $datas['feeText']	=  my_type_data_str($_POST['feeText']);
	 $datas['players']	=  my_type_data_str($_POST['players']);
	 
	if($id > 0){
		return my_update_record( 'leagues' , 'id' , $id , $datas );
	}
	return my_insert_record( 'leagues' , $datas );
}

function form_leagues_validate(){
	return false;
}
	