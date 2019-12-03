<?php
//if(!is_admin())fatal_error('Akses ditolak');
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('user');
$task = isset($_GET['task']) ? $_GET['task'] : ""; 
$id = isset( $_GET['id'] ) ? $_GET['id']:  0; 

$pagename = "Aplikasi _BN_ Setelan _BN_ Pengguna aplikasi";
if($_SERVER['REQUEST_METHOD'] == "POST" ):
	switch($task){
		case   "edit" :
			form_create_user_submit($id);
			my_direct('index.php?com=user');
		break;
		case "ganti_password":
		
			$pagename =   "<li>Anda berada di:</li>
			<li>&nbsp; Admin &nbsp; </li>
			<li>&nbsp; Ganti password &nbsp; </li>";
			$validate =  form_password_user_validate();
			if($validate){ 
				$content = message_multi_error($validate);
				$content .= form_ganti_pasword(  ); 
			}else{
				form_ganti_pasword_submit($_SESSION['user_id']);
				if($_POST['auto_lout'])my_direct('login.php?logout='.md5(rand(0,100)));
				$content  = 'Updated'; 
			}
		break;
		 
	}

else: 	
	if($task == "edit"){ 
		$content = form_create_user( $id); 	 
	}elseif($task == "delete"){	
	}elseif($task == "ganti_password"){	 
		$pagename = "Aplikasi _BN_ Setelan _BN_ Ganti password";
		$content = form_ganti_pasword(  ); 
	}else{
		load_facebox_script();
		$content = userlist();	
	}
endif; 
generate_my_web($content, $pagename  );