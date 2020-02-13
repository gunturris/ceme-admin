<?php 
ini_set("diplay_errors" , 1);
var_dump("AAAAA");
include_once( "/var/www/html/ceme-admin/autoload.php");
include_once( "/var/www/html/ceme-admin/config.php");
var_dump("BBBBBB");

$_GET['com'] = isset($_GET['com']) ? $_GET['com'] : DEFAULT_WEB_URL ;
 
/* 
if(! isset($_SESSION['user_id'])){
	my_direct('login.php');
} 
*/

$comp = my_is_component( $_GET['com'] ) ;

if( $comp ){
    var_dump("DDDDD");
     exit;
	//fatal_error('Module utama tidak ditemukan');
}  
 
var_dump($_GET);
my_exec($_GET['com'] ); 
 