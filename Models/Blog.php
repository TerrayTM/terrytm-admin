<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Blog extends Eloquent {
    protected $table = "blog";
    protected $guarded = [];

    public function url($is_relative = false) {
        return ($is_relative ? "" : "https://terrytm.com") . "/blog/" . str_replace(" ", "-", strtolower($this->type)) . "/" . str_replace(" ", "-", strtolower($this->name));
    }
}

?>