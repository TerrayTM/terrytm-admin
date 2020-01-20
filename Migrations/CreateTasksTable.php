<?php

class CreateTasksTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("tasks")) {
            $this->capsule::schema()->create("tasks", function ($table) {
                $table->increments("id");
                $table->string("text", 256);
            });
        }
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("tasks");
    }
}

?>