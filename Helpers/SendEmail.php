<?php

if (!function_exists("send_email")) {
    function send_email($destination, $from, $message, $subject = "Message") {
        return mail($destination, $subject, $message, "From: " . $from);
    }
}

?>