<?php

file_put_contents("test.log", "HIT\n", FILE_APPEND);

header("Content-Type: text/plain");
echo "BOT IS RUNNING";
