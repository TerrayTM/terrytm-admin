<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SecurityLog extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;

    public static function log($event) {
        if ($_SERVER['SERVER_NAME'] !== AppLocation::$LOCAL_HOST) {
            require_once(__DIR__ . "/../Helpers/GetAddress.php");

            SecurityLog::create([
                "log" => $event[0],
                "address" => get_address(),
                "important" => $event[1]
            ]);
        }
    }
}

?>