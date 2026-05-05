<?php

http_response_code(200);

$botToken = "8651227813:AAHkYD2pFFS6uTogvpnLmCwry8cCLSxCKWg";
$website = "https://api.telegram.org/bot".$botToken;

$update = json_decode(file_get_contents("php://input"), TRUE);

if($text == "/start") {
    $reply = "Welcome 👋";
} elseif($text == "/help") {
    $reply = "Send me a message and I'll reply!";
} else {
    $reply = "You said: " . $text;
}

    file_put_contents("log.txt", json_encode($update) . PHP_EOL, FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 1);
}

?>
