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


$firstArrayKeyWord = ["dove","andiamo","cosa","si"];
$secondArrayKeyWord = ["mangiamo","mangiare","mangia","mangio"];
$lunchPlace = [
  new Place("Giga Vigliacca",									true,true,true,true,true,true,true),
  new Place("Piadina",											true,true,true,true,true,true,true),
  new Place("Pazzi",											true,true,true,true,true,true,true),
  new Place("Focacciaro",										true,true,false,false,false,false,false),
  new Place("Cerchiamo di far sorridere la Giga Vigliacca",		true,true,true,true,true,true,true),
  new Place("io consiglierei il kebabbaro di fiducia",			true,true,false,false,false,false,false),
  new Place("oggi una rucola e stracchino ci sta tutta eh",		true,true,false,false,false,false,false),
  new Place("le vigliacche sentono la nostra mancanza",			true,true,true,true,true,true,true),
  new Place("prendiamo l'autobus e adiamo al burger king",		true,true,true,true,true,true,true),
  new Place("mi dispiace puma..oggi KEBAP",						true,true,false,false,false,false,false),
  new Place("andiamo dai tipi strani",							true,true,true,true,true,true,true),
  new Place("avete rotto...oggi decide il puma",				true,true,false,false,false,false,false),
];


if(CheckKeyWord($firstArrayKeyWord, $text) && CheckKeyWord($secondArrayKeyWord, $text)){
	$place = Place::GetRandomPlace($lunchPlace);
	PrintJsonMessage($place->Name, $chatId);
}

function CheckKeyWord($keywords, $text){
	foreach($keywords as $word){
		if(strpos($text, $word) !== false){
			return true;
		}
	}
	return false;
}

function PrintJsonMessage($message, $chatId){
	header("Content-Type: application/json");
	$parameters = array('chat_id' => $chatId, "text" => $message);
	$parameters["method"] = "sendMessage";
	echo json_encode($parameters);
}

class Place{
	public $Name;
	public $Monday;
	public $Tuesday;
	public $Wednesday;
	public $Thursday;
	public $Friday;
	public $Saturday;
	public $Sunday;
	
	public function __construct($name,	$monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday) {
        $this->Name = $name;
        $this->Monday = $monday;
        $this->Tuesday = $tuesday;
        $this->Wednesday = $wednesday;
        $this->Thursday = $thursday;
        $this->Friday = $friday;
        $this->Saturday = $saturday;
        $this->Sunday = $sunday;
    }
	
	public function IsEnabledDay($day){
		switch($day){
			case 1: return $this->Monday; break;
			case 2: return $this->Tuesday; break;
			case 3: return $this->Wednesday; break;
			case 4: return $this->Thursday; break;
			case 5: return $this->Friday; break;
			case 6: return $this->Saturday; break;
			case 7: return $this->Sunday; break;
		}
	}
	
	public static function GetRandomPlace($places, $tryNumber=1){
		$maxRetryNumber = 20;
		$random = rand(0,(count($places)-1));
		$place = $places[$random];
		$currentDay = intval(date("N"));
		if($place->IsEnabledDay($currentDay)){
			return $place;
		}else{
			if($tryNumber < $maxRetryNumber){
				return GetRandomPlace($places, ($tryNumber+1));
			}else{
				return new Place("Uffaaaa",true,true,true,true,true,true,true);
			}
		}
	}
}















