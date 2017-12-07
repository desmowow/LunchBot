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
use yii\base\Module;
use yii\db\Expression;
use \yii\helpers\Url;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class BotController extends Controller
{

    function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionIndex(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $info = file_get_contents("https://api.telegram.org/bot".Yii::$app->params["token"]."/getwebhookinfo");
        return [
            "info"=>json_decode($info),
            "execute_url"=>$WEBHOOK_URL = Url::toRoute('bot/execute','https'),
            "register_url"=>$WEBHOOK_URL = Url::toRoute('bot/register','https'),
            "get_places_url"=>$WEBHOOK_URL = Url::toRoute('bot/get-places','https'),
        ];
    }

    public function actionRegister(){
        $WEBHOOK_URL = Url::toRoute('bot/execute','https');
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
        $place->save();
    }

    public function actionExecute(){
        $data = file_get_contents("php://input");
        $data = json_decode($data, true);
        if(!$data){ throw new BadRequestHttpException("dati mancanti"); }

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
                $place->first_name = $chat->firstname;
                $place->last_name = $chat->lastname;
                $place->save();
                return $chat->SendMessage("New place:'".$place->Name."' saved");
            }else{
                return $chat->SendMessage("Place name missed");
            }
        }elseif($this->CheckKeyWord(Yii::$app->params["getKeyWord"], $chat->text)){
            $message = "";
            foreach($lunchPlace as $k=>$p){ $message .= "[".$p->id."] ".$p->Name." \n"; }
            return $chat->SendMessage($message);
        }elseif($this->CheckKeyWord(Yii::$app->params["deleteKeyWord"], $chat->text)){
            $placeId = substr($chat->text, 13);
            $placeId = trim($placeId);
            Place::deleteAll(['id'=>$placeId]);
            return $chat->SendMessage("Deleted");
        }elseif($this->CheckKeyWord(Yii::$app->params["firstArrayKeyWord"], $chat->text) && $this->CheckKeyWord(Yii::$app->params["secondArrayKeyWord"], $chat->text)){
            /** @var Place $place */
            $place = Place::find()->orderBy(new Expression('rand()'))->one();
            return $chat->SendMessage($place->Name);
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

    public function actionGetPlaces(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var Place[] $lunchPlace */
        $lunchPlace = Place::find()->all();
        return $lunchPlace;
    }

}