<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Blog extends Eloquent {
    protected $table = "blog";
    protected $guarded = [];
    public $timestamp = false;
}

?>