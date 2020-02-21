<?php

function players_total($criteria = false){
    if(! $criteria ){
        $query = "SELECT COUNT(*) AS total_datas FROM players";
        $result = my_query($query);
        $row = my_fetch_array($result);
        return $row['total_datas'];
    }
    return 0;
}


function player_detail($player_id , $page){
     
    
    $navigasi = array(
		'<input class="submit-green" type="button" value="Back" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
		'<input class="submit-green" type="button" value="Turn over" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail&subtask=turn_over&id='.$player_id.'\'"/>',
		'<input class="submit-green" type="button" value="Deposit" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail&subtask=deposit&id='.$player_id.'\'"/>',
		'<input class="submit-green" type="button" value="Withdraw" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail&subtask=withdraw&id='.$player_id.'\'"/>', 
		'<input class="submit-green" type="button" value="High winner" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail&subtask=high_winner&id='.$player_id.'\'"/>', 
	);
	$box = header_box( '' , $navigasi );
    $player = my_get_data_by_id( 'players' , 'ID' , $player_id );
    $dealer = my_get_data_by_id('dealers' , 'dealerId' ,  (int) $player['dealerId']);
    
    $view = '<div class="row">';
    $view .= '<div class="col-md-6">';
    $view .= form_field_display_header( $player['username'] , 'Username'  );   
    $view .= form_field_display_header( $player['nickname'] , 'Nickname'  );   
    $view .= form_field_display_header( 'xxxxxxxxxxxxxxxxxxxx '  , 'Referrer'  );   
    $view .= form_field_display_header( $dealer['busname'] , 'Dealer'  );   
    $view .= form_field_display_header( $player['email']  , 'Email'  );  
    $view .= form_field_display_header( 'xxxxxxxxxxxxxxxxxxxx '    , 'Phone'  ); 
    $view .= form_field_display_header( date( 'd-m-Y' ,$player['datecreated'] )   , 'Registration date'  ); 
    $view .= '</div><div class="col-md-6">';
       

    $view .= form_field_display_header( $player['country']    , 'Country'  );   
    $view .= form_field_display_header('xxxxxxxxxxxxxxxxxxxx '    , 'Group'  );   
    $view .= form_field_display_header( $player['bankName']    , 'Bank name'  );   
    $view .= form_field_display_header( $player['bankAccount']   , 'Bank account'  );   
    $view .= form_field_display_header(  $player['transferLimit']  , 'Transfer limit'  );    
    $view .= form_field_display_header(  'xxxxxxxxxxxxxxxxxxxx '   , 'Withdraw'  );    
    $view .= form_field_display_header(  'xxxxxxxxxxxxxxxxxxxx '  , 'Deposit'  );    
    $view .= '</div></div>';
    $view .= $box;
    
    switch($page){
        case "turn_over"  :
            $view .= list_statistic_turn_over();
        break;
        case "deposit"  :
            $view .= list_statistic_deposit();
        break;
        case "withdraw"  :
            $view .= list_statistic_withdraw();
        break;
        case "high_winner"  :
            $view .= list_statistic_high_winner();
        break;
        default:
            $view .= list_statistic_turn_over();
        break;
            
    }
    
    /*
    $view .= $split_line;
    $view .= form_field_display(  'Rp. '.rp_format($order['order_net'] ) , 'Total belanja'  );   
    $view .= form_field_display(  'Rp. '.rp_format($order['order_ongkir'] ) , 'Ongkos kirim'  );   
    $view .= form_field_display(  'Rp. '.rp_format(0 ) , 'Biaya admin  (0%)'  );
    
    $view .= form_field_display(  '<span style="color:red;">Rp. '.rp_format(0 ) .'</span>', 'Penggunaan saldo'  );   
    $view .= form_field_display(  '<span style="color:red;">Rp. '.rp_format(0 ).'</span>' , 'Total tagihan'  );   
    $view .= form_field_display(  '<span style="color:red;"> '.$voucher['kodepromo'].' - ( <i> Rp. '.rp_format($voucher['priceref_1']).' </i>)</span>' , 'Kode voucher'  );   
    */
    return $view;
    
}


function list_statistic_turn_over(){
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
		'Date' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Win amount' => array( 'style' => 'width:70%;text-align:right;' ),
        
	);

	
	
	$query 	= "SELECT DATE(ts) as ts, SUM(winAmount) as winAmount from player_history  group by DATE(ts) ORDER BY winAmount ASC LIMIT 15";
    $result = my_query($query);
    
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align($ey['ts'], 'center' ),
            'winamount' => position_text_align( rp_format($ey['winAmount']),  'right') 
		);
	}
	
	$datas = table_rows($row); 
	return table_builder($headers , $datas , 9, false   ); 
}

function list_statistic_deposit(){
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
		'Date' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Deposit' => array( 'style' => 'width:70%;text-align:right;' ),
         
	);

	
	
	$query 	= "SELECT DATE(ts) as ts, count(*)as cnt FROM `debits` where approved='1' group by DATE(ts)  LIMIT 15";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align( $ey['ts'], 'center' ),
            'winamount' => position_text_align( rp_format($ey['cnt']) , 'center' ),  
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas , 9, false   ); 
}

function list_statistic_withdraw(){
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
		'Date' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Withdraw' => array( 'style' => 'width:70%;text-align:right;' ),
         
	);

	
	
	$query 	= "SELECT DATE(ts) as ts, count(*)as cnt FROM `credits` where valid='1' group by DATE(ts) LIMIT 15";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align( $ey['ts'], 'center' ),
            'winamount' => position_text_align( rp_format($ey['cnt']), 'right' ),  
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas , 9, false   ); 
}



function list_statistic_high_winner(){
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
		'Date' => array( 'style' => 'width:30%;text-align:center;' ), 
		'Winner amount' => array( 'style' => 'width:70%;text-align:right;' ),
         
	);

	
	
	$query 	= "select DATE(ts) as ts, SUM(winAmount) as winAmount from player_history where status='win' group by DATE(ts) LIMIT 15";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align( $ey['ts'], 'center' ),
            'winamount' => position_text_align( rp_format($ey['winAmount']), 'right' ),  
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas , 9, false   ); 
}
