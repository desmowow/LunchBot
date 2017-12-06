<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 15:11
 */

namespace LunchBot;


class PlacePersister
{

    /**
     * @param PlaceDto[] $lunchPlace
     */
    static function UpdatePlaces($lunchPlace){
        $json = json_encode($lunchPlace);
        file_put_contents("lunchPlaces.json",$json);
    }

    /**
     * @param PlaceDto[] $lunchPlace
     * @param int $index
     * @return bool
     */
    static function DeletePlaceAtIndex($lunchPlace, $index){
        if(isset($lunchPlace[$index])){
            unset($lunchPlace[$index]);
            self::UpdatePlaces($lunchPlace);
            return true;
        }else{
            return false;
        }
    }

}