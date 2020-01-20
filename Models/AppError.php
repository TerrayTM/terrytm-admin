<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class AppError extends Eloquent {
    protected $table = "errors";
    protected $guarded = [];
    public $timestamps = false;
}

?>