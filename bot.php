<?php

file_put_contents("debug.txt", "HIT ROOT\n", FILE_APPEND);

$input = file_get_contents("php://input");
file_put_contents("debug.txt", $input . "\n", FILE_APPEND);

http_response_code(200);

echo "ok";
?>
