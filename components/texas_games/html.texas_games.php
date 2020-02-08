<?php

    function list_texas_games(){
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
		'Table name' => array( 'style'=>'width:25%;text-align:left;' ), 
		'Table type' => array( 'style'=>'width:10%;text-align:left;' ), 
		'Table limit' => array( 'style'=>'width:10%;text-align:left;' ), 
		'Bots allowed' => array( 'style'=>'width:5%;text-align:left;' ),  
		'Seats' => array( 'style'=>'width:5%;text-align:left;' ), 
		'Speed' => array( 'style'=>'width:5%;text-align:left;' ), 
		'Jackpot med' => array( 'style'=>'width:10%;text-align:left;' ), 
		'Jackpot hi' => array( 'style'=>'width:10%;text-align:left;' ), 
		'Play money' => array( 'style'=>'width:10%;text-align:left;' ), 
		'act'=>array('width:5%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM texas ";
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
				'href'=>'index.php?com='.$_GET['com'].'&task=edit&id=' . $ey['gameID'] , 
				'title'=>'Edit'
		);	
		$edit_button = button_icon( 'b_edit.png' , $editproperty  );

		$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.$ey['gameID'].');',
			'title'=>'Delete', 
		);
		$delete_button = button_icon( 'b_drop.png' , $deleteproperty  );

		$row[] = array( 
		'tablename' => $ey['tablename'],  
		'tabletype' =>  position_text_align($ey['tabletype'],   'center'),
		'tablelimit' =>  position_text_align($ey['tablelimit'],   'center'),
		'botsAllowed' => position_text_align( $ey['botsAllowed'],     'center'),
		'seats' => position_text_align( $ey['seats'],   'center'),
		'speed' =>  position_text_align($ey['speed'],   'center'),
		'jackpotMed' => position_text_align( $ey['jackpotMed'],   'center'),
		'jackpotHi' =>  position_text_align($ey['jackpotHi'],  'center'), 
		'playMoney' => position_text_align( $ey['playMoney'],  'center'),
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

	
function edit_texas_games($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_texas_games" , "form_texas_games"  );
	$fields = my_get_data_by_id('texas','gameID', $id);


	
	$tablename = array(
			'name'=>'tablename',
			'value'=>(isset($_POST['tablename'])? $_POST['tablename'] : $fields['tablename']),
			'id'=>'tablename',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tablename = form_dynamic($tablename);
	$view .= form_field_display( $form_tablename  , "Table name"  );
	
	

	
	$tabletype = array(
			'name'=>'tabletype',
			'value'=>(isset($_POST['tabletype'])? $_POST['tabletype'] : $fields['tabletype']),
			'id'=>'tabletype',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tabletype = form_dynamic($tabletype);
	$view .= form_field_display( $form_tabletype  , "Table type"  );
	
	

	
	$botsAllowed = array(
			'name'=>'botsAllowed',
			'value'=>(isset($_POST['botsAllowed'])? $_POST['botsAllowed'] : $fields['botsAllowed']),
			'id'=>'botsAllowed',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_botsAllowed = form_dynamic($botsAllowed);
	$view .= form_field_display( $form_botsAllowed  , "Bots allowed"  );
	
	

	
	$rakeRate = array(
			'name'=>'rakeRate',
			'value'=>(isset($_POST['rakeRate'])? $_POST['rakeRate'] : $fields['rakeRate']),
			'id'=>'rakeRate',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rakeRate = form_dynamic($rakeRate);
	$view .= form_field_display( $form_rakeRate  , "Rake rate"  );
	
	

	
	$rakeTotal = array(
			'name'=>'rakeTotal',
			'value'=>(isset($_POST['rakeTotal'])? $_POST['rakeTotal'] : $fields['rakeTotal']),
			'id'=>'rakeTotal',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rakeTotal = form_dynamic($rakeTotal);
	$view .= form_field_display( $form_rakeTotal  , "Rake total"  );
	
	 
	
	$seats = array(
			'name'=>'seats',
			'value'=>(isset($_POST['seats'])? $_POST['seats'] : $fields['seats']),
			'id'=>'seats',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_seats = form_dynamic($seats);
	$view .= form_field_display( $form_seats  , "Seat"  );
	
	

	
	$speed = array(
			'name'=>'speed',
			'value'=>(isset($_POST['speed'])? $_POST['speed'] : $fields['speed']),
			'id'=>'speed',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_speed = form_dynamic($speed);
	$view .= form_field_display( $form_speed  , "Speed"  );
	
	

	
	$sb = array(
			'name'=>'sb',
			'value'=>(isset($_POST['sb'])? $_POST['sb'] : $fields['sb']),
			'id'=>'sb',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_sb = form_dynamic($sb);
	$view .= form_field_display( $form_sb  , "SB"  );
	
	

	
	$bb = array(
			'name'=>'bb',
			'value'=>(isset($_POST['bb'])? $_POST['bb'] : $fields['bb']),
			'id'=>'bb',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bb = form_dynamic($bb);
	$view .= form_field_display( $form_bb  , "BB"  );
	
	

	
	$tablelow = array(
			'name'=>'tablelow',
			'value'=>(isset($_POST['tablelow'])? $_POST['tablelow'] : $fields['tablelow']),
			'id'=>'tablelow',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tablelow = form_dynamic($tablelow);
	$view .= form_field_display( $form_tablelow  , "Table low"  );
	
	

	
	$tablelimit = array(
			'name'=>'tablelimit',
			'value'=>(isset($_POST['tablelimit'])? $_POST['tablelimit'] : $fields['tablelimit']),
			'id'=>'tablelimit',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tablelimit = form_dynamic($tablelimit);
	$view .= form_field_display( $form_tablelimit  , "Table limit"  );
	
	

	
	$freeJackpot = array(
			'name'=>'freeJackpot',
			'value'=>(isset($_POST['freeJackpot'])? $_POST['freeJackpot'] : $fields['freeJackpot']),
			'id'=>'freeJackpot',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_freeJackpot = form_dynamic($freeJackpot);
	$view .= form_field_display( $form_freeJackpot  , "Free jackpot"  );
	
	

	
	$jackpotMed = array(
			'name'=>'jackpotMed',
			'value'=>(isset($_POST['jackpotMed'])? $_POST['jackpotMed'] : $fields['jackpotMed']),
			'id'=>'jackpotMed',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_jackpotMed = form_dynamic($jackpotMed);
	$view .= form_field_display( $form_jackpotMed  , "Jackpot med"  );
	
	

	
	$jackpotHi = array(
			'name'=>'jackpotHi',
			'value'=>(isset($_POST['jackpotHi'])? $_POST['jackpotHi'] : $fields['jackpotHi']),
			'id'=>'jackpotHi',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_jackpotHi = form_dynamic($jackpotHi);
	$view .= form_field_display( $form_jackpotHi  , "Jackpot hi"  );
	
	

	
	$playMoney = array(
			'name'=>'playMoney',
			'value'=>(isset($_POST['playMoney'])? $_POST['playMoney'] : $fields['playMoney']),
			'id'=>'playMoney',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playMoney = form_dynamic($playMoney);
	$view .= form_field_display( $form_playMoney  , "Play money"  );
	
		 
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

function submit_texas_games($id){
	 
	$datas = array(); 
	 $datas['gameNo']	=  my_type_data_str($_POST['gameNo']);
	 $datas['game']	=  my_type_data_str($_POST['game']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['tablename']	=  my_type_data_str($_POST['tablename']);
	 $datas['tabletype']	=  my_type_data_str($_POST['tabletype']);
	 $datas['tablelow']	=  my_type_data_str($_POST['tablelow']);
	 $datas['tablelimit']	=  my_type_data_str($_POST['tablelimit']);
	 $datas['tablestyle']	=  my_type_data_str($_POST['tablestyle']);
	 $datas['move']	=  my_type_data_str($_POST['move']);
	 $datas['dealer']	=  my_type_data_str($_POST['dealer']);
	 $datas['hand']	=  my_type_data_str($_POST['hand']);
	 $datas['pot']	=  my_type_data_str($_POST['pot']);
	 $datas['bet']	=  my_type_data_str($_POST['bet']);
	 $datas['lastbet']	=  my_type_data_str($_POST['lastbet']);
	 $datas['lastmove']	=  my_type_data_str($_POST['lastmove']);
	 $datas['card1']	=  my_type_data_str($_POST['card1']);
	 $datas['card2']	=  my_type_data_str($_POST['card2']);
	 $datas['card3']	=  my_type_data_str($_POST['card3']);
	 $datas['card4']	=  my_type_data_str($_POST['card4']);
	 $datas['card5']	=  my_type_data_str($_POST['card5']);
	 $datas['p1name']	=  my_type_data_str($_POST['p1name']);
	 $datas['p1pot']	=  my_type_data_str($_POST['p1pot']);
	 $datas['p1bet']	=  my_type_data_str($_POST['p1bet']);
	 $datas['p1card1']	=  my_type_data_str($_POST['p1card1']);
	 $datas['p1card2']	=  my_type_data_str($_POST['p1card2']);
	 $datas['p2name']	=  my_type_data_str($_POST['p2name']);
	 $datas['p2pot']	=  my_type_data_str($_POST['p2pot']);
	 $datas['p2bet']	=  my_type_data_str($_POST['p2bet']);
	 $datas['p2card1']	=  my_type_data_str($_POST['p2card1']);
	 $datas['p2card2']	=  my_type_data_str($_POST['p2card2']);
	 $datas['p3name']	=  my_type_data_str($_POST['p3name']);
	 $datas['p3pot']	=  my_type_data_str($_POST['p3pot']);
	 $datas['p3bet']	=  my_type_data_str($_POST['p3bet']);
	 $datas['p3card1']	=  my_type_data_str($_POST['p3card1']);
	 $datas['p3card2']	=  my_type_data_str($_POST['p3card2']);
	 $datas['p4name']	=  my_type_data_str($_POST['p4name']);
	 $datas['p4pot']	=  my_type_data_str($_POST['p4pot']);
	 $datas['p4bet']	=  my_type_data_str($_POST['p4bet']);
	 $datas['p4card1']	=  my_type_data_str($_POST['p4card1']);
	 $datas['p4card2']	=  my_type_data_str($_POST['p4card2']);
	 $datas['p5name']	=  my_type_data_str($_POST['p5name']);
	 $datas['p5pot']	=  my_type_data_str($_POST['p5pot']);
	 $datas['p5bet']	=  my_type_data_str($_POST['p5bet']);
	 $datas['p5card1']	=  my_type_data_str($_POST['p5card1']);
	 $datas['p5card2']	=  my_type_data_str($_POST['p5card2']);
	 $datas['p6name']	=  my_type_data_str($_POST['p6name']);
	 $datas['p6pot']	=  my_type_data_str($_POST['p6pot']);
	 $datas['p6bet']	=  my_type_data_str($_POST['p6bet']);
	 $datas['p6card1']	=  my_type_data_str($_POST['p6card1']);
	 $datas['p6card2']	=  my_type_data_str($_POST['p6card2']);
	 $datas['p7name']	=  my_type_data_str($_POST['p7name']);
	 $datas['p7pot']	=  my_type_data_str($_POST['p7pot']);
	 $datas['p7bet']	=  my_type_data_str($_POST['p7bet']);
	 $datas['p7card1']	=  my_type_data_str($_POST['p7card1']);
	 $datas['p7card2']	=  my_type_data_str($_POST['p7card2']);
	 $datas['p8name']	=  my_type_data_str($_POST['p8name']);
	 $datas['p8pot']	=  my_type_data_str($_POST['p8pot']);
	 $datas['p8bet']	=  my_type_data_str($_POST['p8bet']);
	 $datas['p8card1']	=  my_type_data_str($_POST['p8card1']);
	 $datas['p8card2']	=  my_type_data_str($_POST['p8card2']);
	 $datas['p9name']	=  my_type_data_str($_POST['p9name']);
	 $datas['p9pot']	=  my_type_data_str($_POST['p9pot']);
	 $datas['p9bet']	=  my_type_data_str($_POST['p9bet']);
	 $datas['p9card1']	=  my_type_data_str($_POST['p9card1']);
	 $datas['p9card2']	=  my_type_data_str($_POST['p9card2']);
	 $datas['p10name']	=  my_type_data_str($_POST['p10name']);
	 $datas['p10pot']	=  my_type_data_str($_POST['p10pot']);
	 $datas['p10bet']	=  my_type_data_str($_POST['p10bet']);
	 $datas['p10card1']	=  my_type_data_str($_POST['p10card1']);
	 $datas['p10card2']	=  my_type_data_str($_POST['p10card2']);
	 $datas['msg']	=  my_type_data_str($_POST['msg']);
	 $datas['winner']	=  my_type_data_str($_POST['winner']);
	 $datas['win1']	=  my_type_data_str($_POST['win1']);
	 $datas['win2']	=  my_type_data_str($_POST['win2']);
	 $datas['win3']	=  my_type_data_str($_POST['win3']);
	 $datas['win4']	=  my_type_data_str($_POST['win4']);
	 $datas['win5']	=  my_type_data_str($_POST['win5']);
	 $datas['win6']	=  my_type_data_str($_POST['win6']);
	 $datas['win7']	=  my_type_data_str($_POST['win7']);
	 $datas['win8']	=  my_type_data_str($_POST['win8']);
	 $datas['win9']	=  my_type_data_str($_POST['win9']);
	 $datas['winType1']	=  my_type_data_str($_POST['winType1']);
	 $datas['winType2']	=  my_type_data_str($_POST['winType2']);
	 $datas['winType3']	=  my_type_data_str($_POST['winType3']);
	 $datas['winType4']	=  my_type_data_str($_POST['winType4']);
	 $datas['winType5']	=  my_type_data_str($_POST['winType5']);
	 $datas['winType6']	=  my_type_data_str($_POST['winType6']);
	 $datas['winType7']	=  my_type_data_str($_POST['winType7']);
	 $datas['winType8']	=  my_type_data_str($_POST['winType8']);
	 $datas['winType9']	=  my_type_data_str($_POST['winType9']);
	 $datas['sb']	=  my_type_data_str($_POST['sb']);
	 $datas['bb']	=  my_type_data_str($_POST['bb']);
	 $datas['p1lbet']	=  my_type_data_str($_POST['p1lbet']);
	 $datas['p2lbet']	=  my_type_data_str($_POST['p2lbet']);
	 $datas['p3lbet']	=  my_type_data_str($_POST['p3lbet']);
	 $datas['p4lbet']	=  my_type_data_str($_POST['p4lbet']);
	 $datas['p5lbet']	=  my_type_data_str($_POST['p5lbet']);
	 $datas['p6lbet']	=  my_type_data_str($_POST['p6lbet']);
	 $datas['p7lbet']	=  my_type_data_str($_POST['p7lbet']);
	 $datas['p8lbet']	=  my_type_data_str($_POST['p8lbet']);
	 $datas['p9lbet']	=  my_type_data_str($_POST['p9lbet']);
	 $datas['p10lbet']	=  my_type_data_str($_POST['p10lbet']);
	 $datas['mraise']	=  my_type_data_str($_POST['mraise']);
	 $datas['p1action']	=  my_type_data_str($_POST['p1action']);
	 $datas['p2action']	=  my_type_data_str($_POST['p2action']);
	 $datas['p3action']	=  my_type_data_str($_POST['p3action']);
	 $datas['p4action']	=  my_type_data_str($_POST['p4action']);
	 $datas['p5action']	=  my_type_data_str($_POST['p5action']);
	 $datas['p6action']	=  my_type_data_str($_POST['p6action']);
	 $datas['p7action']	=  my_type_data_str($_POST['p7action']);
	 $datas['p8action']	=  my_type_data_str($_POST['p8action']);
	 $datas['p9action']	=  my_type_data_str($_POST['p9action']);
	 $datas['p10action']	=  my_type_data_str($_POST['p10action']);
	 $datas['seats']	=  my_type_data_str($_POST['seats']);
	 $datas['speed']	=  my_type_data_str($_POST['speed']);
	 $datas['wait']	=  my_type_data_str($_POST['wait']);
	 $datas['p1potwin']	=  my_type_data_str($_POST['p1potwin']);
	 $datas['p2potwin']	=  my_type_data_str($_POST['p2potwin']);
	 $datas['p3potwin']	=  my_type_data_str($_POST['p3potwin']);
	 $datas['p4potwin']	=  my_type_data_str($_POST['p4potwin']);
	 $datas['p5potwin']	=  my_type_data_str($_POST['p5potwin']);
	 $datas['p6potwin']	=  my_type_data_str($_POST['p6potwin']);
	 $datas['p7potwin']	=  my_type_data_str($_POST['p7potwin']);
	 $datas['p8potwin']	=  my_type_data_str($_POST['p8potwin']);
	 $datas['p9potwin']	=  my_type_data_str($_POST['p9potwin']);
	 $datas['p10potwin']	=  my_type_data_str($_POST['p10potwin']);
	 $datas['lastplayer']	=  my_type_data_str($_POST['lastplayer']);
	 $datas['p1chat']	=  my_type_data_str($_POST['p1chat']);
	 $datas['p2chat']	=  my_type_data_str($_POST['p2chat']);
	 $datas['p3chat']	=  my_type_data_str($_POST['p3chat']);
	 $datas['p4chat']	=  my_type_data_str($_POST['p4chat']);
	 $datas['p5chat']	=  my_type_data_str($_POST['p5chat']);
	 $datas['p6chat']	=  my_type_data_str($_POST['p6chat']);
	 $datas['p7chat']	=  my_type_data_str($_POST['p7chat']);
	 $datas['p8chat']	=  my_type_data_str($_POST['p8chat']);
	 $datas['p9chat']	=  my_type_data_str($_POST['p9chat']);
	 $datas['p10chat']	=  my_type_data_str($_POST['p10chat']);
	 $datas['p1status']	=  my_type_data_str($_POST['p1status']);
	 $datas['p2status']	=  my_type_data_str($_POST['p2status']);
	 $datas['p3status']	=  my_type_data_str($_POST['p3status']);
	 $datas['p4status']	=  my_type_data_str($_POST['p4status']);
	 $datas['p5status']	=  my_type_data_str($_POST['p5status']);
	 $datas['p6status']	=  my_type_data_str($_POST['p6status']);
	 $datas['p7status']	=  my_type_data_str($_POST['p7status']);
	 $datas['p8status']	=  my_type_data_str($_POST['p8status']);
	 $datas['p9status']	=  my_type_data_str($_POST['p9status']);
	 $datas['startTime']	=  my_type_data_str($_POST['startTime']);
	 $datas['rake']	=  my_type_data_str($_POST['rake']);
	 $datas['cap']	=  my_type_data_str($_POST['cap']);
	 $datas['rakeTotal']	=  my_type_data_str($_POST['rakeTotal']);
	 $datas['ts']	=  my_type_data_str($_POST['ts']);
	 $datas['tableMultiplier']	=  my_type_data_str($_POST['tableMultiplier']);
	 $datas['lastaction']	=  my_type_data_str($_POST['lastaction']);
	 $datas['discards']	=  my_type_data_str($_POST['discards']);
	 $datas['debug']	=  my_type_data_str($_POST['debug']);
	 $datas['storeId']	=  my_type_data_str($_POST['storeId']);
	 $datas['socketSpeed']	=  my_type_data_str($_POST['socketSpeed']);
	 $datas['version']	=  my_type_data_str($_POST['version']);
	 $datas['languageKey']	=  my_type_data_str($_POST['languageKey']);
	 $datas['multiplierType']	=  my_type_data_str($_POST['multiplierType']);
	 $datas['baseSB']	=  my_type_data_str($_POST['baseSB']);
	 $datas['baseBB']	=  my_type_data_str($_POST['baseBB']);
	 $datas['tournamentStarted']	=  my_type_data_str($_POST['tournamentStarted']);
	 $datas['jackpotLow']	=  my_type_data_str($_POST['jackpotLow']);
	 $datas['jackpotMed']	=  my_type_data_str($_POST['jackpotMed']);
	 $datas['jackpotHi']	=  my_type_data_str($_POST['jackpotHi']);
	 $datas['linkedId']	=  my_type_data_str($_POST['linkedId']);
	 $datas['rakeFee']	=  my_type_data_str($_POST['rakeFee']);
	 $datas['ante']	=  my_type_data_str($_POST['ante']);
	 $datas['gameType']	=  my_type_data_str($_POST['gameType']);
	 $datas['isVIP']	=  my_type_data_str($_POST['isVIP']);
	 $datas['linkedTableNo']	=  my_type_data_str($_POST['linkedTableNo']);
	 $datas['botsAllowed']	=  my_type_data_str($_POST['botsAllowed']);
	 $datas['rakeRate']	=  my_type_data_str($_POST['rakeRate']);
	 $datas['checkin']	=  my_type_data_str($_POST['checkin']);
	 $datas['debugMSG']	=  my_type_data_str($_POST['debugMSG']);
	 $datas['closed']	=  my_type_data_str($_POST['closed']);
	 $datas['clubId']	=  my_type_data_str($_POST['clubId']);
	 $datas['levelLimit']	=  my_type_data_str($_POST['levelLimit']);
	 $datas['seasonId']	=  my_type_data_str($_POST['seasonId']);
	 $datas['voices']	=  my_type_data_str($_POST['voices']);
	 $datas['bbPlayer']	=  my_type_data_str($_POST['bbPlayer']);
	 $datas['playMoney']	=  my_type_data_str($_POST['playMoney']);
	 $datas['sbPlayer']	=  my_type_data_str($_POST['sbPlayer']);
	 $datas['freeJackpot']	=  my_type_data_str($_POST['freeJackpot']);
	 
	if($id > 0){
		return my_update_record( 'texas' , 'gameID' , $id , $datas );
	} 
	return my_insert_record( 'texas' , $datas );
}

function form_texas_games_validate(){
	return false;
}
	