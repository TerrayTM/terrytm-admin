<?php 

if (!function_exists("ping_test")) {
    function ping_test($url) {
        $handle = curl_init();
        
        curl_setopt_array($handle, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => "HEAD",
            CURLOPT_NOBODY => true
        ]);
        
        curl_exec($handle);

        if (curl_errno($handle)) {
            return false;
        }
        
        curl_close($handle);

        return true;
    }
}

?>
