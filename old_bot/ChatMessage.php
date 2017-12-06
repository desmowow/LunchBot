<?php
/**
 * Created by PhpStorm.
 * User: viola
 * Date: 06-Dec-17
 * Time: 15:19
 */

namespace LunchBot;


class ChatMessage
{
    public $message;
    public $messageId;
    public $chatId;
    public $firstname;
    public $lastname;
    public $username;
    public $date;
    public $textvar;

    function __construct($update)
    {
        $this->message = isset($update["message"]) ? $update["message"] : "";
        $this->messageId = isset($this->message["message_id"]) ? $this->message["message_id"] : "";
        $this->chatId = isset($this->message["chat"]["id"]) ? $this->message["chat"]["id"] : "";
        $this->firstname = isset($this->message["chat"]["first_name"]) ? $this->message["chat"]["first_name"] : "";
        $this->lastname = isset($this->message["chat"]["last_name"]) ? $this->message["chat"]["last_name"] : "";
        $this->username = isset($this->message["chat"]["username"]) ? $this->message["chat"]["username"] : "";
        $this->date = isset($this->message["date"]) ? $this->message["date"] : "";
        $this->text = isset($this->message["text"]) ? $this->message["text"] : "";
        $this->text = trim($this->text);
        $this->text = strtolower($this->text);
    }

    /**
     * @param string $message
     */
    function PrintJsonMessage($message){
        header("Content-Type: application/json");
        $parameters = array('chat_id' => $this->chatId, "text" => $message);
        $parameters["method"] = "sendMessage";
        echo json_encode($parameters);
    }

}