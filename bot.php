<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

http_response_code(200);

$botToken = "YOUR_NEW_TOKEN_HERE";
$website = "https://api.telegram.org/bot".$botToken;

$update = json_decode(file_get_contents("php://input"), true);

// log updates (optional)
file_put_contents("log.txt", json_encode($update) . PHP_EOL, FILE_APPEND);

// get message data safely
if (isset($update["message"])) {

    $chatId = $update["message"]["chat"]["id"];
    $text = $update["message"]["text"] ?? "";

    // reply logic
    if ($text == "/start") {
        $reply = "Welcome 👋";
    } elseif ($text == "/help") {
        $reply = "Send me a message and I'll reply!";
    } else {
        $reply = "You said: " . $text;
    }

    // send message using Telegram API
    file_get_contents($website."/sendMessage?chat_id=".$chatId."&text=".urlencode($reply));
}

?>
