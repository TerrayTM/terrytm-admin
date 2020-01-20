<?php

class CreateErrorsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("errors")) {
            $this->capsule::schema()->create("errors", function ($table) {
                $table->increments("id");
                $table->text("json");
                $table->timestamp("timestamp")->useCurrent();
            });
        }
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("errors");
    }
}

?>