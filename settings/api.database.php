<?php
 
 
$class_mysqli_exists = class_exists('mysqli') ;
 
if( $class_mysqli_exists ):
	/* EXECUTE QUERY */
	function my_query( $query ){
		global $connection;  
		$check = $connection->query($query);
		  
		if(! $check){
			fatal_error('Query mySQL tidak benar! : '.$query);
		}	
		return  $check ;
	}

	/* AMBIL HASIL RESULT  QUERY */
	function my_result($query){
		$objQuery = my_query($query);
		$row = $objQuery->fetch_array(MYSQLI_ASSOC);  
		return $row[0]; 
	}
	
	function my_num_rows( &$objQuery ){
		return $objQuery->num_rows;
	}

	function my_fetch_object( &$objQuery ){ 
		return $objQuery->fetch_object(); 
	}

	function my_fetch_array( &$objQuery ){  
		return $objQuery->fetch_array(MYSQLI_ASSOC); 
	}
	function my_fetch_row(  $objQuery ){  
		return $objQuery->fetch_row( ); 
	}
	function my_result_close( &$objQuery ){
		return $objQuery->close();
	}

	/* AMBIL RESPON DATABASE */
	function my_affected_rows( ){ 
		global $connection; 
		return  $connection->affected_rows();  
		
	}

	/* CLOSE DATABASE */
	function my_close( ){
		global $connection;
		return $connection->close();
	}
else:
	/* EXECUTE QUERY */
	function my_query( $query ){
		global $connection;  
		$check = mysql_query($query); 
		if(! $check){
			fatal_error('Query mySQL tidak benar! : '.$query);
		}	
		return  $check ;
	}

	/* AMBIL HASIL RESULT  QUERY */
	function my_num_rows(  $objQuery ){
		return mysql_num_rows( $objQuery );
	}

	function my_result( $query){
		$objQuery = my_query($query);
		$row = my_fetch_array(  $objQuery );  
		return $row[0]; 
	}
	
	function my_fetch_object(  $objQuery ){ 
		return mysql_fetch_object(); 
	}

	function my_fetch_array(  $objQuery ){  
		return mysql_fetch_array( $objQuery ); 
	}

	function my_fetch_row(  $objQuery ){  
		return mysql_fetch_row( $objQuery ); 
	}
	function my_result_close(  $objQuery ){
		return mysql_free_result($objQuery);
	}

	/* AMBIL RESPON DATABASE */
	function my_affected_rows( ){ 
		global $connection; 
		return  mysql_affected_rows($connection);  
		
	}

	/* CLOSE DATABASE */
	function my_close( ){
		global $connection;
		return mysql_close( $connection );
	}
endif;

/* Get table fields list*/
function my_get_field_list( $table_name ){
	
	$query = " SHOW fields FROM `".$table_name."` ";
	$result = my_query( $query );
	 
	while($row = my_fetch_array(  $result ) ){
		$data  = $row['Field'];
		break;
	}
	
	return $data;
	
}

/* 
INSERTING DATA  
Depend my_type_data_str(), my_type_data_function (), my_type_data_int 
Cara ,udah untuk menyimpan record;

SAMPLE:

$table_name = 'siswa'

$datas = array();
$datas['umur'] =my_type_data_str('12');
$datas['kelamin'] =my_type_data_str('Perempuan');
$datas['jurusan'] =my_type_data_str('IPS');
$datas['masuk'] = my_type_data_function('NOW()') 
*/
function my_insert_record( $table_name , $datas ){
 
	global $connection;

	$build_query = " INSERT INTO `".$table_name ."` SET "  ;
	foreach( $datas as $field => $value ){
	
		$build_query .= "`".$field ."` = ".  $value  . ", ";
	
	}
	
	$insert_query = rtrim( trim($build_query) , "," ); 
 
	if( my_query( $insert_query )  ){
		return $connection->insert_id; 
	}
	return false;

}

function my_type_data_str($val){
	return "'".trim( addslashes($val))."'";
}

function my_type_data_function($val){
	return trim( addslashes($val));
}

function my_type_data_int($val){
	$val = (int) $val;
	return trim( addslashes($val));
}

/*
	DELETE DATA ON TABLE
*/
var_dump("-+-=-+-= = = =  = = = == = = = = = = = = = == = =");
function my_delete_record( $table_name , $field_key , $data_id){
	global $connection;	
	
	$query = "DELETE FROM `".$table_name."` WHERE `".$field_key ."` = ". $data_id ;
	$result = my_query( $query );
	
	return $connection->affected_rows ;
}
 
/*
 
Depend my_type_data_str(), my_type_data_function (), my_type_data_int 
Cara ,udah untuk menyimpan record;

SAMPLE:

$table_name = 'siswa'
$field_key = 'siswa_id'
$data_id = 12

$datas = array();
$datas['umur'] =my_type_data_str('12');
$datas['kelamin'] =my_type_data_str('Perempuan');
$datas['jurusan'] =my_type_data_str('IPS');
$datas['masuk'] = my_type_data_function('NOW()') 
*/
function my_update_record( $table_name , $primary_key , $data_id , $datas ){

	global $connection;

	$build_query = " UPDATE `".$table_name ."` SET "  ;
	foreach( $datas as $field => $value ){
	
		$build_query .= "`".$field ."` = ".  $value  . ", ";
	
	}
	
	$query = rtrim( trim($build_query) , "," );
	
	$update_query = $query ." WHERE `".$primary_key."` = ". $data_id ; 
 
	$result = my_query( $update_query );
	 
	return $connection->affected_rows ; 

}


/* 
	GET DATA BY DATAS
	
	$table_name = 'siswa';
	
	$datas['siswa_id'] = my_type_data_int('2');
	
*/
function my_browse_data( $table_name , $datas ){
	
	global $connection;
	
	$primary_field = my_get_field_list($table_name);
	foreach($datas as $field => $value){
		$fieldname = $field ;
		$fieldvalue =  $value;
	}
	
	$query = " SELECT `".$primary_field. "` FROM `".$table_name."` WHERE `".$fieldname."` = ". $fieldvalue  ;
	 
	$result = my_query( $query );
	if( my_num_rows($result) > 0 ){
		
		$data = array();
		
		while(	$row = my_fetch_array($result) ){
			$data[] = $row[$primary_field];
		}
		
		return $data;
	
	}
	
	return false;
	
}

/*
	GET_DATA_BY_PRIMARY
*/
function my_get_data_by_id( $table_name , $primary_field , $id ){
	
	global $connection; 
	
	$query = " SELECT * FROM `".$table_name."` WHERE `".$primary_field."` = ". $id  ;
	 
	$result = my_query( $query );
	if( my_num_rows($result) > 0 ){ 
		return my_fetch_array($result); 
	}
	
	return false;
	
}

function my_get_result_query($query){
	$result = my_query($query);
	if($row = my_fetch_row($result)){
		return $row[0];
	}
	return false;
}
 
//Dari Table.API

function table_cetak_builder($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'2',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	$view = '<table cellspacing="4px" class="content_view" ';
	foreach($property_table as $table=>$property){
		$view .= $table.'="'.$property.'" ';
	}
	$view .='>'."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<tr>';
	foreach($header as $field=>$length){
		$view .='<td ';
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
		$view .='><b>'.strtoupper($field).'</b></td>';
 	}
	$view .='</tr>'."\n";	
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= '<tr>';
		$view .= '<td colspan="'.$footer_length.'" style="height:34px;text-align:center;border-bottom:2px solid;">No data available</td>';
		$view .= '</tr>'."\n";
	}
	$view .='<tr><th colspan='.  $footer_length .'>'."\n";
	if($footer_content && $datas ){
		$view .= $footer_content;
	}else{
		$view .= '&nbsp;';
	}
	$view .='</th></tr>'."\n";
	$view .='</table>'."\n<br/>";

	return $view;
}


function table_cetak_rows($fields){

	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	if(count($fields) == 0):
			return false;
	else: 
		$total_rows = count($fields);
		foreach($fields as $key=>$values){
			
			$bgrows = ( ($n%2) == 0 ) ? '#DEDEDE' : '' ;
			
			$view .= '<tr>';
			foreach( $values as $k => $value){
				if(($total_rows -1 )== $n)
				$view .= '<td style="height:24px; border-bottom:2px solid;" class="'.$k .'">'.$value.'</td>';
				else
				$view .= '<td style="height:24px;" class="'.$k .'">'.$value.'</td>';
			}
			$view .= '</tr>';
			
			$n++;
		}
	endif;
	return $view;
}

function table_builder($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'2',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	$view = '
	<div class="table-responsive">
         <table class="table table-striped table-bordered table-hover" id="dataTables-example"> 
    
    '."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<thead> <tr>';
	
    
	foreach($header as $field=>$length){
		$view .='<th ';
        
		$icon_show_field = '' ;
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
        
		$view .='>'.$field.'</th>';
 	}
	$view .='</tr></thead><tbody>'."\n";
	 
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= ' <tr>';
		$view .= '<td colspan="'.$footer_length.'" style="height:38px;text-align:center;">No data available</td>';
		$view .= '</tr>'."\n";
	}
	//$view .='<tr><th colspan='.  $footer_length .'>'."\n"; 
	//$view .='</th></tr>'."\n";
	$view .='
	 </tbody></table>
	</div>';
	if($footer_content  ){
		$view .= $footer_content;
	} 
	$view .=''."\n";
	
	return $view;
}

function header_box( $label , $navigasi ){
	$view = '
    
    <div class="row">
                    <div class="col-md-6">
                     '.$label.'
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                      ';
    
    foreach($navigasi as $button){
			$view .= $button;
		}
    
        $view .='  
                    </div>
                </div>
      ';
		 ;
		return $view;	
}


function table_rows($fields , $style =false){
	$view = "";
	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	$total_rows = count($fields);
	if($total_rows  == 0):
			return false;
	else:
		foreach($fields as $key=>$values){
			$n++;
			
			$bgrows = ( ($n%2) == 0 ) ? ' class="even" ' : ' class="odd"' ;
			/*if($n == $total_rows ){
				$view .= '<tr>';
				foreach( $values as $k => $value){
					$view .= '<td>'.$value.'</td>';
				}
				$view .= '</tr>';
			}else{ */
				$view .= "\n".'<tr'.$bgrows.'>' . "\n";
				foreach( $values as $k => $value){
					$view .= "\t".'<td>'.$value.'</td>'."\n";
				}
				$view .= '</tr>'."\n";
			/*}*/
		}
	endif;
	return $view;
}

function table_builder_plain($header , $datas ,  $footer_length , $property_table = false, $footer_content = false  ){

 
	if(! $property_table){
		$property_table = array(
			'style'=>'border-collapse:collapse;margin:2px;',
			'cellpadding'=>'2',
			'cellspacing'=>'0'
		);
	}
	
	if(! is_array($property_table) ){
		return (-1);
	}
	
	$view = '<table width="100%" ';
	foreach($property_table as $table=>$property){
		$view .= $table.'="'.$property.'" ';
	}
	$view .='>'."\n";

	if(! is_array($header) ){
		return (-2);
	}	 
	$view .='<tr  style="background:#CDCDCD;height:30px;font:12px verdana;font-weight:bold;text-align:left;color:#000;" >';
	foreach($header as $field=>$length){
		$view .='<th valign="bottom" ';
		foreach($length as $th => $value){
			$view .= $th.'="'.$value.'" ';
		}
		$view .='><b>'.strtoupper($field).'</b></th>';
 	}
	$view .='</tr>'."\n";
	 
	if( $datas ){
		$view .= $datas."\n";
	}else{
		$view .= '<tr bgcolor="#FFFFFF" style="border:1px solid;">';
		$view .= '<td colspan="'.$footer_length.'" style="height:34px;text-align:center;font-size:14px;">No data available</td>';
		$view .= '</tr>'."\n";
	} 
	
	if( $footer_content ){
		$view .= '<tr bgcolor="#CDCDCD">';
		$view .= '<td colspan="'.$footer_length.'" style="height:20px;text-align:right;font-size:14px;">'.$footer_content.'</td>';
		$view .= '</tr>'."\n";	
	}
	$view .='</table>'."\n";

	return $view;
}


function table_rows_plain(	$fields ){
	$view = "";
	if(! is_array($fields) ){
		return (-1);
	}
	$n = 0; 
	if(count($fields) == 0):
			return false;
	else:
		foreach($fields as $key=>$values){ 
			$view .= '<tr style="border-bottom:1px solid #CDCDCD;border-top:1px solid #CDCDCD;font:12px verdana;" onMouseOver="this.style.backgroundColor=\'#E1EAFE\'"; onMouseOut="this.style.backgroundColor=\'transparent\'"style="border-top:1px solid #000;border-bottom:1px solid #000;">';
			foreach( $values as $k => $value){
				$view .= '<td>'.$value.'</td>';
			}
			$view .= '</tr>';
			
			$n++;
		}
	endif;
	return $view;
}

function position_text_align( $content , $position = 'left' ){
	return '<div style="text-align:'.$position.'">'.$content.'</div>';
}
