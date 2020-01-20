<?php

require_once(__DIR__ . "/../Partials/DatabaseConnector.php");

$urls = Server::select("url")->get()->pluck("url")->toArray();

function wake($url) {
    $handle = curl_init();
    $identifier = rand();

    curl_setopt_array($handle, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_POSTFIELDS => json_encode([
            "identifier" => $identifier
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);

    $result = curl_exec($handle);

    curl_close($handle);

    if ($result !== strval($identifier)) {
        return false;
    }

    return true;
}

foreach ($urls as $url) {
    $url .= "wake";
    $tryCount = 0;
    $success = true;

    while (!wake($url)) {
        ++$tryCount;
        
        if ($tryCount > 8) {
            $success = false;

            break;
        }

        sleep(5);
    }
}

CronResult::create([
    "type" => CronType::$WAKE_SERVER,
    "is_successful" => $success
]);

?>
