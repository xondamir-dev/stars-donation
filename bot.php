<?php
include 'config.php';

$raw_input = file_get_contents('php://input');
$update = json_decode($raw_input, true);

$message = $update['message'] ?? null;
$callback = $update['callback_query'] ?? null;
$pre_checkout_query = $update['pre_checkout_query'] ?? null;

if ($message) {
    $chat_id = $message['chat']['id'];
    $user_id = $message['from']['id'];
    $text = $message['text'] ?? '';
    $message_id = null;
} elseif ($callback) {
    $chat_id = $callback['message']['chat']['id'];
    $user_id = $callback['from']['id'];
    $text = '';
    $data = $callback['data'];
    $message_id = $callback['message']['message_id'];
}

function bot($method, $params = []) {
    $url = API_URL . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

require 'stars-donation.php';
?>