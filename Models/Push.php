<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Push extends Eloquent {
    protected $guarded = [];

    public function build() {
        return $this->hasOne("Build");
    }

    public function status() {
        $status = null;

        if ($this->is_built) {
            $status = !$this->build ? "Building" : "Built";
        } else {
            $status = "Inactive";
        }

        return $status;
    }
}

?>