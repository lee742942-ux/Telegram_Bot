<?php

http_response_code(200);

$botToken = "8651227813:AAEJVAM6IDkPQ0s4Q7I5eMz8g4nR6eci0zU";
$website = "https://api.telegram.org/bot".$botToken;

$update = json_decode(file_get_contents("php://input"), TRUE);

if(isset($update["message"])) {
    $chatId = $update["message"]["chat"]["id"];
    $text = "Lee Bot is alive 🔥";

    file_get_contents($website."/sendMessage?chat_id=".$chatId."&text=".urlencode($text));
}

?>
