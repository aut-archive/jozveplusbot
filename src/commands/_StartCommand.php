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

    public function execute()
    {
        $message = $this->getMessage();
        $chat = $message->getChat();
        $chat_id = $chat->getId();

        $keyboard = new Keyboard(
            ['درباره ربات', 'ارسال جزوه']
        );
        $keyboard->setResizeKeyboard(true);

        $data = [
            'chat_id' => $chat_id,
            'text' => 'یکی از گزینه‌ها رو انتخاب کنید.',
            'reply_markup' => $keyboard
        ];

        return Request::sendMessage($data);
    }
}
