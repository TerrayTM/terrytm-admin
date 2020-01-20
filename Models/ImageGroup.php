<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class ImageGroup extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;

    public function images() {
        return $this->hasMany("Image", "group_id");
    }

    public function admin_url() {
        return "/Resources/Components/ImageGallery.php?id=" . $this->link_id;
    }

    public function url() {
        return "https://terrytm.com/image-group/" . $this->link_id;
    }

    public function set_delete() {
        $this->update(["is_deleted" => true]);
        $this->images()->update(["is_deleted" => true]);
    }

    public function unset_delete() {
        $this->update(["is_deleted" => false]);
        $this->images()->update(["is_deleted" => false]);
    }
}

?>