<?php 

if (!function_exists("ssl_test")) {
    function ssl_test($url) {
        $stream = stream_context_create(["ssl" => ["capture_peer_cert" => true]]);
        $read = @fopen($url, "rb", false, $stream);
        $context = @stream_context_get_params($read);

        if (isset($context['options']) && isset($context['options']['ssl']) && isset($context['options']['ssl']['peer_certificate'])) {
            return !is_null($context['options']['ssl']['peer_certificate']);
        }

        return false;
    }
}

?>
