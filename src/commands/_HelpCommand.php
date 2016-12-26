<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/help" command
 */
class _HelpCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'نمایش راهنمای دستور ها';

    /**
     * @var string
     */
    protected $usage = '/help یا /help <دستور>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $message_id = $message->getMessageId();
        $command = trim($message->getText(true));

        //Only get enabled Admin and User commands
        /** @var Command[] $command_objs */
        $command_objs = array_filter($this->telegram->getCommandsList(), function ($command_obj) {
            /** @var Command $command_obj */
            return !$command_obj->isSystemCommand() && $command_obj->isEnabled() &&
                !in_array($command_obj->getName(), ['cancel', 'help']);
        });

        //If no command parameter is passed, show the list
        if ($command === '') {
            $text = PHP_EOL . ' 👈 ' . 'لیست دستورها:' . PHP_EOL;

            foreach ($command_objs as $command) {
                $text .= sprintf(
                    '/%s - %s' . PHP_EOL,
                    $command->getName(),
                    $command->getDescription()
                );
            }

            $text .= PHP_EOL . 'برای راهنمای دقیق تر:' . PHP_EOL . '/help <دستور>';
        } else {
            $command = str_replace('/', '', $command);
            if (isset($command_objs[$command])) {
                /** @var Command $command_obj */
                $command_obj = $command_objs[$command];
                $text = sprintf(
                    'Command: %s v%s' . PHP_EOL .
                    'Description: %s' . PHP_EOL .
                    'Usage: %s',
                    $command_obj->getName(),
                    $command_obj->getVersion(),
                    $command_obj->getDescription(),
                    $command_obj->getUsage()
                );
            } else {
                $text = 'No help available: Command /' . $command . ' not found';
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'reply_to_message_id' => $message_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
