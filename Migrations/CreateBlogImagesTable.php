<?php

class CreateBlogImagesTable {
    public static $required_migration = "CreateBlogTable";

    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("blog_images")) {
            $this->capsule::schema()->create("blog_images", function ($table) {
                $table->increments("id");
                $table->unsignedInteger("blog_id")->nullable();
                $table->foreign("blog_id")->references("id")->on("blog")->onDelete("cascade");
                $table->string("external_url", 128)->nullable();
                $table->boolean("is_deleted")->default(false)->index();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("blog_images");
    }
}

?>