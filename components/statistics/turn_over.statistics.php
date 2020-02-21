<?php


function list_statistic_turn_over(){
    
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

function list_statistic_turn_over_player(){
    
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
		'Chip' => array( 'style' => 'width:50%;text-align:right;' ),
		'Action' => array( 'style' => 'width:20%;text-align:right;' ),
        
	);

	
	
	$query 	= "SELECT DATE(ts) as ts, SUM(winAmount) as winAmount from player_history  group by DATE(ts) ORDER BY winAmount ASC LIMIT 15";
    $result = my_query($query);
    
	$row = array();
	while($ey = my_fetch_array($result)){ 

         
		$row[] = array( 
            'username' => position_text_align( '1', 'center' ),
            'winamount' => position_text_align( rp_format($ey['winAmount']),  'right') ,
            'turn over' => position_text_align( 'Turn Over',  'right') ,
		);
	}
	
	$datas = table_rows($row);
    
	return table_builder($headers , $datas , 9, false   ); 
}

function turn_over_tabs($player_id , $page){
     
    
    $navigasi = array(
		'<input class="submit-green" type="button" value="Total" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'\'"/>',
		'<input class="submit-green" type="button" value="By Player" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=detail&subtask=turn_over&id='.$player_id.'\'"/>' 
	);
	$box = header_box( '' , $navigasi );
    $player = my_get_data_by_id( 'players' , 'ID' , $player_id );
    $dealer = my_get_data_by_id('dealers' , 'dealerId' ,  (int) $player['dealerId']);
    
    $view = '<div class="row">';
    $view .= '<div class="col-md-6">';
    $view .= form_field_display_header( $player['username'] , 'Start date'  );   
     
    $view .= '</div><div class="col-md-6">';
       

    $view .= form_field_display_header( $player['country']    , 'End date'  );    
    $view .= '</div></div>';
    $view .= $box;
    
    switch($page){
        case "total"  :
            $view .= list_statistic_turn_over();
        break;
        case "player"  :
            $view .= list_statistic_deposit();
        break; 
        default:
            $view .= list_statistic_turn_over();
        break;
            
    }
     
    return $view;
    
}