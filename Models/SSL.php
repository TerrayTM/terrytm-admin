<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class SSL extends Eloquent {
    protected $table = "ssl";
    protected $guarded = [];
    public $timestamps = false;
}

?>