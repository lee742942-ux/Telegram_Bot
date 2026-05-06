<?php

file_put_contents("hit.log", "HIT\n", FILE_APPEND);

header("Content-Type: text/plain");
echo "OK";
