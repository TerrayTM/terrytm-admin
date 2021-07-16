<?php

$start_time = microtime(true);

set_time_limit(300);

if (!isset($suppress_sleep)) {
    sleep(180);
}

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../Helpers/WithReconnect.php");

$overall_result = true;

foreach (Request::all() as $request) {
    $handle = curl_init();
    $parameters = json_decode($request->json);

    foreach ($parameters as $key => $value) {
        if ($value === "@token") {
            $parameters->$key = Token::generate();
        } else if ($value === "@main") {
            $parameters->$key = "https://api.terrytm.com/main.php";
        } else if ($value === "@wain") {
            $parameters->$key = "https://api.terrytm.com/wain.php";
        }
    }

    curl_setopt_array($handle, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $request->url,
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
        CURLOPT_POSTFIELDS => json_encode($parameters),
        CURLOPT_TIMEOUT => 3
    ]);

    $result = curl_exec($handle);
    $is_successful = true;

    curl_close($handle);

    try {
        $result = json_decode($result);
        
        if (!isset($result->success) || !$result->success) {
            $is_successful = false;
        }
    } catch (Exception $exception) {
        $is_successful = false;
    }

    $overall_result &= $is_successful;

    $request->is_successful = $is_successful;

    $request->save();

    sleep(5);
}

with_reconnect(function () use ($start_time, $overall_result) {
    CronResult::create([
        "type" => CronType::$SendRequest,
        "is_successful" => $overall_result,
        "duration" => microtime(true) - $start_time
    ]);
});

?>
