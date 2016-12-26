<?php
require_once __DIR__ . '/../src/telegram.php';
global $telegram;

try {
    if(isset($_REQUEST['unset'])) {
        // Setup WebHook
        $result = $telegram->setWebhook(HOOK_URL); // ['certificate' => $path_certificate]
    } else {
        // Delete WebHook
        $result = $telegram->deleteWebhook();
    }

    // Check for result
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
