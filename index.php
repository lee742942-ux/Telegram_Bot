<?php

file_put_contents("hit.log", date("c") . " HIT\n", FILE_APPEND);

// Return something Telegram can see
header("Content-Type: text/plain");
echo "OK";
