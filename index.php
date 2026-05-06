<?php

http_response_code(200);

$botToken = "8651227813:AAHkYD2pFFS6uTogvpnLmCwry8cCLSxCKWg";
$api = "https://api.telegram.org/bot".$botToken;

// =====================
// SUPABASE CONNECTION
// =====================
$dsn = "pgsql:host=db.hbflajkykuctaiyyeokf.supabase.co;port=5432;dbname=postgres";

$user = "postgres";
$pass = "ventaxit5_yt";

try {
    $conn = new PDO($dsn, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    file_put_contents("error_log.txt", $e->getMessage());
    exit;
}

// =====================
// GET TELEGRAM UPDATE
// =====================
$update = json_decode(file_get_contents("php://input"), true);

if (!isset($update["message"])) exit;

$chatId = $update["message"]["chat"]["id"];
$username = $update["message"]["from"]["username"] ?? "anon";
$text = $update["message"]["text"] ?? "";

// =====================
// SAVE QUESTION
// =====================
$stmt = $conn->prepare("INSERT INTO questions (user_id, username, question) VALUES (?, ?, ?)");
$stmt->execute([$chatId, $username, $text]);

// =====================
// RESPONSE
// =====================
if ($text == "/start") {
    $reply = "👋 Welcome to AskMate!";
} else {
    $reply = "✅ Question saved!";
}

// =====================
// SEND MESSAGE
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
