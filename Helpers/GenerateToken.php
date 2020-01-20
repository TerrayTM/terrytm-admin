<?php

if (!function_exists("generate_token")) {
    function generate_token($length = 64, $keySpace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
        $pieces = [];
        $max = mb_strlen($keySpace, "8bit") - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces[] = $keySpace[random_int(0, $max)];
        }
        return implode("", $pieces);
    }
}

?>