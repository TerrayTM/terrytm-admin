<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Build extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;

    public function find_parent() {
        return Push::find($this->push_id);
    }
}

?>