<?php

$confidence_general=0.7;

//get a message, triggered by app chat api

$data = json_decode(file_get_contents('php://input'), true);
$mensaje=$data["messages"]["0"]["body"];
$id = $data["messages"]["0"]["chatId"];
$flag_loop = $data["messages"]["0"]["fromMe"];
//error_log(print_r($data,true), 0);
//$numero=explode("@",$id);

$endpoint='https://eu36.chat-api.com/instance62012/';
$metodo='message';
$token='ucm5oy8oa6fne7ko';

$url_watson = 'https://gateway.watsonplatform.net/assistant/api/v1/workspaces/befc8044-0d38-47a9-92b5-852db81a06ca/message?version=2019-02-28';
$username_watson = 'apikey';
$password_watson = 'l5bNJzAZlEWpFENUYaaXLiBo6DqVDiM3EBsQQoeeqINi';

$url_clima= 'http://api.meteoagro.co/v1?apikey=LXASZZNJFM3MQ0N6KWCK&idstation=CHIRECU62';


$pos=1;
$pos_eli=1;
$comando=0;


$search ='Eli';
if(preg_match("/{$search}/i", $mensaje)) {
    
$pos =0;
};


$search ='ELI';
if(preg_match("/{$search}/i", $mensaje)) {
 
 $pos =0;
	
};
	
$search ='eli';
if(preg_match("/{$search}/i", $mensaje)) {
   
 $pos_eli =0;
 
};


$search ='clima';
if(preg_match("/{$search}/i", $mensaje)) {

$pos =0;
$comando =1;
};

$search ='Clima';
if(preg_match("/{$search}/i", $mensaje)) {

$pos =0;    
$comando =1;
};


$search ='CLIMA';
if(preg_match("/{$search}/i", $mensaje)) {
 
$pos =0;
$comando =1;
	
};

$search ='video';
if(preg_match("/{$search}/i", $mensaje)) {
    
$pos =0;
$comando =2;
};

$search ='Video';
if(preg_match("/{$search}/i", $mensaje)) {
    
$pos =0;
$comando =2;
};


$search ='VIDEO';
if(preg_match("/{$search}/i", $mensaje)) {
 
$pos =0;
$comando =2;
	
};



if (($pos == 0) and ($pos_eli == 0))
    
{

//get Watson answer


$ch = curl_init($url_watson);
$jsonData = array(
    'input' => array('text' => $mensaje));
$jsonDataEncoded = json_encode($jsonData);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $username_watson . ":" . $password_watson);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
$result = curl_exec($ch);
$resultDecode =json_decode($result);
$data=json_decode($result,true);

$respuesta = $resultDecode->{'output'}->{'text'}[0];
//error_log("------------------".$respuesta . "------------------------",0);


$confidence =$data['intents'][0]['confidence'];

$conversation_id = $data['output']['context']['conversation_id'];
$dialog_turn_counter = $data['output']['context']['system']['dialog_turn_counter'];
$confidence2 =(float)$confidence ;
error_log($confidence2);

if  ($confidence2 > $confidence_general)
    
	{

if ($flag_loop == 0) 
	
	{	
	    $metodo='message';
        $endpoint = 'https://eu36.chat-api.com/instance62012/'.$metodo.'?token='.$token;
    	$ch = curl_init($endpoint);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>$respuesta);
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
			
	  	
		
	if ($comando==1)
    {
		
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: x-www-form-urlencoded'));
	//$result = curl_exec($ch);

	$result = file_get_contents($url_clima);
	// Will dump a beauty json :3
	$jsonobject = json_decode($result, true);

	echo "wind_speed_kmh : ";

	$velocidad_del_viento=$jsonobject['CurrentDataClimatic']['wind_speed_kmh'];
	echo $velocidad_del_viento;
	echo "<br/>";
	echo "temp_c : ";
	$temperatura= $jsonobject['CurrentDataClimatic']['temp_c'];
	echo $temperatura;
	$data_clima="En la ciudad de Melipilla la temperatura es: ".$temperatura." la velocidad del viento es: " .$velocidad_del_viento. " KM/H" ;
	
	
        $endpoint = 'https://eu36.chat-api.com/instance62012/'.$metodo.'?token='.$token;
    	$ch = curl_init($endpoint);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>$data_clima);
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
			
	  	
		
    }
	
	if ($comando==2)
    {
		
        $metodo='sendFile';
        $endpoint = 'https://eu36.chat-api.com/instance62012/'.$metodo.'?token='.$token;
    	$ch = curl_init($endpoint);
		$jsonData = array(
			'chatId'=>$id,
			'filename'=>"video.mp4",
			'body' =>"http://kognitive.cl/video.mp4");
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
			
	    
    }
 
   }
  }
  else
  {
        $endpoint = 'https://eu36.chat-api.com/instance62012/'.$metodo.'?token='.$token;
    	$ch = curl_init($endpoint);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>'Tengo una respuesa , pero no la seguridad de que sea correcta ,un humano la revisará para reentranarme.');
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
  }
	  
 }
	

?>