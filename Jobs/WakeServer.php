<?php

$start_time = microtime(true);

set_time_limit(600);

require_once(__DIR__ . "/../Helpers/WakeServer.php");
require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../Helpers/WithReconnect.php");

$urls = Server::select("url")->get()->pluck("url")->toArray();

foreach ($urls as $url) {
    $url .= "wake";
    $tryCount = 0;
    $success = true;

    while (!wake_server($url)) {
        ++$tryCount;
        
        if ($tryCount > 8) {
            $success = false;

            break;
        }

        sleep(5);
    }
}

with_reconnect(function () use ($start_time, $success) {
    CronResult::create([
        "type" => CronType::$WakeServer,
        "is_successful" => $success,
        "duration" => microtime(true) - $start_time
    ]);
});

?>
