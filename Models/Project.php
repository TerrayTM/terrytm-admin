<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Project extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;

    public function tags() {
        return $this->hasMany("ProjectTag");
    }

    public function images() {
        return $this->hasMany("ProjectImage");
    }

    public function technologies() {
        return $this->hasMany("ProjectTechnology");
    }

    public function url($is_relative = false) {
        return ($is_relative ? "" : "https://terrytm.com") . "/projects/" . str_replace(" ", "-", strtolower($this->type)) . "s/" . str_replace(" ", "-", strtolower($this->name));
    }
}

?>