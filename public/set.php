<?php
require_once __DIR__ . '/../src/telegram.php';
global $telegram;

try {
    if (isset($_REQUEST['unset'])) {
        // Delete WebHook
        $result = $telegram->deleteWebhook();
    } else {
        // Setup WebHook
        echo HOOK_URL;
        $result = $telegram->setWebhook(HOOK_URL); // ['certificate' => $path_certificate]
    }

    // Check for result
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
