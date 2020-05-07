<?php

if (!function_exists("get_address")) {
    function get_address($cloudflare = false) {
        $address = null;
        if ($cloudflare) {
            try {
                $response = @file_get_contents("https://www.cloudflare.com/cdn-cgi/trace");

                if ($response) {
                    foreach (explode("\n", $response) as $line) {
                        $parts = explode("=", $line);
                        
                        if ($parts[0] == "ip") {
                            $address = $parts[1];

                            break;
                        }
                    }
                }
            } catch (Exception $exception) {
                $address = null;
            }
        } else {
            if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $address = $_SERVER['HTTP_CLIENT_IP'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
                $address = $_SERVER['HTTP_X_FORWARDED'];
            } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                $address = $_SERVER['HTTP_FORWARDED_FOR'];
            } else if(isset($_SERVER['HTTP_FORWARDED'])) {
                $address = $_SERVER['HTTP_FORWARDED'];
            } else if(isset($_SERVER['REMOTE_ADDR'])) {
                $address = $_SERVER['REMOTE_ADDR'];
            }
        }

        return $address;
    }
}

?>