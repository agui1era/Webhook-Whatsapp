<?php


//get a message, triggered by app chat api

$data = json_decode(file_get_contents('php://input'), true);
$mensaje=$data["messages"]["0"]["body"];
$id = $data["messages"]["0"]["chatId"];
$flag_loop = $data["messages"]["0"]["fromMe"];

//$numero=explode("@",$id);


$pos =  1;
$pos2 = 1;
$pos3 = 1;


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



//echo $mensaje;
echo "pos".$pos."</BR>";
echo "pos2".$pos2."</BR>";
echo "pos3".$pos3."</BR>";
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


$respuesta = $resultDecode->{'output'}->{'text'}[0];
$confidence = $resultDecode->{'output'}->{'text'}[0];

 
echo "Respuesta: ".$respuesta."</BR>";

echo $confidence;



if ($flag_loop == 0)
	
	{

		$url = 'https://eu48.chat-api.com/instance51261/message?token=7ur7q8g8ayov0chi';
		$ch = curl_init($url);
		$jsonData = array(
			'chatId'=>$id,
			'body' =>$respuesta);
     
		$jsonDataEncoded = json_encode($jsonData);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
		$result = curl_exec($ch);
		echo $result;
	
    }
	}
?>