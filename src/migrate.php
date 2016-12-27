#!/usr/bin/env php
<?php
require_once __DIR__ . '/bootstrap.php';

// Check mysql enabled
if (!MYSQL_CREDENTIALS['enabled']) {
    echo "Mysql not enabled!";
    die(1);
}

// Initialize PDO
$dsn = 'mysql:host=' . MYSQL_CREDENTIALS['host'] . ';dbname=' . MYSQL_CREDENTIALS['database'];
$options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . 'utf8mb4'];
$pdo = new PDO($dsn, MYSQL_CREDENTIALS['user'], MYSQL_CREDENTIALS['password'], $options);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

// Read db file
$sql = file_get_contents(BASE_DIR . '/vendor/longman/telegram-bot/structure.sql');

// Execute
$pdo->exec($sql);

//
echo "Migrate done!";