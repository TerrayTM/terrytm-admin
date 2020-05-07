<?php

require_once(__DIR__ . "/../Helpers/Response.php");

if (isset($_SERVER['CONTENT_TYPE'])) {
    try {
        switch ($_SERVER['CONTENT_TYPE']) {
            case "application/json":
                $_POST = json_decode(file_get_contents("php://input"), true);
                
                break;
            case "text/plain": 
                $text = file_get_contents("php://input");
                $_POST = [];

                foreach (array_filter(explode("\n", $text)) as $line) {
                    $parts = array_filter(explode("=", $line));

                    if (count($parts) !== 2 || isset($_POST[$parts[0]])) {
                        throw new Exception();
                    }

                    $_POST[$parts[0]] = $parts[1];
                }

                break;
        }
    } catch (Exception $exception) {
        response_error("An error has occurred when trying to parse content body.");
    }
}


?>