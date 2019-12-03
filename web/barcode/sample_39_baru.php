<?php
include "Barcode39.php"; 
$text = isset($_GET['text']) ? $_GET['text']: '0000000' ;
// set Barcode39 object 
$bc = new Barcode39($text); 

// display new barcode 
$bc->draw();