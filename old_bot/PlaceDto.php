<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 15:09
 */

namespace LunchBot;


class PlaceDto
{
    public $Name;
    public $Description;
    public $Monday;
    public $Tuesday;
    public $Wednesday;
    public $Thursday;
    public $Friday;
    public $Saturday;
    public $Sunday;
    public $user;
    public $timestamp;

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

    /**
     * @param $day
     * @return bool
     */
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
}