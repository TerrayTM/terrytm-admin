<?php

class CreateProjectImagesTable {
    public static $required_migration = "CreateProjectsTable";

    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("project_images")) {
            $this->capsule::schema()->create("project_images", function ($table) {
                $table->increments("id");
                $table->unsignedInteger("project_id");
                $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade");
                $table->string("url", 128);
                $table->unsignedInteger("order");
            });
        }
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("project_images");
    }
}

?>