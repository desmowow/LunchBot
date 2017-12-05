<?php
$content = file_get_contents("php://input");
$update = json_decode($content, true);

if(!$update)
{
  exit;
}

$message = isset($update['message']) ? $update['message'] : "";
$messageId = isset($message['message_id']) ? $message['message_id'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstname = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";
$lastname = isset($message['chat']['last_name']) ? $message['chat']['last_name'] : "";
$username = isset($message['chat']['username']) ? $message['chat']['username'] : "";
$date = isset($message['date']) ? $message['date'] : "";
$text = isset($message['text']) ? $message['text'] : "";

$text = trim($text);
$text = strtolower($text);


$keyWordfirst = array("dove","andiamo","cosa");
$keyWordsecond = array("mangiamo","mangiare","mangia");
$lunchplace = array(
  "Giga Vigliacca",
  "Piadina",
  "Pazzi",
  "Focacciaro",
  "Cerchiamo di far sorridere la Giga Vigliacca",
  "io consiglierei il kebabbaro di fiducia",
  "oggi una rucola e stracchino ci sta tutta eh",
  "le vigliacche sentono la nostra mancanza",
  "prendiamo l'autobus e adiamo al burger king",
  "mi dispiace puma..oggi KEBAP",
  "andiamo dai tipi strani",
  "avete rotto...oggi decide il puma"
);
$random = rand(0,(count($lunchplace)-1));


foreach($keyWordfirst as $word1){
  if(strpos($text, $word1) !== false){
	  foreach($keyWordsecond as $word2){
		  if(strpos($text, $word2) !== false){
				header("Content-Type: application/json");
				$parameters = array('chat_id' => $chatId, "text" => $lunchplace[$random]);
				$parameters["method"] = "sendMessage";
				echo json_encode($parameters);
				exit;
		  }
	  }
  }
}


/*
header("Content-Type: application/json");
$parameters = array('chat_id' => $chatId, "text" => $text);
$parameters["method"] = "sendMessage";
echo json_encode($parameters);
*/
