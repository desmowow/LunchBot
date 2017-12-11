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
use yii\db\Expression;

class GetallplacesCommand extends UserCommand
{

    protected $name = 'getallplaces';
    protected $description = 'Get all lunch place';
    protected $usage = '/getallplaces';
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        /** @var Place[] $places */
        $places = Place::find()->orderBy("id")->all();
        $text = "";
        foreach($places as $place){ $text .= "[".$place->id."] ".$place->Name." \n"; }

        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text'    => $text,
        ]);
    }
}