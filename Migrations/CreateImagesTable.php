<?php

class CreateImagesTable {
    public static $required_migration = "CreateImageGroupsTable";

    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("images")) {
            $this->capsule::schema()->create("images", function ($table) {
                $table->increments("id");
                $table->string("name", 64)->index();
                $table->unsignedInteger("size");
                $table->unsignedInteger("group_id");
                $table->foreign("group_id")->references("id")->on("image_groups")->onDelete("cascade");
                $table->boolean("is_deleted")->default(false)->index();
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("images");
    }
}

?>