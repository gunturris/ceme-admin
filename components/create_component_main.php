<?php

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
    print("Main file generated ...\n");
}

function generate_html($module_name,  $headers){
     
	$html_content = template_text($module_name ,  $headers);
	$file_html = $path.'/html.'.$module_name.'.php';
	$handle = fopen($file_html, 'a+');
	fwrite($handle, $html_content);
	fclose($handle);
}