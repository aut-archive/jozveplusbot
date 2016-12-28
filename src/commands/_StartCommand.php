<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use App\Lib\Telegram;
/**
 * User "/start" command
 */
class _StartCommand extends UserCommand
{
    /**#@+
     * {@inheritdoc}
     */
    protected $name = 'start';
    protected $description = 'دستور شروع';
    protected $usage = '/start';
    protected $version = '1.0.0';
    protected $enabled = true;
    protected $public = true;
    protected $message;
    /**#@-*/

    /** @var  Conversation */
    protected $conversation;

    /**
     * _StartCommand constructor.
     * @param Telegram $telegram
     * @param Update $update
     */
    public function __construct(Telegram $telegram, Update $update)
    {
        $this->need_mysql = true;
        parent::__construct($telegram, $update);
        $this->telegram = $telegram;
    }

    public function execute()
    {

        $message = $this->getMessage();

        $chat = $message->getChat();
        $user = $message->getFrom();
        $text = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        $data = [];
        $send = false;
        //Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        if ($text === 'ارسال جزوه') {
            $this->conversation->stop();
            $this->telegram->executeCommand("submit");
        } else if ($text === 'درباره ربات') {
            $this->conversation->stop();
            $this->telegram->executeCommand("about");
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