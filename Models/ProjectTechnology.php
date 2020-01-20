<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectTechnology extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ["id", "project_id"];
}

?>