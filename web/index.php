<?php
ini_set("display_errors" , 1);

require_once(__DIR__ . "/../config.php");

$_GET['com'] = isset($_GET['com']) ? $_GET['com'] : DEFAULT_WEB_URL ;

/* 
if(! isset($_SESSION['user_id'])){
	my_direct('login.php');
} 
*/
if( ! my_is_component( $_GET['com'] ) ){
	fatal_error('Module utama tidak ditemukan');
}  
my_exec($_GET['com'] ); 
