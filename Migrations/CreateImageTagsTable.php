<?php

class CreateImageTagsTable {
    public static $required_migration = "CreateImagesTable";

    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("image_tags")) {
            $this->capsule::schema()->create("image_tags", function ($table) {
                $table->increments("id");
                $table->unsignedInteger("image_id");
                $table->foreign("image_id")->references("id")->on("images")->onDelete("cascade");
                $table->string("name")->index();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("image_tags");
    }
}

?>