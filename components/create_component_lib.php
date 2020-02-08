<?php
 
function declare_paging(){
    $text = " 
	//PAGING CONTROL START
	\$total_records = my_num_rows(\$result );
	\$scroll_page = SCROLL_PERHALAMAN;  
	\$per_page = PAGING_PERHALAMAN;  
	\$current_page = isset(\$_GET['halaman']) ? (int) \$_GET['halaman'] : 1 ; 
	if(\$current_page < 1){
		\$current_page = 1;
	}		 
	\$task = isset(\$_GET['task']) ?\$_GET['task'] :'' ;
	\$field = isset(\$_GET['field']) ?\$_GET['field'] :'' ;
	\$key = isset(\$_GET['key']) ?\$_GET['key'] :'' ;
	\$pager_url  =\"index.php?com={\$_GET['com']}&task={\$task}&field={\$field}&key={\$key}&halaman=\";	 
	\$pager_url_last='';
	\$inactive_page_tag = 'style=\"padding:4px;background-color:#BBBBBB\"';  
	\$previous_page_text = ' Mundur '; 
	\$next_page_text = ' Maju ';  
	\$first_page_text = ' Awal '; 
	\$last_page_text = ' Akhir ';
	
	\$kgPagerOBJ = new kgPager();
	\$kgPagerOBJ->pager_set(
		\$pager_url, 
		\$total_records, 
		\$scroll_page, 
		\$per_page, 
		\$current_page, 
		\$inactive_page_tag, 
		\$previous_page_text, 
		\$next_page_text, 
		\$first_page_text, 
		\$last_page_text ,
		\$pager_url_last
		); 
	 		
	\$result = my_query(\$query .\" LIMIT \".\$kgPagerOBJ->start.\", \".\$kgPagerOBJ->per_page);  
	\$i = (\$current_page  - 1 ) * \$per_page ;
	//PAGING CONTROL END
    ";
      
    return $text;
}

function declare_js_header(){
    $text = "my_set_code_js('
		function confirmDelete(id){
            var t = confirm(\'Yakin akan menghapus data ?\');
                if(t){
                    location.href=\'index.php?com='.\$_GET['com'].'&task=delete&id=\'+id;
                }
                return false;
            }
        ');	
    ";
    return $text;
} 

function generator_function_list($module_name , $db_table, $table_column ,$dataheaders){
    
    $tablecolumn = explode("," , $table_column );
    
    $jstext = declare_js_header();
    $paging_form = declare_paging();
	$template = "
    function list_{$module_name}(){
    global \$box;
	{$jstext}
	";
	
	$template .= "\$headers= array( 
		";
	foreach($dataheaders as $key=>$dataheader){ 
		$template .= "'".trim($key)."' => array( ".trim($dataheader)." ), 
		";
	}
	$template .= "'act'=>array('width:10%','text-align:center')
	);\n";
	$template .= "
	
	
	\$query 	= \"SELECT * FROM {$db_table} \";
	\$result = my_query(\$query);
	
    {$paging_form}
	
	\$row = array();
	while(\$ey = my_fetch_array(\$result)){
		\$i++;
		\$editproperty = array(
				'href'=>'index.php?com='.\$_GET['com'].'&task=edit&id=' . \$ey['id'] , 
				'title'=>'Edit'
		);	
		\$edit_button = button_icon( 'b_edit.png' , \$editproperty  );

		\$deleteproperty = array(
			'href'=>'javascript:confirmDelete('.\$ey['id'].');',
			'title'=>'Delete', 
		);
		\$delete_button = button_icon( 'b_drop.png' , \$deleteproperty  );

		\$row[] = array( 
		";
		 
	foreach($tablecolumn as  $dataheader){ 
		$template .= "'".$dataheader."' => \$ey['{$dataheader}'],  
		";
	}			
$template .= "		'op'=> position_text_align( \$edit_button  .\$delete_button , 'right')
		);
	}
	
	\$datas = table_rows(\$row);
	\$navigasi = array(
		'<input class=\"submit-green\" type=\"button\" value=\"Tambah data\" onclick=\"javascript:location.href=\'index.php?com='.\$_GET['com'].'&task=edit\'\"/>',
		'<input class=\"submit-green\" type=\"button\" value=\"Proses\" />'
	);
	\$box = header_box( 'Data {$module_name}' , \$navigasi );
	\$paging = \$kgPagerOBJ ->showPaging();
	return table_builder(\$headers , \$datas ,  4 , false , \$paging  ); 
}

	";
	return $template;
}

function generate_function_form_submit($module_name , $tablename , $primary_key){
    $template = "

function submit_{$module_name}(\$id){
	 
	\$datas = array();";
$query = "SHOW fields FROM {$tablename}";
$result = my_query($query);
while($row = my_fetch_array($result)){

 
	
	$template .= " \$datas['{$row['Field']}']	=  my_type_data_str(\$_POST['{$row['Field']}']);
	";
		
}	
	$template .= " 
	if(\$id > 0){
		return my_update_record( '{$tablename}' , '{$primary_key}' , \$id , \$datas );
	}
	return my_insert_record( '{$tablename}' , \$datas );
}

function form_{$module_name}_validate(){
	return false;
}
	";
    return $template;
}



function generator_function_edit($module_name ,   $list_table , $primary_key ,$forms){
if( ! is_array($forms))return false;
    
	$template = "
function edit_{$module_name}(\$id){
	
	my_set_file_js(
		array(
			'assets/jquery/combomulti/jquery.chainedSelects.js',
			'assets/js/calendar/calendarDateInput.js' 
		)
	);
	\$view = form_header( \"form_{$module_name}\" , \"form_{$module_name}\"  );
	\$fields = my_get_data_by_id('{$list_table}','{$primary_key}', \$id);
";

foreach($forms as $label => $row ){ 
    
    list(  $type ,$name ) = explode("|" , $row);
    
    
	if( $type == 'textfield'  ){
        $template .= "\n".generate_form_text( $name , $label );
    }
    else
	if( $type == 'number'  ){
        $template .= "\n".generate_form_text( $name , $label );
    }
    else
    if( $type == 'calendar' ){
        $template .= "\n".generate_form_calendar( $name , $label);
    }
    else
    if( $type == 'dropdown' ){    
		$template .= "\n".generate_form_dropdown($name , $label);
    
    }
    if( $type == 'textarea' ){    
        $template .= "\n".generate_form_textarea($name , $label);
    }
	
      
}
	$template .= "	 
	\$submit = array(
		'value' => ( \$id ==0 ? ' Save ' :'  Update  '),
		'name' => 'simpan', 
		'type'=>'submit','class'=>'submit-green'
	);
	\$form_submit= form_dynamic(\$submit); 
	
	\$cancel = array(
		'value' => ( '  Cancel  '),
		'name' => 'cancel', 
		'type'=>'button',
		'onclick'=>'location.href=\'index.php?com='.\$_GET['com'].'\'',
		'class'=>'submit-gray'
	);
	\$form_cancel = form_dynamic(\$cancel );
	 
	\$view .= form_field_display( \$form_submit.' '.\$form_cancel, \"&nbsp;\" , \"<hr />\"  );
	\$view .= form_footer( );	
	
	return  \$view;
} ";
return $template;
}


function generate_form_calendar($name , $label){
	$templates ="
	\$f{$name} = date('Y-m-d');
	if(\$fields){
		list(\$yyyy{$name} , \$mm{$name}, \$dd{$name} ) = explode(\"-\" ,\$fields['{$name}'] );
		\$f{$name} = \$dd{$name}.'-'.\$mm{$name}.'-'.\$yyyy{$name};
	}
	
	\${$name} = array(
			'name'=>'{$name}',
			'value'=>(isset(\$_POST['{$name}'])? \$_POST['{$name}'] : \$f{$name}),
			'id'=>'{$name}',
			'type'=>'textfield',
			'size'=>'45'
		);
	\$form_{$name} = form_calendar(\${$name});
	\$view .= form_field_display( \$form_{$name}  , \"". $label."\" );
	";
	return $templates;
}

function generate_form_dropdown($name , $label ){
	$templates ="
	\${$name}s =  array( );
	\$query = \"SELECT {$name}_id , label FROM ".str_replace("_id","" ,$name)."\";	
	\$result = my_query(\$query);
	while(\$row_{$name} = my_fetch_array(\$result)){
		\${$name}s[\$row_{$name}['{$name}_id']] = \$row_{$name}['label'];
	}
	\${$name} = array(
		'name'=>'{$name}',
		'value'=>( isset(\$_POST['{$name}']) ? \$_POST['{$name}'] : \$fields['{$name}']) ,
	);
	\$form_{$name} = form_radiobutton(\${$name} , \${$name}s);
	\$view .= form_field_display(  \$form_{$name}   , \"". ($label)."\"    ); 
	";
	return $templates;
}

function generate_form_textarea($name , $label){
	$template ="
	
	\${$name} = array(
			'name'=>'{$name}',
			'value'=>(isset(\$_POST['{$name}'])? \$_POST['{$name}'] : \$fields['{$name}']),
			'id'=>'{$name}',
			'cols'=>'35',
			'rows'=>'5'
		);
	\$form_{$name} = form_textarea(\${$name});
	\$view .= form_field_display( \$form_{$name}  , \"". ($label)."\"  );
	
	";
	return $template;
}

function generate_form_text($name, $label){
	$template ="
	
	\${$name} = array(
			'name'=>'{$name}',
			'value'=>(isset(\$_POST['{$name}'])? \$_POST['{$name}'] : \$fields['{$name}']),
			'id'=>'{$name}',
			'type'=>'textfield',
			'size'=>'35'
		);
	\$form_{$name} = form_dynamic(\${$name});
	\$view .= form_field_display( \$form_{$name}  , \"".  $label ."\"  );
	
	";
	return $template;
}

function content_text($module_name , $title_component = ""){
	$template ="<?php
my_component_load('__jsload' , false);
my_component_load('__paging' , false);  
my_component_load('{$module_name}' );

require_once(__DIR__ .'/custom.{$module_name}.php');

\$task = isset(\$_GET['task']) ? \$_GET['task'] : ''; 
\$id = isset( \$_GET['id'] ) ? \$_GET['id']:  0;
\$modulname = '{$title_component}';

if(\$_SERVER['REQUEST_METHOD'] == \"POST\" ):
 	switch(\$task){
		case   \"edit\" :
			\$validatepost = form_{$module_name}_validate(\$id);
			if(\$validatepost){
				\$content =   message_multi_error(\$validatepost); 
				\$content .= edit_{$module_name}(\$id);
				generate_my_web(\$content,\"\",\"plain.php\");
				exit; 
			}else{
				submit_{$module_name}(\$id);
				\$content =  \"Updated\";
				my_direct('index.php?com='.\$_GET['com']);
			 }
			break; 
	}
else: 	
	if(\$task == \"edit\"){ 
		\$content =  edit_{$module_name}(\$id) ;
	}else{
		 
		load_facebox_script();
		\$content =  list_{$module_name}() ; 
	}
endif; 
generate_my_web(\$content, \$modulname );
?>
";
return $template;
}

 