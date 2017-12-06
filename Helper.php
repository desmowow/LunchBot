<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 15:15
 */

namespace LunchBot;


class Helper
{
    /**
     * @param string[] $keywords
     * @param string $text
     * @return bool
     */
    static function CheckKeyWord($keywords, $text){
        foreach($keywords as $word){
            if(strpos($text, $word) !== false){
                return true;
            }
        }
        return false;
    }




}