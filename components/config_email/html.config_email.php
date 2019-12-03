<?php



function form_config_email_validate(){
	$errsubmit = false;
	$err = array();
	
	if( trim($_POST['server_host']) == ''){
		$errsubmit = true;
		$err[] = "Host server belum di isi";
	}
	
	if(trim($_POST['server_user']) == ''){
		$errsubmit = true;
		$err[] = "Host server belum di isi";
	}
	if( (int) $_POST['server_port'] == 0 ){
		$errsubmit = true;
		$err[] = "Port belum di buat";
	}
	
	if( $errsubmit){
		return $err;
	}
	return false;
}
	
	
function form_config_email(){
	global $errors;
	 
	$view = form_header( "form_config_emailr" , "form_config_emailr"  );
	if(trim($errors) <>''){
		$view .= $errors;
	} 
	
	$server_host = array(
			'name'=>'server_host',
			'value'=>(isset($_POST['server_host'])? $_POST['server_host'] : get_config_data('SMTP_SERVER') ),
			'id'=>'server_host',
			'type'=>'textfield',
			'class'=>'input-medium',
		);
	$form_server_host = form_dynamic($server_host);
	$view .= form_field_display( $form_server_host  , "SMTP server"  );
	
	$server_user = array(
			'name'=>'server_user',
			'value'=>(isset($_POST['server_user'])? $_POST['server_user'] : get_config_data('SMTP_USER') ),
			'id'=>'server_user',
			'type'=>'textfield',
			'class'=>'input-medium',
		);
	$form_server_user = form_dynamic($server_user);
	$view .= form_field_display( $form_server_user  , "Username"  );
		
	$server_pass = array(
			'name'=>'server_pass',
			'value'=>(isset($_POST['server_pass'])? $_POST['server_pass'] : get_config_data('SMTP_PASSWORD') ),
			'id'=>'server_pass',
			'type'=>'password',
			'class'=>'input-medium',
		);
	$form_server_pass = form_dynamic($server_pass);
	$view .= form_field_display( $form_server_pass  , "Password"  );
	 
	$server_port = array(
			'name'=>'server_port',
			'value'=>(isset($_POST['server_port'])? $_POST['server_port'] : get_config_data('SMTP_PORT') ),
			'id'=>'server_port',
			'type'=>'textfield',
			'class'=>'input-short',
		);
	$form_server_port = form_dynamic($server_port);
	$view .= form_field_display( $form_server_port  , "Server port"  );
	 
	$submit = array(
		'value' => ('  Update  '),
		'name' => 'simpan', 
		'type'=>'submit',
		'class'=>'submit-green'
	);
	$form_submit= form_dynamic($submit); 
	
	$cancel = array(
		'value' => ( '  Batal  '),
		'name' => 'cancel', 
		'type'=>'button',
		'onclick'=>'location.href=\'index.php\'',
		'class'=>'submit-gray'
	);
	$form_cancel = form_dynamic($cancel );
	 
	$view .= form_field_display( $form_submit.' '.$form_cancel,   "<hr />"  );
	$view .= form_footer( );	
	
	return  $view; 
}