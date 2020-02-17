<?php

function list_buyin_megajackpot(){ 
     
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
		'Buy-in Jackpot' => array( 'style' => 'width:70%;text-align:right;' ),
         
	);

	
	 
	$query 	= "
select DATE(ts) as ts, SUM(jackpotBuy) as jackpotBuy from player_history group by DATE(ts) 
order by jackpotBuy DESC  LIMIT 15";

    $result = my_query($query);
	
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'tanggal' => position_text_align( $ey['ts'], 'center' ),
            'winamount' => position_text_align( rp_format($ey['jackpotBuy']) , 'center' ),  
		);
	}
	
	$datas = table_rows($row);
     
	return table_builder($headers , $datas , 9, false   ); 
} 