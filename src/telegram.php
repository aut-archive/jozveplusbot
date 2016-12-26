<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/Telegram.php';

try {
    // Create Telegram API object
    global $telegram;
    $telegram = new Telegram(BOT_API_KEY, BOT_USERNAME);

    // Error, Debug and Raw Update logging
    //Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);
    Longman\TelegramBot\TelegramLog::initErrorLog(LOGS_DIR . '/error.log');
    if (DEBUG) {
        Longman\TelegramBot\TelegramLog::initDebugLog(LOGS_DIR . '/debug.log');
        Longman\TelegramBot\TelegramLog::initUpdateLog(LOGS_DIR . '/update.log');
    }

    // Enable MySQL
    if (MYSQL_CREDENTIALS['enabled']) {
        $telegram->enableMySql(MYSQL_CREDENTIALS);
    }

    // Add an additional commands path
    $telegram->addCommandsPath(COMMANDS_PATH);

    // Enable admin users
    $telegram->enableAdmins(BOT_ADMINS);

    // Add the channel you want to manage
    //$telegram->setCommandConfig('sendtochannel', ['your_channel' => '@type_here_your_channel']);

    // Here you can set some command specific parameters,
    // for example, google geocode/timezone api key for /date command:
    //$telegram->setCommandConfig('date', ['google_api_key' => 'your_google_api_key_here']);

    // Set custom Upload and Download path
    $telegram->setDownloadPath(DOWNLOADS);
    $telegram->setUploadPath(UPLOADS_DIR);

    // Botan.io integration
    //$telegram->enableBotan('your_token');

    // Handle telegram getUpdates request
    $serverResponse = $telegram->handleGetUpdates();


} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Catch log init errors
    echo $e;
}
