<?php 

if (!function_exists("response_success")) {
    function response_success($data = null) {
        header("Content-Type: application/json");
        http_response_code(200);
        
        $response = ["success" => true];
        
        if ($data && count($data) > 0) {
            $response['data'] = $data;
        }

        die(json_encode($response));
    }
}

if (!function_exists("response_error")) {
    function response_error($message = null) {
        header("Content-Type: application/json");
        http_response_code(200);

        $response = ["error" => true];

        if ($message) {
            $response['message'] = $message;
        }

        die(json_encode($response));
    }
}

?>