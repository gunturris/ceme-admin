<?php
 


function statistic_header_box(){
    $navigasi = array(
		'<input class="submit-green" type="button" value="Turn over" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=turn_over\'"/>',
		'<input class="submit-green" type="button" value="Deposit" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=deposit\'"/>',
		'<input class="submit-green" type="button" value="Withdraw" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=withdraw\'"/>', 
		'<input class="submit-green" type="button" value="High winner" onclick="javascript:location.href=\'index.php?com='.$_GET['com'].'&task=high_winner\'"/>', 
	);
    return $navigasi;
}

function custom_bar_statistics($custombar_layout){
    global $custombar;
    $view = ' <div class="row">'.$custombar_layout.'</div>';
    $custombar = $view;
    return true;
}