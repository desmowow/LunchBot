<?php
namespace LunchBot;

try{

	$content = file_get_contents("php://input");
	$update = json_decode($content, true);

	if(!$update){ exit;	}

	$chat = new ChatMessage($update);

	$firstArrayKeyWord = ["dove","andiamo","cosa","si"];
	$secondArrayKeyWord = ["mangiamo","mangiare","mangia","mangio"];
	$lunchPlace = PlaceHelper::FromJSON(file_get_contents("lunchPlaces.json"));

	if(Helper::CheckKeyWord(["set place:","add place:"], $chat->text)){
		$placeName = substr($chat->text, 10);
		$placeName = trim($placeName);
		if($placeName!=""){
			$lunchPlace[] = new PlaceDto($placeName, true, true, true, true, true, true, true);
			PlacePersister::UpdatePlaces($lunchPlace);
            $chat->PrintJsonMessage("New place:'".$placeName."' saved");
		}else{
            $chat->PrintJsonMessage("Place name missed");
		}
	}elseif(Helper::CheckKeyWord(["get places"], $chat->text)){
		$message = "";
		foreach($lunchPlace as $k=>$p){ $message .= "[".$k."] ".$p->Name." \n"; }
        $chat->PrintJsonMessage($message);
	}elseif(Helper::CheckKeyWord(["delete place:"], $chat->text)){
		$placeId = substr($chat->text, 13);
		$placeId = trim($placeId);
		if($placeId=="first"){
			PlacePersister::DeletePlaceAtIndex($lunchPlace, 0);
		}else{
			$placeId = intval(trim($placeId));
			if($placeId>0){
				PlacePersister::DeletePlaceAtIndex($lunchPlace, $placeId);
			}
		}
	}elseif($chat->text=="get last error" || $chat->text=="get error"){
		$error = file_get_contents("last_error.txt");
        $chat->PrintJsonMessage($error);
	}elseif(Helper::CheckKeyWord($firstArrayKeyWord, $chat->text) && Helper::CheckKeyWord($secondArrayKeyWord, $chat->text)){
		$place = PlaceHelper::GetRandomPlace($lunchPlace);
		$chat->PrintJsonMessage($place->Name);
	}
		
}catch(Exception $ex){
	file_put_contents("last_error.txt",$ex->getMessage());
	file_put_contents("error.txt",$ex->getMessage()." \n",FILE_APPEND);
}





