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
$lunchPlace = Place::FromJSON(file_get_contents("lunchPlaces.json"));

if(CheckKeyWord(["set place:"], $text)){
	$placeName = substr($text, 10);
	$placeName = trim($placeName);
	if($placeName!=""){
		$lunchPlace[] = new Place($placeName, true, true, true, true, true, true, true);
		$json = json_encode($lunchPlace);
		file_put_contents("lunchPlaces.json",$json);
		PrintJsonMessage("New place:'".$placeName."' saved", $chatId);
	}else{
		PrintJsonMessage("Place name missed", $chatId);
	}
}elseif(CheckKeyWord(["get places"], $text)){
	$message = "";
	foreach($lunchPlace as $k=>$p){ $message .= "[".$k."] ".$p->Name." \n"; }
	PrintJsonMessage($message, $chatId);
}elseif(CheckKeyWord($firstArrayKeyWord, $text) && CheckKeyWord($secondArrayKeyWord, $text)){
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
	
	public function __construct($Name, $Monday, $Tuesday, $Wednesday, $Thursday, $Friday, $Saturday, $Sunday) {
        $this->Name = $Name;
        $this->Monday = $Monday;
        $this->Tuesday = $Tuesday;
        $this->Wednesday = $Wednesday;
        $this->Thursday = $Thursday;
        $this->Friday = $Friday;
        $this->Saturday = $Saturday;
        $this->Sunday = $Sunday;
    }
	
	public static function NewFromMixed($mixed) {
		return new self($mixed->Name, $mixed->Monday, $mixed->Tuesday, $mixed->Wednesday, $mixed->Thursday, $mixed->Friday, $mixed->Saturday, $mixed->Sunday);
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
	
	public static function FromJSON($json)
	{
		$array_obj = json_decode($json);
		$result = [];
		foreach($array_obj as $obj){
			$result[] = Place::NewFromMixed($obj);
		}
		return $result;
	}

   
}















