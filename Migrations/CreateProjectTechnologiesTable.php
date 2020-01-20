<?php

class CreateProjectTechnologiesTable {
    public static $required_migration = "CreateProjectsTable";
    
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("project_technologies")) {
            $this->capsule::schema()->create("project_technologies", function ($table) {
                $table->increments("id");
                $table->unsignedInteger("project_id");
                $table->foreign("project_id")->references("id")->on("projects")->onDelete("cascade");
                $table->string("technology", 32);
            });
        }
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("project_technologies");
    }
}

?>