<?php

function list_payout_megajackpot(){ 
     
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