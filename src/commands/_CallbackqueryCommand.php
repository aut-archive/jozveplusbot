<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 */
class _CallbackqueryCommand extends SystemCommand
{


    protected $name = '';
    protected $description = '';
    protected $version = '1.0.0';


    public function execute()
    {
        global $courses;

        $update = $this->getUpdate();
        $callback_query = $update->getCallbackQuery();
        $callback_data = $callback_query->getData();

        $data = [];
        $data['chat_id'] = $callback_query->getFrom()->getId();

        $data['text'] = "CALLBACK";

        return Request::sendMessage($data);
    }
}
