<?php

class CreateAnalyticsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("analytics")) {
            $this->capsule::schema()->create("analytics", function ($table) {
                $table->increments("id");
                $table->string("url", 256);
                $table->string("group", 32);
                $table->boolean("is_error")->index();
                $table->timestamp("timestamp")->useCurrent();
            });
        }
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("analytics");
    }
}

?>