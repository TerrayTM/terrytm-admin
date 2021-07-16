<?php

class CreateRequestsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("requests")) {
            $this->capsule::schema()->create("requests", function ($table) {
                $table->increments("id");
                $table->string("url", 128);
                $table->text("json");
                $table->boolean("is_successful")->default(true);
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("requests");
    }
}

?>
