<?php

class CreateNotesTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("notes")) {
            $this->capsule::schema()->create("notes", function ($table) {
                $table->increments("id");
                $table->text("note");
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