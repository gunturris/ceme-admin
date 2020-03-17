<?php
function list_acounting_bot(){
    
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
		'Date' => array( 'style' => 'width:11%;text-align:center;' ),  
		'Bot chip' => array( 'style' => 'width:13%;text-align:center;' ),  
		'Add chip' => array( 'style' => 'width:13%;text-align:center;' ),  
		'Min chip' => array( 'style' => 'width:13%;text-align:center;' ),  
		'Turn over' => array( 'style' => 'width:15%;text-align:center;' ),  
		'Rake' => array( 'style' => 'width:15%;text-align:center;' ), 
		'Win/Lose' => array( 'style' => 'width:20%;text-align:center;' ), 
        
	); 
    $query 	= "SELECT * FROM debits WHERE approved=1 LIMIT 15";
    $result = my_query($query);
    
	$row = array();
	while($ey = my_fetch_array($result)){ 
 
		$row[] = array( 
            'date'    => position_text_align( 0, 'center' ), 
            'bot'    => position_text_align( 0, 'center' ), 
            'add'    => position_text_align( 0, 'center' ), 
            'min'    => position_text_align( 0, 'center' ), 
            'to'       => position_text_align(0, 'right' ), 
            'rake'       => position_text_align(0, 'right' ), 
            'win'       => position_text_align(0, 'right' ), 
            
		);
	}
	
	$datas = table_rows($row);
    
	return table_builder($headers , $datas , 9, false   ); 
}