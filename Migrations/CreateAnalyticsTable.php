<?php

class CreateAnalyticsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("analytics")) {
            $this->capsule::schema()->create("analytics", function ($table) {
                $table->increments("id");
                $table->string("url", 128);
                $table->string("address", 64)->nullable();
                $table->string("group", 32);
                $table->string("referrer", 16);
                $table->boolean("is_error")->index();
                $table->timestamp("timestamp")->useCurrent();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("analytics");
    }
}

?>