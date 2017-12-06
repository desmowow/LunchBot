<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 15:11
 */

namespace LunchBot;


class PlaceHelper
{

    /**
     * @param object $mixed
     * @return PlaceDto
     */
    public static function NewFromMixed($mixed) {
        return new PlaceDto($mixed->Name, $mixed->Monday, $mixed->Tuesday, $mixed->Wednesday, $mixed->Thursday, $mixed->Friday, $mixed->Saturday, $mixed->Sunday);
    }

    /**
     * @param PlaceDto[] $places
     * @param int $tryNumber
     * @return PlaceDto
     */
    public static function GetRandomPlace($places, $tryNumber=1){
        $maxRetryNumber = 20;
        $random = rand(0,(count($places)-1));
        $place = $places[$random];
        $currentDay = intval(date("N"));
        if($place->IsEnabledDay($currentDay)){
            return $place;
        }else{
            if($tryNumber < $maxRetryNumber){
                return self::GetRandomPlace($places, ($tryNumber+1));
            }else{
                return new PlaceDto("Uffaaaa",true,true,true,true,true,true,true);
            }
        }
    }

    /**
     * @param $json
     * @return PlaceDto[] array
     */
    public static function FromJSON($json)
    {
        $array_obj = json_decode($json);
        $result = [];
        foreach($array_obj as $obj){
            $result[] = PlaceHelper::NewFromMixed($obj);
        }
        return $result;
    }

}