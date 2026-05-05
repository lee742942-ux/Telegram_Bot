<?php

http_response_code(200);

$botToken = "YOUR_TOKEN";
$api = "https://api.telegram.org/bot".$botToken;

$update = json_decode(file_get_contents("php://input"), true);

file_put_contents("debug.txt", json_encode($update) . PHP_EOL, FILE_APPEND);

if (!isset($update["message"])) exit;

$chatId = $update["message"]["chat"]["id"];
$text = $update["message"]["text"] ?? "";

if ($text == "/start") {
    $reply = "Welcome 👋";
} elseif ($text == "/help") {
    $reply = "Send me a message!";
} else {
    $reply = "You said: " . $text;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api."/sendMessage");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    "chat_id" => $chatId,
    "text" => $reply
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
curl_close($ch);

?>
