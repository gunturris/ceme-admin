<?php 
ini_set("diplay_errors" , 1);
var_dump("AAAAA");
include( "/var/www/html/ceme-admin/config.php");
var_dump("BBBBBB");
$_GET['com'] = isset($_GET['com']) ? $_GET['com'] : DEFAULT_WEB_URL ;
var_dump("CCCC");
var_dump($_GET['com']);
/* 
if(! isset($_SESSION['user_id'])){
	my_direct('login.php');
} 
*/
 
if( ! my_is_component( $_GET['com'] ) ){
    var_dump("DDDDD");
	fatal_error('Module utama tidak ditemukan');
}  
 
var_dump($_GET);
my_exec($_GET['com'] ); 
