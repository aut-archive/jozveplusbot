<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;

/**
 * User "/start" command
 */
class _StartCommand extends UserCommand
{

    protected $name = 'start';
    protected $description = 'شروع کار با ربات';
    protected $usage = '/start';

    /** @var  \Telegram */
    protected $telegram;
    protected $conversation;

    public function execute()
    {

        $message = $this->getMessage();
        $chat = $message->getChat();
        $user = $message->getFrom();
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        $text = $message->getText();

        $data = [];
        $send = false;
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        if ($text == 'ارسال جزوه') {
            $this->conversation->stop();
            $this->telegram->executeCommand("submit");
            $send = true;
        } else if ($text == 'درباره ربات') {
            $this->conversation->stop();
            $this->telegram->executeCommand("about");
            $send = true;
        } else {
            $keyboard = new Keyboard(
                ['درباره ربات', 'ارسال جزوه']
            );
            $keyboard->setResizeKeyboard(true);
            $data = [
                'chat_id' => $chat_id,
                'text' => 'یکی از گزینه‌ها رو انتخاب کنید.',
                'keyboard' => $keyboard
            ];
        }

        if ($send) {
            return Request::sendMessage($data);
        } else {
            return true;
        }

    }
}
