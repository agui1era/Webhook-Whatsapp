<?php

$confidence_general=0.7;

//get a message, triggered by app chat api

$data = json_decode(file_get_contents('php://input'), true);
$mensaje=$data["messages"]["0"]["body"];
$id = $data["messages"]["0"]["chatId"];
$flag_loop = $data["messages"]["0"]["fromMe"];

//$numero=explode("@",$id);


$pos =  1;
$pos2 = 1;
$pos3 = 1;
$comando=0;


$search ='Eli';
if(preg_match("/{$search}/i", $mensaje)) {
    
$pos =0;
};


$search ='ELI';
if(preg_match("/{$search}/i", $mensaje)) {
 
 $pos2 =0;
	
};
	
$search ='eli';
if(preg_match("/{$search}/i", $mensaje)) {
   
 $pos3 =0;
 
};


$search ='clima';
if(preg_match("/{$search}/i", $mensaje)) {
    
$comando =2;
};

$search ='Clima';
if(preg_match("/{$search}/i", $mensaje)) {
    
$comando =2;
};


$search ='CLIMA';
if(preg_match("/{$search}/i", $mensaje)) {
 
$comando =2;
	
};




//echo $mensaje;
//echo "pos".$pos."</BR>";
//echo "pos2".$pos2."</BR>";
//echo "pos3".$pos3."</BR>";
if (($pos == 0) or ($pos2 == 0) or ($pos3 == 0))
    
{

//get Watson answer

$url = 'https://gateway.watsonplatform.net/assistant/api/v1/workspaces/befc8044-0d38-47a9-92b5-852db81a06ca/message?version=2019-02-28';
$username = 'apikey';
$password = 'l5bNJzAZlEWpFENUYaaXLiBo6DqVDiM3EBsQQoeeqINi';
$ch = curl_init($url);
$jsonData = array(
    'input' => array('text' => $mensaje));
$jsonDataEncoded = json_encode($jsonData);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
$result = curl_exec($ch);
$resultDecode =json_decode($result);
$data=json_decode($result,true);

$respuesta = $resultDecode->{'output'}->{'text'}[0];



$confidence =$data['intents'][0]['confidence'];

$conversation_id = $data['extities']['output']['context']['conversation_id'];
//echo "<br>conversation_id:" ."  " . $conversation_id;
$dialog_turn_counter = $data['extities']['output']['context']['system']['dialog_turn_counter'];
//echo "<br>dialog_turn_counter:" ."  " . $dialog_turn_counter;

$confidence2 =(float)$confidence ;
echo "confidence:" ."  " . $confidence2;
echo "---repuesta  ".$respuesta;



if ($confidence2 > $confidence_general)
{



if ($flag_loop == 0) 
	
	{	
        $url = 'https://eu57.chat-api.com/instance54979/message?token=w2pbubkfb1ga58ot';	
		$ch = curl_init($url);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>$respuesta);
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
		echo "Mensaje enviado";
	
    }
}
else
		
	{
	    $url = 'https://eu57.chat-api.com/instance54979/message?token=w2pbubkfb1ga58ot';
    	$ch = curl_init($url);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>"Mi respuesta no tiene el nivel de confianza , pero aprenderé para entregartela");
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
		echo "Mensaje enviado 2";
	};
	
 if ($comando==2)
    {
		
	$url = 'http://api.meteoagro.co/v1?apikey=LXASZZNJFM3MQ0N6KWCK&idstation=CHIRECU62';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: x-www-form-urlencoded'));
	//$result = curl_exec($ch);

	$result = file_get_contents($url);
	// Will dump a beauty json :3
	$jsonobject = json_decode($result, true);

	echo "wind_speed_kmh : ";

	$velocidad_del_viento=$jsonobject['CurrentDataClimatic']['wind_speed_kmh'];
	echo $velocidad_del_viento;
	echo "<br/>";
	echo "temp_c : ";
	$temperatura= $jsonobject['CurrentDataClimatic']['temp_c'];
	echo $temperatura;
	$data_clima="En Melipilla Temperatura: ".$temperatura."° Velocidad del viento: ".$velocidad_del_viento. "KM/H";
	
	
	
        $url = 'https://eu57.chat-api.com/instance54979/message?token=w2pbubkfb1ga58ot';
    	$ch = curl_init($url);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>$data_clima);
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
		echo "Mensaje enviado 3";	
	  	
		
    }
	

}



?>