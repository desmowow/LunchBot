<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 17:23
 */

namespace app\controllers;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Exception\TelegramLogException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use yii\base\Module;
use \yii\helpers\Url;
use Yii;
use yii\rest\Controller;

class BotController extends Controller
{
    protected $bot_api_key;
    protected $bot_username;
    protected $telegram;
    protected $commands_path = [__DIR__ . '/../models/commands/'];

    function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->bot_api_key = Yii::$app->params["bot_api_key"];
        $this->bot_username = Yii::$app->params["bot_username"];
        $this->telegram = new Telegram($this->bot_api_key, $this->bot_username);
    }

    public function actionIndex(){ return ["result"=>"Hello..!"]; }

    public function actionRegister(){
        try {
            $hook_url = Url::toRoute('bot/hook','https');
            $result = $this->telegram->setWebhook($hook_url);
            if ($result->isOk()) {
                return [
                    "hook_url"=>$hook_url,
                    "commands_path"=>$this->commands_path,
                    "response"=>$result->getDescription()
                ];
            }
        } catch (TelegramException $e) {
            Yii::error($e);
        }
    }

    public function actionHook(){
        try {
            $this->telegram->addCommandsPaths($this->commands_path);
            // Enable admin users
            //$telegram->enableAdmins($admin_users);
            // Enable MySQL
            //$telegram->enableMySql($mysql_credentials);
            // Logging (Error, Debug and Raw Updates)
            TelegramLog::initErrorLog(__DIR__ . "/../runtime/logs/{$this->bot_username}_error.log");
            TelegramLog::initDebugLog(__DIR__ . "/../runtime/logs/{$this->bot_username}_debug.log");
            TelegramLog::initUpdateLog(__DIR__ . "/../runtime/logs/{$this->bot_username}_update.log");
            // If you are using a custom Monolog instance for logging, use this instead of the above
            //Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);
            // Set custom Upload and Download paths
            //$telegram->setDownloadPath(__DIR__ . '/Download');
            //$telegram->setUploadPath(__DIR__ . '/Upload');
            // Here you can set some command specific parameters
            // e.g. Google geocode/timezone api key for /date command
            //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);
            // Botan.io integration
            //$telegram->enableBotan('your_botan_token');
            // Requests Limiter (tries to prevent reaching Telegram API limits)
            $this->telegram->enableLimiter();
            // Handle telegram webhook request
            $this->telegram->handle();
        } catch (TelegramException $e) {
            // Silence is golden!
            //echo $e;
            // Log telegram errors
            TelegramLog::error($e);
            Yii::error($e);
        } catch (TelegramLogException $e) {
            // Silence is golden!
            // Uncomment this to catch log initialisation errors
            //echo $e;
        } catch (\Exception $e) {
        }
    }


}