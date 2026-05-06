<?php

http_response_code(200);

// =====================
// CONFIG (MOVE THESE OUTSIDE FILE LATER)
// =====================
$botToken = "8651227813:AAEfxtzZeIgMku_yDKHjfA5mRc-_7n9uXVE";
$api = "https://api.telegram.org/bot$botToken";

// =====================
// DEBUG LOG (VERY IMPORTANT)
// =====================
$input = file_get_contents("php://input");
file_put_contents("debug.json", $input);

// =====================
// DATABASE (SUPABASE)
// =====================
$dsn = "pgsql:host=db.hbflajkykuctaiyyeokf.supabase.co;port=5432;dbname=postgres;sslmode=require";
$user = "postgres";
$pass = "ventaxit5_yt";

try {
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    file_put_contents("db_error.log", $e->getMessage());
    $conn = null; // continue bot even if DB fails
}

// =====================
// PARSE UPDATE
// =====================
$update = json_decode($input, true);

if (!isset($update["message"])) {
    exit;
}

$message = $update["message"];

$chatId = $message["chat"]["id"] ?? null;
$text = trim($message["text"] ?? "");
$username = $message["from"]["username"] ?? "anon";

// =====================
// SAVE TO DATABASE (SAFE)
// =====================
if ($conn && $text !== "") {
    try {
        $stmt = $conn->prepare("
            INSERT INTO questions (user_id, username, question)
            VALUES (:user_id, :username, :question)
        ");

        $stmt->execute([
            ":user_id" => $chatId,
            ":username" => $username,
            ":question" => $text
        ]);

    } catch (Exception $e) {
        file_put_contents("sql_error.log", $e->getMessage());
    }
}

// =====================
// RESPONSE LOGIC
// =====================
if ($text === "/start") {
    $reply = "👋 Welcome to AskMate!\nAsk any question and someone may answer it.";
} else {
    $reply = "✅ Your question has been saved!";
}

// =====================
// SEND MESSAGE FUNCTION
// =====================
function sendMessage($api, $chatId, $text)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $api . "/sendMessage");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        "chat_id" => $chatId,
        "text" => $text
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        file_put_contents("curl_error.log", curl_error($ch));
    }

    file_put_contents("tg_response.log", $result);

    curl_close($ch);
}

// =====================
// SEND RESPONSE
// =====================
if ($chatId) {
    sendMessage($api, $chatId, $reply);
}

?>
