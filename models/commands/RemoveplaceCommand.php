<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 07-Dec-17
 * Time: 14:52
 */
namespace Longman\TelegramBot\Commands\UserCommands;

use app\models\Place;
use Exception;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\TelegramLog;
use yii\db\StaleObjectException;

class RemoveplaceCommand extends UserCommand
{

    protected $name = 'removeplace';
    protected $description = 'Remove lunch place';
    protected $usage = '/removeplace <id>';
    protected $version = '1.1.0';

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $chat_id = $chat->getId();
        $id = trim($message->getText(true));
        if ($id === '') {
            $text = 'Command usage: ' . $this->getUsage();
        }elseif(intval($id) <= 0){
            $text = 'Command usage: ' . $this->getUsage();
        }else{
            $place = Place::findOne(["id"=>$id]);
            if(!$place){
                $text = "Place with id='".$id."' not found!";
            }else{
                try {
                    $place->delete();
                    $text = "'".$place->Name."' removed!";
                } catch (StaleObjectException $e) {
                    TelegramLog::error($e);
                } catch (Exception $e) {
                    TelegramLog::error($e);
                }
            }
        }
                
        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];
        return Request::sendMessage($data);
    }
}