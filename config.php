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
var_dump(DATABASE_NAME);
/* KONFIGURASI PAGING */
define("PAGING_PERHALAMAN" , 14);
define("SCROLL_PERHALAMAN" ,  5);

/* KONFIGURASI TEMPLATE LABEL */
define( "_CLIENT_LABEL" , "Gunturris NetMedia");
define( "_FOOTER_LABEL" , "Design by <a href=\"#\">PrintDiGo Designer</a>");

define("DEFAULT_TEXT_TANGGAL","'2014-09-24'");
var_dump(DATABASE_NAME);
exit;
