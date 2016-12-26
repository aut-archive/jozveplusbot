<?php

class Telegram extends Longman\TelegramBot\Telegram
{

    protected $version = '1.0.0';

    public function addCommandsPath($path, $before = true)
    {
        if ($path != BASE_COMMANDS_PATH . '/UserCommands')
            parent::addCommandsPath($path, $before);
    }

    public function getCommandObject($command)
    {
        if (in_array($command, ['start', 'help', 'cancel', 'Callbackquery']))
                $command = '_' . mb_strtolower($command);

        return parent::getCommandObject($command);
    }


}

