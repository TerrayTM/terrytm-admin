<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ProjectImage extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ["id", "project_id", "order"];
}

?>