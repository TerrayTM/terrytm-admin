<?php

class CreateProjectsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("projects")) {
            $this->capsule::schema()->create("projects", function ($table) {
                $table->increments("id");
                $table->string("name", 32)->index();
                $table->string("type", 32)->index();
                $table->date("date");
                $table->string("author", 64);
                $table->text("description");
                $table->string("link", 64)->nullable();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("notes");
    }
}

?>