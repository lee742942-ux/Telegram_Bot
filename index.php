<?php

http_response_code(200);

// =====================
// CONFIG
// =====================
$botToken = "8651227813:AAHkYD2pFFS6uTogvpnLmCwry8cCLSxCKWg";
$api = "https://api.telegram.org/bot".$botToken;

// DB CONFIG (CHANGE THIS)
$dbHost = "localhost";
$dbUser = "DB_USER";
$dbPass = "DB_PASS";
$dbName = "DB_NAME";

// =====================
// CONNECT DB
// =====================
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("DB connection failed");
}

// =====================
// GET UPDATE
// =====================
$update = json_decode(file_get_contents("php://input"), true);

// optional log (for debugging)
file_put_contents("debug.txt", json_encode($update) . PHP_EOL, FILE_APPEND);

// must be message
if (!isset($update["message"])) exit;

$chatId = $update["message"]["chat"]["id"];
$username = $update["message"]["from"]["username"] ?? "anon";
$text = $update["message"]["text"] ?? "";

// =====================
// SAVE QUESTION TO DB
// =====================
$stmt = $conn->prepare("INSERT INTO questions (user_id, username, question) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $chatId, $username, $text);
$stmt->execute();

// =====================
// BOT RESPONSE LOGIC
// =====================
if ($text == "/start") {
    $reply = "👋 Welcome to AskMate!\nSend any question and it will be posted.";
} elseif ($text == "/help") {
    $reply = "💡 Just send a question and it will be saved.";
} else {
    $reply = "✅ Your question has been posted!";
}

// =====================
// SEND MESSAGE (cURL)
// =====================
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
