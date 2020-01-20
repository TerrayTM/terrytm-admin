<?php

require_once(__DIR__ . "/Response.php");

if (!function_exists("validate_request")) {
    function validate_request($request, $rules) {
        for ($i = 0; $i < count($rules); ++$i) {
            if (!isset($request[$rules[$i][0]])) {
                response_error("Invalid or missing parameters in request.");
            }
        
            $item = $request[$rules[$i][0]];

            switch ($rules[$i][1]) {
                case "s":
                    if (!is_string($item) || empty(trim($item))) {
                        response_error("Invalid or missing parameters in request.");
                    }

                    break;
                case "i":
                    if (!is_numeric($item)) {
                        response_error("Invalid or missing parameters in request.");
                    }

                    break;
                case "e":
                    if(!is_string($item) || !filter_var($item, FILTER_VALIDATE_EMAIL)) {
                        response_error("Invalid or missing parameters in request.");
                    }

                    break;
                default:
                    response_error("Invalid or missing parameters in request.");

                    break;
            }
        }
    }
}

?>