<?php

file_put_contents("hit.log", date("c") . " HIT\n", FILE_APPEND);

// respond to Telegram immediately
echo "OK";
