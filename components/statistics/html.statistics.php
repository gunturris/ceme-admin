<?php

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
	$navigasi =  statistic_header_box();;
	$box = header_box( 'Data turn over' , $navigasi ); 
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
    
	$navigasi =statistic_header_box();
	$box = header_box( 'Deposits sumary' , $navigasi ); 
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
    
	$navigasi =statistic_header_box();
	$box = header_box( 'Withdraw sumary' , $navigasi ); 
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
    
	$navigasi =statistic_header_box();
	$box = header_box( 'Withdraw sumary' , $navigasi ); 
	return table_builder($headers , $datas , 9, false   ); 
}



function statistic_header_box(){
    $navigasi = array(
		'<input class="submit-green" type="button" value="Turn over" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=turn_over\'"/>',
		'<input class="submit-green" type="button" value="Deposit" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=deposit\'"/>',
		'<input class="submit-green" type="button" value="Withdraw" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=withdraw\'"/>', 
		'<input class="submit-green" type="button" value="High winner" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=high_winner\'"/>', 
	);
    return $navigasi;
}

function custom_bar_statistics($custombar_layout){
    global $custombar;
    $view = ' <div class="row">'.$custombar_layout.'</div>';
    $custombar = $view;
    return true;
}