<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 07-Dec-17
 * Time: 14:52
 */
namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Place;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;
use yii\base\Exception;

class AddplaceCommand extends UserCommand
{

    protected $name = 'addplace';
    protected $description = 'Add new lunch place';
    protected $usage = '/addplace <name>';
    protected $version = '1.1.0';

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        try {
            $message = $this->getMessage();
            $chat = $message->getChat();
            $chat_id = $chat->getId();
            $text = trim($message->getText(true));
            if ($text === '') {
                $text = 'Command usage: ' . $this->getUsage();
            } else {
                $place = new Place();
                $place->Name = $text;
                $place->user = $chat->username ?: "";
                $place->first_name = $chat->first_name;
                $place->last_name = $chat->last_name;
                $place->save();
                $text = "'" . $place->Name . "' was saved!";
            }

            $data = [
                'chat_id' => $chat_id,
                'text' => $text,
            ];
            return Request::sendMessage($data);
        }catch (Exception $ex){
            TelegramLog::error($ex);
        }
    }
}