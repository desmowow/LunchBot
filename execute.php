<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$content = file_get_contents("php://input");
$update = json_decode($content, true);

$firstArrayKeyWord = ["dove","andiamo","cosa","si"];
$secondArrayKeyWord = ["mangiamo","mangiare","mangia","mangio"];
$lunchPlace = Place::FromJSON(file_get_contents("lunchPlace.txt"));

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
	
	public function __construct($mixed) {
        $this->Name = $mixed->Name;
        $this->Monday = $mixed->Monday;
        $this->Tuesday = $mixed->Tuesday;
        $this->Wednesday = $mixed->Wednesday;
        $this->Thursday = $mixed->Thursday;
        $this->Friday = $mixed->Friday;
        $this->Saturday = $mixed->Saturday;
        $this->Sunday = $mixed->Sunday;
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
	
	public static FromJSON($json)
	{
		$array_obj = json_decode($json);
		$result = [];
		foreach($array_obj as $obj){
			$result[] = new self($obj);
		}
		return $result;
	}

   
}















