<?php
/*
	Pastikan versi PHP adalah 5.2.X atau yang lebih baru
	Dengan MySQL ver 5.x atau yang lebih baru
*/
 
 
/* KONFIGURASI KONEKSI DATABASE */


ini_set("display_errors", 1 );
if( $_SERVER['HTTP_HOST'] == "18.141.9.181" ){
    define( "DATABASE_HOST" , "localhost" ); 
    define( "DATABASE_USER" , "root" );
    define( "DATABASE_PASSWORD" , "0r4nggila" );
    define( "DATABASE_NAME" , "enginepoker" );
}else{ 
    define( "DATABASE_HOST" , "sql2.freesqldatabase.com" ); 
    define( "DATABASE_USER" , "sql2290984" );
    define( "DATABASE_PASSWORD" , "gE8*dY9!" );
    define( "DATABASE_NAME" , "sql2290984" );
} 

/* KONFIGURASI PAGING */
define("PAGING_PERHALAMAN" , 14);
define("SCROLL_PERHALAMAN" ,  5);

/* KONFIGURASI TEMPLATE LABEL */
define( "_CLIENT_LABEL" , "Gunturris NetMedia");
define( "_FOOTER_LABEL" , "Design by <a href=\"#\">PrintDiGo Designer</a>");

define("DEFAULT_TEXT_TANGGAL","'2014-09-24'");

/* DEFAULT AKSES */
define( "DEFAULT_WEB_URL" ,"bank"); 
//define("_NET_ADDR" ,"../"); 
define("_NET_ADDR" ,__DIR__ . "/" ); 
var_dump(DATABASE_NAME);
//DISINI KEBAWAH JANGAN DI EDIT
/* ROOT PATH*/
define( "MY_ROOT_PATH" , _NET_ADDR ); 
define( "MY_FILES_PATH" ,   _NET_ADDR."files/upload/");  
define( "MY_COMPONENT_PATH" ,   _NET_ADDR."components/"); 
define( "PATH_TEMPLATES" ,   "templates/");
define("__TEMPLATE_NAME__" , "bs-binary-admin");
/* FILES PATH*/
define( "PATH_ICON" ,   __TEMPLATE_NAME__. "/icons/"); 
var_dump(DATABASE_HOST)
include_once( 'autoload.php'); 
 var_dump($_SERVER);
//INISIASI KODE  
