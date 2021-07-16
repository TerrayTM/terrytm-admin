<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BlogImage extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;

    public function url() {
        return $this->external_url ?? "https://terrytm.com/files/images/temporary/image-" . $this->id . ".jpg";
    }
}

?>
