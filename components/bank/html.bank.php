<?php

    function list_bank(){
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
		'Dealer' => array( 'width:25%;','text-align:left;' ), 
		'Bank name' => array( 'width:25%;','text-align:left;' ), 
		'Rake Take' => array( 'width:10%;','text-align:right;' ), 
		'Play Money' => array( 'width:10%;','text-align:right;' ), 
		'Total Payout' => array( 'width:12%;','text-align:right;' ), 
		'act'=>array('width:10%','text-align:center')
	);

	
	
	$query 	= "SELECT * FROM bank ";
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
		'dealerId' => $ey['dealerId'],  
		'bankName' => $ey['bankName'],  
		'rakeTake' => $ey['rakeTake'],  
		'playMoneyBank' => $ey['playMoneyBank'],  
		'playMoneyTotalPayout' => $ey['playMoneyTotalPayout'],  
				'op'=> position_text_align( $edit_button  .$delete_button , 'right')
		);
	}
	
	$datas = table_rows($row);
	$navigasi = array(
		'<input class="submit-green" type="button" value="Tambah data" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=edit\'"/>',
		'<input class="submit-green" type="button" value="Proses" />'
	);
	$box = header_box( 'Data bank' , $navigasi );
	$paging = $kgPagerOBJ ->showPaging();
	return table_builder($headers , $datas ,  4 , false , $paging  ); 
}

	
function edit_bank($id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	$view = form_header( "form_bank" , "form_bank"  );
	$fields = my_get_data_by_id('bank','id', $id);


	$dealerIds =  array( );
	$query = "SELECT dealerId_id , label FROM dealerId";	
	$result = my_query($query);
	while($row_dealerId = my_fetch_array($result)){
		$dealerIds[$row_dealerId['dealerId_id']] = $row_dealerId['label'];
	}
	$dealerId = array(
		'name'=>'dealerId',
		'value'=>( isset($_POST['dealerId']) ? $_POST['dealerId'] : $fields['dealerId']) ,
	);
	$form_dealerId = form_radiobutton($dealerId , $dealerIds);
	$view .= form_field_display(  $form_dealerId   , "DealerId"    ); 
	

	
	$rakeTake = array(
			'name'=>'rakeTake',
			'value'=>(isset($_POST['rakeTake'])? $_POST['rakeTake'] : $fields['rakeTake']),
			'id'=>'rakeTake',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rakeTake = form_dynamic($rakeTake);
	$view .= form_field_display( $form_rakeTake  , "RakeTake"  );
	
	

	
	$rakeRate = array(
			'name'=>'rakeRate',
			'value'=>(isset($_POST['rakeRate'])? $_POST['rakeRate'] : $fields['rakeRate']),
			'id'=>'rakeRate',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_rakeRate = form_dynamic($rakeRate);
	$view .= form_field_display( $form_rakeRate  , "RakeRate"  );
	
	

	
	$tournamentTake = array(
			'name'=>'tournamentTake',
			'value'=>(isset($_POST['tournamentTake'])? $_POST['tournamentTake'] : $fields['tournamentTake']),
			'id'=>'tournamentTake',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_tournamentTake = form_dynamic($tournamentTake);
	$view .= form_field_display( $form_tournamentTake  , "TournamentTake"  );
	
	

	
	$hiloTake = array(
			'name'=>'hiloTake',
			'value'=>(isset($_POST['hiloTake'])? $_POST['hiloTake'] : $fields['hiloTake']),
			'id'=>'hiloTake',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_hiloTake = form_dynamic($hiloTake);
	$view .= form_field_display( $form_hiloTake  , "HiloTake"  );
	
	

	
	$scratchyTake = array(
			'name'=>'scratchyTake',
			'value'=>(isset($_POST['scratchyTake'])? $_POST['scratchyTake'] : $fields['scratchyTake']),
			'id'=>'scratchyTake',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_scratchyTake = form_dynamic($scratchyTake);
	$view .= form_field_display( $form_scratchyTake  , "ScratchyTake"  );
	
	

	
	$bank = array(
			'name'=>'bank',
			'value'=>(isset($_POST['bank'])? $_POST['bank'] : $fields['bank']),
			'id'=>'bank',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bank = form_dynamic($bank);
	$view .= form_field_display( $form_bank  , "Bank"  );
	
	

	
	$playMoneyBank = array(
			'name'=>'playMoneyBank',
			'value'=>(isset($_POST['playMoneyBank'])? $_POST['playMoneyBank'] : $fields['playMoneyBank']),
			'id'=>'playMoneyBank',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playMoneyBank = form_dynamic($playMoneyBank);
	$view .= form_field_display( $form_playMoneyBank  , "PlayMoneyBank"  );
	
	

	
	$playMoneyPayoutBank = array(
			'name'=>'playMoneyPayoutBank',
			'value'=>(isset($_POST['playMoneyPayoutBank'])? $_POST['playMoneyPayoutBank'] : $fields['playMoneyPayoutBank']),
			'id'=>'playMoneyPayoutBank',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playMoneyPayoutBank = form_dynamic($playMoneyPayoutBank);
	$view .= form_field_display( $form_playMoneyPayoutBank  , "PlayMoneyPayoutBank"  );
	
	

	
	$playMoneyTotalPayout = array(
			'name'=>'playMoneyTotalPayout',
			'value'=>(isset($_POST['playMoneyTotalPayout'])? $_POST['playMoneyTotalPayout'] : $fields['playMoneyTotalPayout']),
			'id'=>'playMoneyTotalPayout',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_playMoneyTotalPayout = form_dynamic($playMoneyTotalPayout);
	$view .= form_field_display( $form_playMoneyTotalPayout  , "PlayMoneyTotalPayout"  );
	
	

	
	$payoutRate = array(
			'name'=>'payoutRate',
			'value'=>(isset($_POST['payoutRate'])? $_POST['payoutRate'] : $fields['payoutRate']),
			'id'=>'payoutRate',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_payoutRate = form_dynamic($payoutRate);
	$view .= form_field_display( $form_payoutRate  , "PayoutRate"  );
	
	

	
	$payoutBank = array(
			'name'=>'payoutBank',
			'value'=>(isset($_POST['payoutBank'])? $_POST['payoutBank'] : $fields['payoutBank']),
			'id'=>'payoutBank',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_payoutBank = form_dynamic($payoutBank);
	$view .= form_field_display( $form_payoutBank  , "PayoutBank"  );
	
	

	
	$totalPayout = array(
			'name'=>'totalPayout',
			'value'=>(isset($_POST['totalPayout'])? $_POST['totalPayout'] : $fields['totalPayout']),
			'id'=>'totalPayout',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_totalPayout = form_dynamic($totalPayout);
	$view .= form_field_display( $form_totalPayout  , "TotalPayout"  );
	
	

	
	$totalPayoutCashout = array(
			'name'=>'totalPayoutCashout',
			'value'=>(isset($_POST['totalPayoutCashout'])? $_POST['totalPayoutCashout'] : $fields['totalPayoutCashout']),
			'id'=>'totalPayoutCashout',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_totalPayoutCashout = form_dynamic($totalPayoutCashout);
	$view .= form_field_display( $form_totalPayoutCashout  , "TotalPayoutCashout"  );
	
	

	
	$bankName = array(
			'name'=>'bankName',
			'value'=>(isset($_POST['bankName'])? $_POST['bankName'] : $fields['bankName']),
			'id'=>'bankName',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankName = form_dynamic($bankName);
	$view .= form_field_display( $form_bankName  , "BankName"  );
	
	

	
	$bankWalletAddress = array(
			'name'=>'bankWalletAddress',
			'value'=>(isset($_POST['bankWalletAddress'])? $_POST['bankWalletAddress'] : $fields['bankWalletAddress']),
			'id'=>'bankWalletAddress',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankWalletAddress = form_dynamic($bankWalletAddress);
	$view .= form_field_display( $form_bankWalletAddress  , "BankWalletAddress"  );
	
	

	
	$bankAccount = array(
			'name'=>'bankAccount',
			'value'=>(isset($_POST['bankAccount'])? $_POST['bankAccount'] : $fields['bankAccount']),
			'id'=>'bankAccount',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_bankAccount = form_dynamic($bankAccount);
	$view .= form_field_display( $form_bankAccount  , "BankAccount"  );
	
	

	
	$ts = array(
			'name'=>'ts',
			'value'=>(isset($_POST['ts'])? $_POST['ts'] : $fields['ts']),
			'id'=>'ts',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_ts = form_dynamic($ts);
	$view .= form_field_display( $form_ts  , "Ts"  );
	
	

	
	$depositTotal = array(
			'name'=>'depositTotal',
			'value'=>(isset($_POST['depositTotal'])? $_POST['depositTotal'] : $fields['depositTotal']),
			'id'=>'depositTotal',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_depositTotal = form_dynamic($depositTotal);
	$view .= form_field_display( $form_depositTotal  , "DepositTotal"  );
	
	

	
	$cashoutTotal = array(
			'name'=>'cashoutTotal',
			'value'=>(isset($_POST['cashoutTotal'])? $_POST['cashoutTotal'] : $fields['cashoutTotal']),
			'id'=>'cashoutTotal',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_cashoutTotal = form_dynamic($cashoutTotal);
	$view .= form_field_display( $form_cashoutTotal  , "CashoutTotal"  );
	
	

	
	$autoWithdrawLimit = array(
			'name'=>'autoWithdrawLimit',
			'value'=>(isset($_POST['autoWithdrawLimit'])? $_POST['autoWithdrawLimit'] : $fields['autoWithdrawLimit']),
			'id'=>'autoWithdrawLimit',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_autoWithdrawLimit = form_dynamic($autoWithdrawLimit);
	$view .= form_field_display( $form_autoWithdrawLimit  , "AutoWithdrawLimit"  );
	
	

	
	$minWithdrawLimit = array(
			'name'=>'minWithdrawLimit',
			'value'=>(isset($_POST['minWithdrawLimit'])? $_POST['minWithdrawLimit'] : $fields['minWithdrawLimit']),
			'id'=>'minWithdrawLimit',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_minWithdrawLimit = form_dynamic($minWithdrawLimit);
	$view .= form_field_display( $form_minWithdrawLimit  , "MinWithdrawLimit"  );
	
	

	
	$minDepositLimit = array(
			'name'=>'minDepositLimit',
			'value'=>(isset($_POST['minDepositLimit'])? $_POST['minDepositLimit'] : $fields['minDepositLimit']),
			'id'=>'minDepositLimit',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_minDepositLimit = form_dynamic($minDepositLimit);
	$view .= form_field_display( $form_minDepositLimit  , "MinDepositLimit"  );
	
	

	
	$currencySign = array(
			'name'=>'currencySign',
			'value'=>(isset($_POST['currencySign'])? $_POST['currencySign'] : $fields['currencySign']),
			'id'=>'currencySign',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_currencySign = form_dynamic($currencySign);
	$view .= form_field_display( $form_currencySign  , "CurrencySign"  );
	
	

	$decimalPointss =  array( );
	$query = "SELECT decimalPoints_id , label FROM decimalPoints";	
	$result = my_query($query);
	while($row_decimalPoints = my_fetch_array($result)){
		$decimalPointss[$row_decimalPoints['decimalPoints_id']] = $row_decimalPoints['label'];
	}
	$decimalPoints = array(
		'name'=>'decimalPoints',
		'value'=>( isset($_POST['decimalPoints']) ? $_POST['decimalPoints'] : $fields['decimalPoints']) ,
	);
	$form_decimalPoints = form_radiobutton($decimalPoints , $decimalPointss);
	$view .= form_field_display(  $form_decimalPoints   , "DecimalPoints"    ); 
	

	$chipToBitcoinRatios =  array( );
	$query = "SELECT chipToBitcoinRatio_id , label FROM chipToBitcoinRatio";	
	$result = my_query($query);
	while($row_chipToBitcoinRatio = my_fetch_array($result)){
		$chipToBitcoinRatios[$row_chipToBitcoinRatio['chipToBitcoinRatio_id']] = $row_chipToBitcoinRatio['label'];
	}
	$chipToBitcoinRatio = array(
		'name'=>'chipToBitcoinRatio',
		'value'=>( isset($_POST['chipToBitcoinRatio']) ? $_POST['chipToBitcoinRatio'] : $fields['chipToBitcoinRatio']) ,
	);
	$form_chipToBitcoinRatio = form_radiobutton($chipToBitcoinRatio , $chipToBitcoinRatios);
	$view .= form_field_display(  $form_chipToBitcoinRatio   , "ChipToBitcoinRatio"    ); 
	

	$useJackpots =  array( );
	$query = "SELECT useJackpot_id , label FROM useJackpot";	
	$result = my_query($query);
	while($row_useJackpot = my_fetch_array($result)){
		$useJackpots[$row_useJackpot['useJackpot_id']] = $row_useJackpot['label'];
	}
	$useJackpot = array(
		'name'=>'useJackpot',
		'value'=>( isset($_POST['useJackpot']) ? $_POST['useJackpot'] : $fields['useJackpot']) ,
	);
	$form_useJackpot = form_radiobutton($useJackpot , $useJackpots);
	$view .= form_field_display(  $form_useJackpot   , "UseJackpot"    ); 
	

	
	$jackpotFee = array(
			'name'=>'jackpotFee',
			'value'=>(isset($_POST['jackpotFee'])? $_POST['jackpotFee'] : $fields['jackpotFee']),
			'id'=>'jackpotFee',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_jackpotFee = form_dynamic($jackpotFee);
	$view .= form_field_display( $form_jackpotFee  , "JackpotFee"  );
	
	

	
	$signupAward = array(
			'name'=>'signupAward',
			'value'=>(isset($_POST['signupAward'])? $_POST['signupAward'] : $fields['signupAward']),
			'id'=>'signupAward',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_signupAward = form_dynamic($signupAward);
	$view .= form_field_display( $form_signupAward  , "SignupAward"  );
	
	

	$hiloTakeGolds =  array( );
	$query = "SELECT hiloTakeGold_id , label FROM hiloTakeGold";	
	$result = my_query($query);
	while($row_hiloTakeGold = my_fetch_array($result)){
		$hiloTakeGolds[$row_hiloTakeGold['hiloTakeGold_id']] = $row_hiloTakeGold['label'];
	}
	$hiloTakeGold = array(
		'name'=>'hiloTakeGold',
		'value'=>( isset($_POST['hiloTakeGold']) ? $_POST['hiloTakeGold'] : $fields['hiloTakeGold']) ,
	);
	$form_hiloTakeGold = form_radiobutton($hiloTakeGold , $hiloTakeGolds);
	$view .= form_field_display(  $form_hiloTakeGold   , "HiloTakeGold"    ); 
	

	
	$exchangeRate = array(
			'name'=>'exchangeRate',
			'value'=>(isset($_POST['exchangeRate'])? $_POST['exchangeRate'] : $fields['exchangeRate']),
			'id'=>'exchangeRate',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_exchangeRate = form_dynamic($exchangeRate);
	$view .= form_field_display( $form_exchangeRate  , "ExchangeRate"  );
	
	

	
	$dailyAwardChips = array(
			'name'=>'dailyAwardChips',
			'value'=>(isset($_POST['dailyAwardChips'])? $_POST['dailyAwardChips'] : $fields['dailyAwardChips']),
			'id'=>'dailyAwardChips',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_dailyAwardChips = form_dynamic($dailyAwardChips);
	$view .= form_field_display( $form_dailyAwardChips  , "DailyAwardChips"  );
	
	

	
	$dailyAwardChipsVIP = array(
			'name'=>'dailyAwardChipsVIP',
			'value'=>(isset($_POST['dailyAwardChipsVIP'])? $_POST['dailyAwardChipsVIP'] : $fields['dailyAwardChipsVIP']),
			'id'=>'dailyAwardChipsVIP',
			'type'=>'textfield',
			'size'=>'35'
		);
	$form_dailyAwardChipsVIP = form_dynamic($dailyAwardChipsVIP);
	$view .= form_field_display( $form_dailyAwardChipsVIP  , "DailyAwardChipsVIP"  );
	
		 
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

function submit_bank($id){
	 
	$datas = array(); $datas['id']	=  my_type_data_str($_POST['id']);
	 $datas['dealerId']	=  my_type_data_str($_POST['dealerId']);
	 $datas['rakeTake']	=  my_type_data_str($_POST['rakeTake']);
	 $datas['rakeRate']	=  my_type_data_str($_POST['rakeRate']);
	 $datas['tournamentTake']	=  my_type_data_str($_POST['tournamentTake']);
	 $datas['hiloTake']	=  my_type_data_str($_POST['hiloTake']);
	 $datas['scratchyTake']	=  my_type_data_str($_POST['scratchyTake']);
	 $datas['bank']	=  my_type_data_str($_POST['bank']);
	 $datas['playMoneyBank']	=  my_type_data_str($_POST['playMoneyBank']);
	 $datas['playMoneyPayoutBank']	=  my_type_data_str($_POST['playMoneyPayoutBank']);
	 $datas['playMoneyTotalPayout']	=  my_type_data_str($_POST['playMoneyTotalPayout']);
	 $datas['payoutRate']	=  my_type_data_str($_POST['payoutRate']);
	 $datas['payoutBank']	=  my_type_data_str($_POST['payoutBank']);
	 $datas['totalPayout']	=  my_type_data_str($_POST['totalPayout']);
	 $datas['totalPayoutCashout']	=  my_type_data_str($_POST['totalPayoutCashout']);
	 $datas['bankName']	=  my_type_data_str($_POST['bankName']);
	 $datas['bankWalletAddress']	=  my_type_data_str($_POST['bankWalletAddress']);
	 $datas['bankAccount']	=  my_type_data_str($_POST['bankAccount']);
	 $datas['ts']	=  my_type_data_str($_POST['ts']);
	 $datas['depositTotal']	=  my_type_data_str($_POST['depositTotal']);
	 $datas['cashoutTotal']	=  my_type_data_str($_POST['cashoutTotal']);
	 $datas['autoWithdrawLimit']	=  my_type_data_str($_POST['autoWithdrawLimit']);
	 $datas['minWithdrawLimit']	=  my_type_data_str($_POST['minWithdrawLimit']);
	 $datas['minDepositLimit']	=  my_type_data_str($_POST['minDepositLimit']);
	 $datas['currencySign']	=  my_type_data_str($_POST['currencySign']);
	 $datas['decimalPoints']	=  my_type_data_str($_POST['decimalPoints']);
	 $datas['chipToBitcoinRatio']	=  my_type_data_str($_POST['chipToBitcoinRatio']);
	 $datas['useJackpot']	=  my_type_data_str($_POST['useJackpot']);
	 $datas['jackpotFee']	=  my_type_data_str($_POST['jackpotFee']);
	 $datas['signupAward']	=  my_type_data_str($_POST['signupAward']);
	 $datas['hiloTakeGold']	=  my_type_data_str($_POST['hiloTakeGold']);
	 $datas['exchangeRate']	=  my_type_data_str($_POST['exchangeRate']);
	 $datas['dailyAwardChips']	=  my_type_data_str($_POST['dailyAwardChips']);
	 $datas['dailyAwardChipsVIP']	=  my_type_data_str($_POST['dailyAwardChipsVIP']);
	 
	if($id > 0){
		return my_update_record( 'bank' , 'id' , $id , $datas );
	}
	return my_insert_record( 'bank' , $datas );
}

function form_bank_validate(){
	return false;
}
	