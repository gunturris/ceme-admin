<?php
global $box, $custombar;
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
font-size: 16px;">  &nbsp; <a href="#" class="btn btn-danger square-btn-adjust">Logout</a> </div>
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
                        <a  href="index.php"><i class="fa fa-dashboard fa-2x"></i> Dashboard<span class="fa arrow"></a>
                    </li>
                    <li>
                        <a  href="index.html"><i class="fa fa-bar-chart-o fa-2x"></i> Statistics<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?com=statistics&task=turn_over">High turn over</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=high_winner">High winner</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=high_lose">High lose</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=high_chip">High chip</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=deposit">Jumlah deposit</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=withdraw">Jumlah withdraw</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=buyin_megajackpot">Buy-in Mega Jackpot</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=payout_megajackpot">Payout Mega Jackpot</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=player_last_login">Login member</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=player_active">Active member</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=player_new_register">New Register Member</a>
                            </li>
                            <li>
                                <a href="index.php?com=statistics&task=withdraw">Search Player</a>
                            </li>
                  
                            
                        </ul>
                    </li>
                            
                    <li>
                        <a  href="index.html"><i class="fa fa-gamepad fa-2x"></i> Games management<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?com=texas_games">Table</a>
                            </li>
                            <li>
                                <a href="#">Bot</a>
                            </li> 
                            <li>
                                <a href="index.php?com=texas_group">Grouping</a>
                            </li>
                            <li>
                                <a href="#">Monitoring</a>
                            </li>
                            <li>
                                <a href="#">Agent</a>
                            </li>  
                        </ul>
                    </li>
                    <li>
                        <a  href="index.html"><i class="fa fa-anchor fa-2x"></i> Tournaments<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?com=tournaments">Tournaments</a>
                            </li>
                            <li>
                                <a href="index.php?com=tournament_structure">Structure</a>
                            </li>
                            <li>
                                <a href="index.php?com=tournament_payout">Payout</a>
                            </li>
                        </ul>
                    </li>		 
                        
                    <li>
                        <a  href="index.html"><i class="fa fa-book fa-2x"></i> Accounting<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Game</a>
                            </li>
                            <li>
                                <a href="#">Free jackpot</a>
                            </li> 
                            <li>
                                <a href="#">Mega jackpot</a>
                            </li>
                            <li>
                                <a href="#">Bonus</a>
                            </li>
                            <li>
                                <a href="#">Referal</a>
                            </li> 
                            <li>
                                <a href="#">Bot</a>
                            </li>
                        </ul>
                    </li>	   
                    <li>
                        <a  href="index.html"><i class="fa fa-compress fa-2x"></i> Transactions<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <!-- li>
                                <a href="index.php?com=statistics">Statistic</a>
                            </li -->
                            <li>
                                <a href="index.php?com=transactions_deposit">Deposit</a>
                            </li> 
                            <li>
                                <a href="index.php?com=transactions_withdraw">Withdraw</a>
                            </li>
                            <li>
                                <a href="#">Manual transaction</a>
                            </li>
                            <li>
                                <a href="index.php?com=transactions_withdraw">History transaction</a>
                            </li> 
                            <li>
                                <a href="#">Limit settings</a>
                            </li>
                        </ul>
                    </li>	
                            
                    <li>
                        <a  href="index.html"><i class="fa fa-user fa-2x"></i> Players<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <!-- li>
                                <a href="index.php?com=statistics">Statistic</a>
                            </li -->
                            <li>
                                <a href="index.php?com=players">Registered players</a>
                            </li> 
                            <li>
                                <a href="index.php?com=players_transaction">Player transaction</a>
                            </li>
                            <li>
                                <a href="#">Validation</a>
                            </li>
                            <li>
                                <a href="#">History Validation</a>
                            </li>
                            <li>
                                <a href="#">Forget Password</a>
                            </li>
                            <li>
                                <a href="#">History Forget Password</a>
                            </li>
                        </ul>
                    </li>
                        
                    		
                    <li>
                        <a  href="index.html"><i class="fa fa-tags fa-2x"></i> Bank<span class="fa arrow"></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="index.php?com=bank">Set group</a>
                            </li>
                            <li>
                                <a href="#">Set bank deposit</a>
                            </li> 
                            <li>
                                <a href="#">Set bank withdraw</a>
                            </li> 
                        </ul>
                    </li>     
                        <?php /*
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
					 */ ?>          
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
                    <div class="col-md-12">
                     <h4><?php echo $sidebar; ?></h4>  
                    </div>
                </div> 
                <?php echo (isset($box) ? $box : ''); ?> 
                <?php echo (isset($custombar) ? $custombar : ''); ?> 
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
