<?php
function gender_porsion(){
load_graphic_model('pie3d');
	$path = '../files/services/resume.json';  
	$contents = (string) file_get_contents($path);  
	$datas = json_decode($contents ,true); 
	$data = array();
	$label = array();
	foreach($datas["jenis_kelamin"] as $key=>$value){
		$label[] = ucfirst($key);
		$data[] = $value;
	}  
	$graph = new PieGraph(290,225);
	$graph->SetShadow();
	 
	//$graph->title->Set("Perbandingan jumlah karyawan");
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	 
	$p1 = new PiePlot3D($data);
	//$p1->SetTheme("water"); 
	$p1->SetLegends($label);
	$p1->SetAngle(65);
	$p1->SetSize(0.5);
	$p1->SetCenter(0.40);
	//$p1->ExplodeAll(15);

	$graph->legend->Pos(0.05,0.35);
	$graph->legend->SetShadow(false);
	  
	$graph->Add($p1);
	$graph->Stroke();
}

function odo_last_periode( ){

	load_graphic_model('odo');  
	 
	// Create a new odometer graph (width=250, height=200 pixels)
	$graph = new OdoGraph(250,150);
	 
	$graph->title->Set('Example with scale indicators');
	 
	// Add drop shadow for graph
	$graph->SetShadow();
	 
	// Now we need to create an odometer to add to the graph.
	// By default the scale will be 0 to 100
	$odo = new Odometer(ODO_HALF);
	 
	// Add color indications
	$odo->AddIndication(0,20,"green:0.7");
	$odo->AddIndication(20,30,"green:0.9");
	$odo->AddIndication(30,60,"yellow");
	$odo->AddIndication(60,80,"orange");
	$odo->AddIndication(80,100,"red");
	 
	// Set display value for the odometer
	$odo->needle->Set(90);
	 
	//---------------------------------------------------------------------
	// Add drop shadow for needle
	//---------------------------------------------------------------------
	$odo->needle->SetShadow();
	 
	//---------------------------------------------------------------------
	// Add the odometer to the graph
	//---------------------------------------------------------------------
	$graph->Add($odo);
	 
	//---------------------------------------------------------------------
	// ... and finally stroke and stream the image back to the browser
	//---------------------------------------------------------------------
	$graph->Stroke();
}
  
function bar_daily(){ 
	load_graphic_model('bar'); 
	 
	setlocale (LC_ALL, 'et_EE.ISO-8859-1');
	$path = '../files/services/lima_hari_akhir.json';  
	$contents = (string) file_get_contents($path);  
	$datas = json_decode($contents ,true); 
 
	$data1y = array();
	$data2y = array();
	$data3y = array();
	$datax = array();
	foreach($datas as $key=>$value){
		$datax[] =  $key ; 
		$data1y[] = $value['cuti'];
		$data2y[] = $value['dinas'];
		$data3y[] = $value['ijin'];
	}  
	
	//$data1y=array(12,8,19,3,10);
	//$data2y=array(8,2,11,7,14);
	//$data3y=array(2,1,4,5,4);
	
	//$datax = array("12/02","13/02","14/02","17/02","18/02");
	// Create the graph. These two calls are always required
	$graph = new Graph(447,335);    
	$graph->SetScale("textlin");
	 
	$graph->SetShadow();
	$graph->img->SetMargin(40,30,20,40);
	 
	// Create the bar plots
	$b1plot = new BarPlot($data1y);
	$b1plot->SetFillColor("orange");
	$b1plot->SetLegend('Cuti  ');
	$b2plot = new BarPlot($data2y);
	$b2plot->SetFillColor("brown");
	$b2plot->SetLegend('Dinas  ');
	$b3plot = new BarPlot($data3y);
	$b3plot->SetFillColor("red");
	$b3plot->SetLegend('Ijin  ');
	 
	// Create the grouped bar plot
	$gbplot = new AccBarPlot(array($b1plot,$b2plot,$b3plot));
	 
	// ...and add it to the graPH
	$graph->Add($gbplot); 
	$graph->legend->SetPos(0.08 ,0.8 ,'right','bottom');
	//$graph->title->Set("Accumulated bar plots");
	$graph->xaxis->title->Set("Tanggal absensi");
	$graph->yaxis->title->Set("Jumlah status absen");
	 
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->SetTickLabels($datax);
	// Display the graph
	$graph->Stroke();
}