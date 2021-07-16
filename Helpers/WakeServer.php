<?php

if (!function_exists("wake_server")) {
    function wake_server($url) {
        $handle = curl_init();
        $identifier = rand();
    
        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
            CURLOPT_POSTFIELDS => json_encode([
                "identifier" => $identifier
            ]),
            CURLOPT_TIMEOUT => 5
        ]);
    
        $result = curl_exec($handle);
    
        curl_close($handle);
    
        if ($result !== strval($identifier)) {
            return false;
        }
    
        return true;
    }
}

?>