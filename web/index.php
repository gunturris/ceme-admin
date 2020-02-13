<?php 
var_dump("AAAAAAA");
require_once(__DIR__ . "/../config.php");
var_dump("BBBBBB");
$_GET['com'] = isset($_GET['com']) ? $_GET['com'] : DEFAULT_WEB_URL ;

/* 
if(! isset($_SESSION['user_id'])){
	my_direct('login.php');
} 
*/
 
if( ! my_is_component( $_GET['com'] ) ){
	fatal_error('Module utama tidak ditemukan');
}  
 
var_dump($_GET);
my_exec($_GET['com'] ); 
