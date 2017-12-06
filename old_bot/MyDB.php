<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 16:15
 */

namespace LunchBot;


use Exception;
use mysqli;

class MyDB
{
    const HOST = "62.149.150.133";
    const USERNAME = "Sql457863";
    const PASSWORD = "2bb2c926";
    const DATABASE = "Sql457863_1";

    /**
     * @return mysqli
     */
    private static function Connect(){
        return new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
    }


    public static function Insert($tableName, $data){
        $c = self::Connect();
        try{
            $c->query("INSERT INTO ".$tableName." (".implode(",",array_keys($data)).")  ");
        }catch (Exception $ex){
            return false;
        }finally{
            $c->close();
        }
    }

}