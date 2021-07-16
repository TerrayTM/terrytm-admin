<?php

class CreateDataTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("data")) {
            $this->capsule::schema()->create("data", function ($table) {
                $table->increments("id");
                $table->string("group", 64);
                $table->string("tag", 64)->nullable();
                $table->text("json");
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("data");
    }
}

?>
