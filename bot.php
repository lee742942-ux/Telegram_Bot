<?php

file_put_contents("debug.txt", "HIT: " . date("H:i:s") . PHP_EOL, FILE_APPEND);

$input = file_get_contents("php://input");
file_put_contents("debug.txt", "INPUT: " . $input . PHP_EOL, FILE_APPEND);

http_response_code(200);

echo "ok";
?>
