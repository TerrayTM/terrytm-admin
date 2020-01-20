<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Image extends Eloquent {
    protected $guarded = [];

    public function url($is_relative = false) {
        return ($is_relative ? "" : "https://terrytm.com") . "/files/images/" . $this->name;
    }
}

?>