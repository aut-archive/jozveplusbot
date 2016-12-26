<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class AboutCommand extends UserCommand
{
    protected $name = 'about';
    protected $description = 'درباره ما';
    protected $usage = '/about';

    public function execute()
    {
        $message = $this->getMessage();
        $data = [];
        $data['chat_id'] = $message->getChat()->getId();
        $data['text']='سلام جزوه بدین';

        return Request::sendMessage($data);
    }
}
