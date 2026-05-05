<?php
file_put_contents("debug.txt", file_get_contents("php://input") . PHP_EOL, FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 1);

http_response_code(200);

$botToken = "YOUR_NEW_TOKEN";
$website = "https://api.telegram.org/bot".$botToken;

$update = json_decode(file_get_contents("php://input"), true);

// log raw updates
file_put_contents("log.txt", json_encode($update) . PHP_EOL, FILE_APPEND);

if (isset($update["message"])) {

    $chatId = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"] ?? "";

    if ($text === "/start") {
        $reply = "Welcome 👋";
    } elseif ($text === "/help") {
        $reply = "Send me a message!";
    } else {
        $reply = "You said: " . $text;
    }

    // CURL (more reliable than file_get_contents)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $website."/sendMessage");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "chat_id" => $chatId,
        "text" => $reply
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
