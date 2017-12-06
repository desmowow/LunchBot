<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 17:23
 */

namespace app\controllers;


use app\models\ChatMessage;
use app\models\Place;
use http\Exception;
use yii\db\Expression;
use \yii\helpers\Url;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

class BotController extends Controller
{

    public function actionIndex(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ["ok"=>"funziona"];
    }

    public function actionRegister(){
        $WEBHOOK_URL = Url::to(['bot/execute'],true);
        $BOT_TOKEN = Yii::$app->params["token"];

        // NON APPORTARE MODIFICHE NEL CODICE SEGUENTE
        $API_URL = 'https://api.telegram.org/bot' . $BOT_TOKEN .'/';
        $method = 'setWebhook';
        $parameters = array('url' => $WEBHOOK_URL);
        $url = $API_URL . $method. '?' . http_build_query($parameters);
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($handle);

        return $result;
    }

    public function actionSave($name){
        $place = new Place();
        $place->Name = $name;
        $place->user = "";
        $place->save();
    }

    public function actionExecute(){
        try{

            $data = Yii::$app->request->post();
            $chat = new ChatMessage($data);
            /** @var Place[] $lunchPlace */
            $lunchPlace = Place::find()->all();

            if($this->CheckKeyWord(Yii::$app->params["setKeyWord"], $chat->text)){
                $placeName = substr($chat->text, 10);
                $placeName = trim($placeName);
                if($placeName!=""){
                    $place = new Place();
                    $place->Name = $placeName;
                    $place->user = $chat->username;
                    $place->save();
                    return $chat->PrintJsonMessage("New place:'".$place->Name."' saved");
                }else{
                    return $chat->PrintJsonMessage("Place name missed");
                }
            }elseif($this->CheckKeyWord(Yii::$app->params["getKeyWord"], $chat->text)){
                $message = "";
                foreach($lunchPlace as $k=>$p){ $message .= "[".$p->id."] ".$p->Name." \n"; }
                return $chat->PrintJsonMessage($message);
            }elseif($this->CheckKeyWord(Yii::$app->params["deleteKeyWord"], $chat->text)){
                $placeId = substr($chat->text, 13);
                $placeId = trim($placeId);
                Place::deleteAll(['id'=>$placeId]);
                return $chat->PrintJsonMessage("Deleted");
            }elseif($this->CheckKeyWord(Yii::$app->params["firstArrayKeyWord"], $chat->text) && $this->CheckKeyWord(Yii::$app->params["secondArrayKeyWord"], $chat->text)){
                /** @var Place $place */
                $place = Place::find()->orderBy(new Expression('rand()'))->one();
                return $chat->PrintJsonMessage($place->Name);
            }

        }catch(Exception $ex){
            //file_put_contents("last_error.txt",$ex->getMessage());
            //file_put_contents("error.txt",$ex->getMessage()." \n",FILE_APPEND);
        }
    }

    private function CheckKeyWord($keywords, $text){
        foreach($keywords as $word){
            if(strpos($text, $word) !== false){
                return true;
            }
        }
        return false;
    }

}