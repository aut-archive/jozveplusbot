<?php
namespace App\Lib;

use Longman\TelegramBot\Telegram as BaseTelegram;

class Telegram extends BaseTelegram
{
    public function addCommandsPath($path, $before = true)
    {
        if ($path != BASE_COMMANDS_PATH . '/UserCommands')
            parent::addCommandsPath($path, $before);
    }

    public function getCommandObject($command)
    {
        if (in_array($command, ['start', 'help', 'cancel','Generic', 'genericmessage']))
            $command = '_' . mb_strtolower($command);

        return parent::getCommandObject($command);
    }
}

