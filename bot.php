<?php
declare(strict_types = 1);

use class\Curl;

require __DIR__ . '/class/Curl.php';

// Текущий сайт
$schema = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Инициализация Curl
$curl = new Curl();

// Получение сессионного токена первым запросом
$settings = ['url' => $schema.'://'.$host];
$response = $curl->request($settings);
preg_match('~value="(.*)"~', $response['body'], $token);

// Декодирование токена для чистоты эксперимента
$dtoken = html_entity_decode($token[1]);

// Отправление токена вторым запросом
$settings['post'] = "token={$dtoken}&submit=send";
$response = $curl->request($settings);
print_r($settings);
print_r($response['body']);