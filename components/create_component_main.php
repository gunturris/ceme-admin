<?php
function my_query( $query ){
    global $connection;  
    $check = mysql_query($query); 
    if(! $check){
        die('Query mySQL tidak benar! : '.$query);
    }	
    return  $check ;
}


function my_fetch_array(  $objQuery ){  
    return mysql_fetch_array( $objQuery ); 
}

function generate_main($component_name , $title ){
	$path = __DIR__.'/'.$component_name;
	if(file_exists($path)){
		return false;
	}
	
	mkdir($path , 0755 , true);
    
	$control_content = content_text($component_name , $title); 
	$filename = $path.'/'.$component_name.'.php';
	$handle = fopen($filename, 'a+');
	fwrite($handle, $control_content);
	fclose($handle);
}

function generate_html($module_name,  $db_table, $table_column , $headerslist , $primary_key, $forms){
    $path = __DIR__.'/'.$module_name;
    $headers = convert_to_array($headerslist); 
    $html_content = "<?php\n";
	$html_content .= template_text($module_name , $db_table , $table_column , $headers , $primary_key, $forms);
	 
    $file_html = $path.'/html.'.$module_name.'.php';
	$handle = fopen($file_html, 'a+');
	fwrite($handle, $html_content);
	fclose($handle);
}

function generate_custom($module_name){
    $path = __DIR__.'/'.$module_name;
	$html_content = "<?php\n";
	$file_html = $path.'/custom.'.$module_name.'.php';
	$handle = fopen($file_html, 'a+');
	fwrite($handle, $html_content);
	fclose($handle);
}

function template_text($module_name , $list_table , $table_column , $dataheaders , $primary_key , $forms){
    $content = generator_function_list($module_name , $list_table, $table_column , $dataheaders);
    
    $content .= generator_function_edit($module_name ,  $list_table , $primary_key , $forms );
    
    $content .= generate_function_form_submit($module_name , $list_table , $primary_key);
    
    return $content;
}

function convert_to_array($headerslist){
    $headers = explode("\n" , trim($headerslist) );
    
    $resp = array();
    foreach($headers as $header){ 
        $text = get_string_between( $header, '{', '}');
        
        $field = explode('->' , $text );
        
        if(! is_array($field))
            continue;
        $resp[$field[0]] = trim($field[1]);
    }
    
    return $resp;
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}