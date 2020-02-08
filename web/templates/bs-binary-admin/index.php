<?php
global $box;
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php 
$com = isset($_GET['com']) ?   $_GET['com'] : ''; 
?>
    <title>  Ceme Admin</title>
	<!-- BOOTSTRAP STYLES-->
    <link href="<?php echo my_template_position(); ?>/assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="<?php echo my_template_position(); ?>/assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="<?php echo my_template_position(); ?>/assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
 
    <!-- JQUERY SCRIPTS -->
    <script src="<?php echo my_template_position(); ?>/assets/js/jquery-1.10.2.js"></script>
<?php  
 
if(isset($js_file))print $js_file;
?>

<?php  
//if(defined('JS_LIST'))print JS_LIST; 
if(isset($css_file))print $css_file;
?>

<style>      
<?php
//if(defined('JS_CODE'))print JS_CODE;
if(isset($css_code))print $css_code;
?>
</style> 
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Ceme admin</a> 
            </div>
  <div style="color: white;
padding: 15px 50px 5px 50px;
float: right;
font-size: 16px;"> Last access : 30 May 2018 &nbsp; <a href="#" class="btn btn-danger square-btn-adjust">Logout</a> </div>
        </nav>   
           <!-- /. NAV TOP  -->
                <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
				    <li class="text-center">
                        &nbsp;
                    <!-- img src="assets/img/find_user.png" class="user-image img-responsive"/ -->
					</li>
				
					
                    <li>
                        <a  href="index.html"><i class="fa fa-dashboard fa-2x"></i> Dashboard<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?com=bank">Banks</a>
                            </li>
                            <li>
                                <a href="index.php?com=dealer_users">Users</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a  href="index.html"><i class="fa fa-bar-chart-o fa-2x"></i> Players stas<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Statistic</a>
                            </li>
                            <li>
                                <a href="index.php?com=players">Players</a>
                            </li>
                            <li>
                                <a href="#">Devices</a>
                            </li>
                            <li>
                                <a href="#">Transactions</a>
                            </li>
                            <li>
                                <a href="#">Bots</a>
                            </li>
                            <li>
                                <a href="#">Agents</a>
                            </li>
                        </ul>
                    </li>					
                    <li>
                        <a  href="index.html"><i class="fa fa-list-alt fa-2x"></i> Leagues<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?com=leagues">Leagues</a>
                            </li>
                            <li>
                                <a href="index.php?com=league_venues">Venues</a>
                            </li>
                        </ul>
                    </li>			
                    <li>
                        <a  href="index.html"><i class="fa fa-anchor fa-2x"></i> Tournaments<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Tournaments</a>
                            </li>
                            <li>
                                <a href="#">Structure</a>
                            </li>
                            <li>
                                <a href="#">Payout</a>
                            </li>
                        </ul>
                    </li>			
                    <li>
                        <a  href="index.html"><i class="fa fa-picture-o fa-2x"></i> Texas Poker Games<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Games</a>
                            </li>
                            <li>
                                <a href="#">Group</a>
                            </li>
                            <li>
                                <a href="#">Jackpot</a>
                            </li>
                        </ul>
                    </li> 		
                    <li>
                        <a  href="index.html"><i class="fa fa-tags fa-2x"></i> Stores<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Store</a>
                            </li>
                            <li>
                                <a href="#">Gift</a>
                            </li> 
                        </ul>
                    </li> 
					           
                    <li>
                        <a  href="index.html"><i class="fa fa-cog fa-2x"></i> Settings</a> 
                    </li>  
                </ul>
               
            </div>
            
        </nav>  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-6">
                     <h4><?php echo $sidebar; ?></h4>  
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                      <?php echo (isset($box) ? $box : ''); ?>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <?php echo $content; ?>
                    </div>
                </div>
                 <!-- /. ROW  -->
                 <hr />
               
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="<?php echo my_template_position(); ?>/assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="<?php echo my_template_position(); ?>/assets/js/jquery.metisMenu.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="<?php echo my_template_position(); ?>/assets/js/custom.js"></script>

    
<script language="Javascript">
<?php
//if(defined('JS_CODE'))print JS_CODE;
if(isset($js_code))print $js_code;
?>
<?php
if(isset($js_jquery_code))
print  '$(document).ready(function() {
'.
$js_jquery_code
.
'})';
?>
</script>
   
</body>
</html>
