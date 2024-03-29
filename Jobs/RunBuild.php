<?php

$start_time = microtime(true);
$url = "https://tuniper.terrytm.repl.co/";
$callback = "https://api.terrytm.com/main.php";

require_once(__DIR__ . "/../Helpers/WakeServer.php");
require_once(__DIR__ . "/../Partials/DatabaseConnector.php");
require_once(__DIR__ . "/../Helpers/WithReconnect.php");

if (!wake_server($url . "wake")) {
    with_reconnect(function () use ($start_time) {
        CronResult::create([
            "type" => CronType::$RunBuild,
            "is_successful" => false,
            "message" => "Tuniper is not running.",
            "duration" => microtime(true) - $start_time
        ]);
    });
} else {
    require_once(__DIR__ . "/../Config/Config.php");

    $pushes = Push::orderBy("created_at", "DESC")->get()->groupBy("repository");
    $reported = false;

    foreach ($pushes as $repository => $group) {
        $next = null;

        foreach ($group as $item) {
            $do_exit = false;

            switch ($item->status()) {
                case "Inactive":
                    if (!$next) {
                        $next = $item;
                    }
                    
                    break;
                case "Building":
                    $next = null;

                case "Built":
                    $do_exit = true;

                    break;
            }

            if ($do_exit) {
                break;
            }
        }

        if ($next) {
            $handle = curl_init();
        
            curl_setopt_array($handle, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query([
                    "repository" => $next->url,
                    "name" => $next->repository,
                    "callback" => $callback,
                    "token" => config("tuniper_token"),
                    "return" => Token::generate(),
                    "pass" => $next->id
                ])
            ]);
        
            if (curl_exec($handle)) {
                $next->is_built = true;
            
                $next->save();
            } else {
                with_reconnect(function () use ($start_time, $next) {
                    CronResult::create([
                        "type" => CronType::$RunBuild,
                        "is_successful" => false,
                        "message" => "No response from Tuniper with push ID " . $next->id . ".",
                        "duration" => microtime(true) - $start_time
                    ]);
                });

                $reported = true;

                break;
            }

            curl_close($handle);
        }
    }

    if (!$reported) {
        with_reconnect(function () use ($start_time) {
            CronResult::create([
                "type" => CronType::$RunBuild,
                "is_successful" => true,
                "duration" => microtime(true) - $start_time
            ]);
        });
    }
}

?>