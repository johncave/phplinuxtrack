<?php
use Dotenv\Dotenv;
use johncave\PhpLinuxTrack;

$startTime = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

$oDotEnv = new Dotenv(__DIR__ . '/../config');
$oDotEnv->load();

$oDotEnv->required(['PROJECT_TITLE', 'LANGUAGE', 'TORRENT_DIRECTORY', 'TORRENT_WEB_DIRECTORY', 'REDIS_HOST'])->notEmpty();
$oDotEnv->required(['REDIS_PORT', 'CACHE_SCRAPE', 'CACHE_TABLE'])->isInteger()->notEmpty();

$oLanguage = new PhpLinuxTrack\Language();
$oLanguage->setLanguage($_ENV['LANGUAGE']);

if (!class_exists('Redis')) {
    $oRedis = new PhpLinuxTrack\Redis($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
} else {
    $oRedis = new Redis();
    $oRedis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
}

include __DIR__ . '/../templates/layout.html.php';