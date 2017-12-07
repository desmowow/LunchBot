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

class RandomCommand extends UserCommand
{

    protected $name = 'random';
    protected $description = 'Select rundom lunch place';
    protected $usage = '/random';
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
        /** @var Place $place */
        $place = Place::find()->orderBy(new Expression("rand()"))->one();

        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text'    => $place->Name,
        ]);
    }
}