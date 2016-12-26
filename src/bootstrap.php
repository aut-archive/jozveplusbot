<?php
// Project base dir
define('BASE_DIR', dirname(__DIR__)); // One dir up!

// Load composer
require BASE_DIR . '/vendor/autoload.php';

// Initialize .env to get configs
$env = new Dotenv\Dotenv(BASE_DIR);
$env->load();

// Bot's API key and username
define('BOT_API_KEY', getenv('BOT_API_KEY'));
define('BOT_USERNAME', getenv('BOT_USERNAME'));
define('BOT_ADMINS', explode(',', getenv('BOT_ADMINS')));

// Path to commands
define('COMMANDS_PATH', BASE_DIR . '/src/commands/');

// Debug mode
define('DEBUG', getenv('DEBUG'));

// Path to logs
define('LOGS_DIR', BASE_DIR . '/storage/logs');

// Path uploads and downloads
define('UPLOADS_DIR', BASE_DIR . '/storage/uploads');
define('DOWNLOADS', BASE_DIR . '/storage/downloads');

// WebHook URL - https://yourdomain.com
define('HOOK_URL', getenv('BASE_URL') . '/hook.php');

// MySQL database credentials used for getUpdate mode
define('MYSQL_CREDENTIALS', [
    'enabled' => json_decode(getenv('MYSQL_ENABLED')) ?: false,
    'host' => getenv('MYSQL_HOST') ?: 'localhost',
    'user' => getenv('MYSQL_USER') ?: 'bot',
    'password' => getenv('MYSQL_PASSWORD') ?: 'bot',
    'database' => getenv('MYSQL_DB') ?: 'bot',
]);
