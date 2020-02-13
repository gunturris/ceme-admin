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

//Form.API

function set_post_time_data($fieldname){ 
	$jam = $_POST['jam_'.$fieldname];
	$menit = $_POST['menit_'.$fieldname]; 
	return $jam.':'.$menit.':00';
}

function form_time($forms){ 
list($forms['value_jam'],$forms['value_menit']) = explode(":",$forms['value']); 
	$jam_form = array(
				'name'=>'jam_'.$forms['name'],
				'value'=>$forms['value_jam'],
				'id'=>'jam_'.$forms['name'],
				'style'=>'width:22px;',
				'maxlength'=>'2',
				'type'=>'text'
			);
	$form_jam = form_dynamic($jam_form );
	 
	$menit_form = array(
				'name'=>'menit_'.$forms['name'],
				'value'=>$forms['value_menit'],
				'id'=>'menit_'.$forms['name'], 
				'style'=>'width:22px;',
				'maxlength'=>'2',
				'type'=>'text'
			);
	$form_menit = form_dynamic($menit_form  );
	
	return '<span style="60px">'.$form_jam.'<b> : </b>'.$form_menit.'</span>';
}


function dropdown_by_table($forms , $tablename ,$keys , $labels){
	$datas = array();
	$query = "SELECT {$keys} , {$labels} FROM {$tablename} ORDER BY {$labels} ASC ";
	$result = my_query($query);
	while($row = my_fetch_array($result)){
		$datas[$row[$keys]] = $row[$labels];
	}
	
	return form_dropdown($forms , $datas);
}
 
function form_dynamic($forms){

	if(! is_array($forms)) return false;
	
	$input = '<input ';
	foreach($forms as $attribut=>$value ){
		$input .= $attribut .' ="'.$value.'" '; 
	}
	$allow = array('submit' , 'reset'  );
	if(in_array($forms['type'] ,$allow ) )
	$input .= ' />';
	else
	$input .= ' onkeypress="return handleEnter(this, event)" />';
	
	return $input;
}

function form_money($forms){
	if(! is_array($forms)) return false;
	my_set_code_css('.form_angka{text-align:right;}'); 
	
	$script = '
	$(document).ready(function() {';
	$input = '<input ';
	
	foreach($forms as $attribut=>$value ){
		$input .= $attribut .' ="'.$value.'" '; 
		if($attribut == 'id'){
		$script .='
				$(\'#'.$value.'\').change(function()
                {
                    $(\'#'.$value.'\').formatCurrency();
                });
				';
		}
	}
	$script .='
	});';
	my_set_code_js($script);
	$allow = array('submit' , 'reset'  );
	if(in_array($forms['type'] ,$allow ) )
	$input .= ' />';
 	else
 	$input .= 'style="text-align:right;" onkeypress="return handleEnter(this, event);" class="form_angka" />';
	
	return $input;
} 

function form_hidden( $forms ){

	$name  =  isset($forms['name'])? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id    = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	
	
	$input = '<input  type="hidden" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';
	
		return $input;
}
 
/*
	FORM TEXTFIELD
	$forms  
*/
function form_textfield($forms){
	$type = isset($forms['type']) ? $forms['type'] : "text" ;
	$name =  isset($forms['name'])? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$text = ( $type =="password") ? "password" : $type;
	
	$input = '<input  onkeypress="return handleEnter(this, event)" type="'.$text.'" size="65" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';

	return $input;
}
/*
	FORM FILE
	$forms  
*/
function form_file($forms){

	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ; 
	
	$input = '<input  onkeypress="return handleEnter(this, event)" type="file" size="52" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';

	return $input;
}
/*
	FORM CHECKBOX
	$forms  
*/
function form_checkbox($forms){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$checked =   $forms['value'] == '1' ?  "checked" : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;

	$input = '<input  onkeypress="return handleEnter(this, event)" type="checkbox" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="1" '; 
	$input .= ' '.$checked.' />';

	return $input;

} 

/*
	FORM TEXTAREA
	$forms  
*/
function form_textarea($forms){

	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id 	= isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$rows 	= isset( $forms['rows'] ) ? $forms['rows'] : 4 ;
	$cols 	= isset( $forms['cols'] ) ? $forms['cols'] : 35 ;
	
	$input = '<textarea cols="'.$cols.'" rows="'.$rows .'" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= '>';
	$input .= $value;
	$input .= '</textarea>';

	return $input;

}

/*
	FORM DROP_DOWN
	$forms , $datas
	
	$datas = data yang di declare dari array
*/

function form_dropdown($forms , $datas){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id 	= isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$style	= isset( $forms['style'] ) ? $forms['style'] : "" ;
	$multiple = isset($forms['multiple']) ? $forms['multiple'] : 0 ;
	
	$input = '<select  onkeypress="return handleEnter(this, event)" ';
	$input .= ' name="'.$name.'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	//$input .= ' style="'.$style.'"';
	if(isset($forms['onchange'])){
		$input .= ' onchange="'.$forms['onchange'].'" ';
	}
	if((int) $multiple > 0 ){
		$input .= ' scrolling="yes" multiple="multiple" size="'.$multiple.'" ';
	}
	$input .= '>';
	$input .= '<option value="0" selected ></option>
				';
	//$input .= '<option value="">- - - - - Pilih - - - - -</option>';
	if( is_array($datas) ){
		foreach($datas as $option_value => $option ){
			if( $option_value == $value )
				$input .= '<option value="'.$option_value.'" selected>'.$option.'</option>
				';
			else
				$input .= '<option value="'.$option_value.'">'.$option.'</option>
				';
		}  
	}
	$input .= '</select>';
	
	return $input;

} 


/*
	FORM CALENDER
	$forms  
*/
function form_calendar($forms){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ; 
	
	return "<script>DateInput('".$name."', true, 'YYYY-MM-DD', '".$value."')</script> ";
}

function form_daterange($forms){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ; 
	my_set_code_js('$(function(){$(\'#'.$name.'\').dateRangePicker();}');
	return '<input id="'.$name.'" size="50" name="'.$name.'" value="'.$value.'">';
}
function form_radiobutton($forms , $datas , $flag=false){
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class 	= isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value 	= isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id 	= isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	$style 	= isset( $forms['style'] ) ? $forms['style'] : "";
	$form_field='';
	if(!$flag){
		$flag = ' &nbsp; ';
	}
	$i=0;
	foreach($datas  as $key=>$data){ 
	$i++;
		if(strtolower($key) == strtolower($value) && trim($value) <> ''){
		$form_field .= '<input  onkeypress="return handleEnter(this, event)" type="radio" id="'.$id.$i.'" checked value="'.$key.'" name="'.$name.'"><span class="label_form"><i>'.ucfirst($data).'</i></span>'.$flag;
		}else
		$form_field .= '<input  onkeypress="return handleEnter(this, event)" type="radio"  id="'.$id.$i.'" value="'.$key.'" name="'.$name.'"> <span class="label_form"><i>'.ucfirst($data).'</i></span>'.$flag;
	}
	return $form_field;
}
 
function form_button($forms){
	$text= isset($forms['type']) ? $forms['type'] : "submit";
	$name =  isset($forms['name'])  ? $forms['name'] : rand(1000 , 9999 ) ;
	$class = isset( $forms['class'] ) ? $forms['class'] : $forms['name'] ;
	$value = isset( $forms['value'] ) ? $forms['value'] : "" ;
	$id = isset( $forms['id'] ) ? $forms['id'] : $forms['name'] ;
	 
	
	$input = '<input type="'.$text.'" size="30" ';
	$input .= ' name="'.$name .'" ';
	$input .= ' class="'.$class.'" ';
	$input .= ' id="'.$id.'" ';
	$input .= ' value="'.$value.'" '; 
	$input .= ' />';

	return $input;	
}

function form_field_display( $form , $label , $bgcolor ="" , $merge=false,$request = false ){
	 
	  
    $view = '
    <div class="form-group input-group">
      <span class="input-group-addon" style="width:180px">'.$label.'</span>
      '.$form .'
    </div>';
	return $view;
}
function form_field_display_three( $form , $label ,  $nilai ,$merge = false  ){
	if($merge)
	$view  ='
	<tr   style="border-top: 1px solid #FFFFFF;border-bottom: 1px solid #FFFFFF; ">
		<td colspan="3" width="100%" class="label_form" valign="top" style="padding:4px"><span>'.( $request ? '* ': '' ). ucfirst($label). '</span></td>
		 
	</tr>';

	else
	$view  ='
	<tr   style="_border-top: 1px solid #CDCDCD;_border-bottom: 1px solid #CDCDCD;border-top: 1px solid #CDCDCD;border-bottom: 1px solid #CDCDCD; ">
		<td width="20%" class="label_form" valign="top" style="padding:4px"><span>'.( $request ? '* ': '' ). ucfirst($label). '</span></td>
		<td width="40%" style="padding:4px">'.$form . ' </td>
		<td width="30%" style="padding:4px">'.$nilai . ' </td>
	</tr>';
	return $view;
}

function form_header( $label_name , $form_name ,$errors = false ){

my_set_code_js('
function handleEnter (field, event) {
		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if (keyCode == 13) {
			var i;
			for (i = 0; i < field.form.elements.length; i++)
				if (field == field.form.elements[i])
					break;
			i = (i + 1) % field.form.elements.length;
			field.form.elements[i].focus();
			return false;
		} 
		else
		return true;
}   
');
	$_SESSION['post_status'] = true;
	$viewed = '   
                     <div class="module-body">
                        <form method="POST" enctype="multipart/form-data">
						';
	if($errors){
	$viewed .= '<div>
                                <span class="notification n-error">Success notification.</span>
                            </div>';
	}
	return $viewed;
}

function form_footer(){
	 $viewed = '
	  </form>
                     </div> <!-- End .module-body -->
 ';
	return $viewed;
}
function form_header_in_tab(){

}