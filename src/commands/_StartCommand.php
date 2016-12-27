<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Request;
use App\Lib\Telegram;
/**
 * User "/start" command
 */
class _StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'شروع کار با ربات';
    protected $usage = '/start';
    /** @var  Telegram */
    protected $telegram;
    /** @var  Conversation */
    protected $conversation;
    protected $need_mysql = true;

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

        Request::sendMessage([
            'chat_id' => $chat_id,
            'text' => "Got text: " . $text
        ]);

        if ($text === 'ارسال جزوه') {
            Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Got submit: " . $text
            ]);
            $this->conversation->stop();
            $this->telegram->executeCommand("submit");
        } else if ($text === 'درباره ربات') {
            Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Got about: " . $text
            ]);
            $this->conversation->stop();
            $this->telegram->executeCommand("about");
        } else {
            Request::sendMessage([
                'chat_id' => $chat_id,
                'text' => "Got else: " . $text
            ]);
            $keyboard = new Keyboard(
                ['درباره ربات', 'ارسال جزوه']
            );
            $keyboard->setResizeKeyboard(true);
            $data = [
                'chat_id' => $chat_id,
                'text' => 'یکی از گزینه‌ها رو انتخاب کنید.',
                'keyboard' => $keyboard
            ];
            $send = true;
        }

        if ($send) {
            echo 'Sending message with data: ' . $data . PHP_EOL;
            return Request::sendMessage($data);
        } else {
            echo 'Not gonna send' . PHP_EOL;
            return true;
        }
    }
}