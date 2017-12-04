<?php

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
  "prendiamo l'autobus e adiamo al burger king"
);
$random = rand(0,(count($lunchplace)-1));


if(isset($Hook["params"]["message"]["text"])){

  $testo  = strtolower($Hook["params"]["message"]["text"]);
  $chatID = $Hook["params"]["message"]["chat"]["id"];
  $boitKey = $Hook["env"]["parrot_bot_key"];

  $word_array = end(explode(" ", $testo));

  foreach($keyWordfirst as $word1){
      if(strpos($testo, $word1) !== false){
          foreach($keyWordsecond as $word2){
              if(strpos($testo, $word2) !== false){
                  $message = urlencode($lunchplace[$random]);
                  $URL = "https://api.telegram.org/bot" . $boitKey . "/sendMessage?chat_id=".$chatID."&text=".$message;
                  $response = file_get_contents($URL);
                  exit;
              }
          }
      }
  }

}
