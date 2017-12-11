<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Commands\UserCommands\RandomCommand;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Yii;

class GenericmessageCommand extends SystemCommand
{
    protected $name = 'Genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';
    private $firstArrayKeyWord = ["dove","andiamo","cosa","si"];
    private $secondArrayKeyWord = ["mangiamo","mangiare","mangia","mangio"];

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $text = trim($this->getMessage()->getText(true));
        $update = json_decode($this->update->toJson(), true);
        $text = strtolower($text);

        if($this->CheckKeyWord($this->firstArrayKeyWord, $text) && $this->CheckKeyWord($this->secondArrayKeyWord, $text)){
            return (new RandomCommand($this->telegram, new Update($update)))->preExecute();
        }

        return Request::emptyResponse();
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