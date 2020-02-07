<?php
require_once("create_component_lib.php");
require_once("create_component_main.php");
echo "Nama component : ";
$component_name = rtrim(fgets(STDIN));
echo "Title component : ";
$title_name = rtrim(fgets(STDIN));
echo "Penggunaan table : " ;
$table_name = rtrim(fgets(STDIN));
echo "Formulir isian : ";
$count_field = rtrim(fgets(STDIN));

generate_main($component_name , $title_name );

echo "Done ...\n\n"; 