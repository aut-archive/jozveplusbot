#!/usr/bin/env php
<?php
require_once __DIR__ . '/telegram.php';
global $telegram;

function get_updates()
{
    global $telegram;
    try {
        // Handle telegram getUpdates request
        $serverResponse = $telegram->handleGetUpdates();

        if ($serverResponse->isOk()) {
            $updateCount = count($serverResponse->getResult());
            if ($updateCount)
                echo PHP_EOL . date('Y-m-d H:i:s', time()) . ' - Processed ' . $updateCount . ' updates';
            else echo ".";
        } else {
            echo PHP_EOL . date('Y-m-d H:i:s', time()) . ' - Failed to fetch updates';
            echo $serverResponse->printError();
        }

    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        echo $e;
        // Log telegram errors
        Longman\TelegramBot\TelegramLog::error($e);

    } catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
        // Catch log init errors
        echo $e;
    }
}

while (true) {
    get_updates();
    sleep(1);
}