<?php
/*
	Pastikan versi PHP adalah 5.2.X atau yang lebih baru
	Dengan MySQL ver 5.x atau yang lebih baru
*/
 
 
/* KONFIGURASI KONEKSI DATABASE */

if( $_SERVER['HTTP_HOST'] == "localhost"){
    ini_set("display_errors", 1 );
    define( "DATABASE_HOST" , "localhost" ); 
    define( "DATABASE_USER" , "root" );
    define( "DATABASE_PASSWORD" , "" );
    define( "DATABASE_NAME" , "bontanq" );
    
}else{ 
    //define( "DATABASE_HOST" , "db575819624.db.1and1.com" ); 
    //define( "DATABASE_USER" , "dbo575819624" );
    //define( "DATABASE_PASSWORD" , "gungun123" );
    //define( "DATABASE_NAME" , "db575819624" );
}
/* KONFIGURASI PAGING */
define("PAGING_PERHALAMAN" , 14);
define("SCROLL_PERHALAMAN" ,  5);

/* KONFIGURASI TEMPLATE LABEL */
define( "_CLIENT_LABEL" , "Gunturris NetMedia");
define( "_FOOTER_LABEL" , "Design by <a href=\"#\">PrintDiGo Designer</a>");

define("DEFAULT_TEXT_TANGGAL","'2014-09-24'");

 
/* DEFAULT AKSES */
define( "DEFAULT_WEB_URL" ,"user_attendance"); 
define("_NET_ADDR" ,"../"); 

//DISINI KEBAWAH JANGAN DI EDIT
/* ROOT PATH*/
define( "MY_ROOT_PATH" ,   _NET_ADDR.""); 
define( "MY_FILES_PATH" ,   _NET_ADDR."files/upload/");  
define( "MY_COMPONENT_PATH" ,   _NET_ADDR."components/"); 
define( "PATH_TEMPLATES" ,   "templates/");
define("__TEMPLATE_NAME__" , "bs-binary-admin");
/* FILES PATH*/
define( "PATH_ICON" ,   __TEMPLATE_NAME__. "/icons/"); 
 
require_once('autoload.php'); 

//INISIASI KODE  
