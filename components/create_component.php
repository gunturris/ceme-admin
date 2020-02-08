<?php

define( "DATABASE_HOST" , "sql2.freesqldatabase.com" ); 
define( "DATABASE_USER" , "sql2290984" );
define( "DATABASE_PASSWORD" , "gE8*dY9!" );
define( "DATABASE_NAME" , "sql2290984" );
$connection = @mysql_connect(DATABASE_HOST ,DATABASE_USER , DATABASE_PASSWORD);
@mysql_select_db(DATABASE_NAME);
if( @mysql_errno($connection)  ){   
    die( mysql_errno($connection) . ": " . mysql_error($connection)    );
}


require_once("create_component_lib.php");
require_once("create_component_main.php");

$myXMLData = file_get_contents('create_component_config.xml');
$xml = simplexml_load_string($myXMLData);
$component_name = (string)  $xml->attributes()->name[0]; 
$title_name = (string)  $xml->attributes()->title[0]; 
$table_name = (string) $xml->dbtable[0];
$primary_key = (string) $xml->dbtable[0]->attributes()->key[0];
$table_column = (string) $xml->tablefield[0];
$headers = (string) $xml->headers[0];


$forms = array();
$form_text = "\n";
foreach( $xml->forms[0]->form as $formz){ 
    $label = (string) $formz[0];
    $type = (string) $formz['type'];
    $name = (string) $formz['name'];
    $forms[$label] =$type.'|'.$name;
    $form_text .= "\t  {$label} \t> ".  '(type: '.$type.' ; fieldname: '.$name.' )'."\n";
}

echo "Nama component : {$component_name}\n";

echo "Title component : {$title_name}\n"; 

echo "Database table : {$table_name}\n" ; 

echo "Database table column : {$table_column}\n" ;

echo "Database table primary key : {$primary_key}\n" ; 

echo "Header column : {$headers}\n\n" ;

echo "Form fields : {$form_text}\n\n" ; 

exit;


generate_main($component_name , $title_name );
print("Main file generated ...\n");

generate_html($component_name, $table_name , $table_column , $headers,    $primary_key , $forms);
print("HTML page generated ...\n");

generate_custom($component_name );
print("Custom code generated ...\n");

echo "Done ...\n\n"; 