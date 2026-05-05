<?php

file_put_contents("debug.txt", "HIT: " . date("H:i:s") . "\n", FILE_APPEND);

$input = file_get_contents("php://input");
file_put_contents("debug.txt", "INPUT: " . $input . "\n", FILE_APPEND);

http_response_code(200);

echo "ok";
?>
