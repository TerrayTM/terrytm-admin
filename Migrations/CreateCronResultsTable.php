<?php

class CreateCronResultsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("cron_results")) {
            $this->capsule::schema()->create("cron_results", function ($table) {
                $table->increments("id");
                $table->string("type", 64)->index();
                $table->boolean("is_successful");
                $table->timestamp("timestamp")->useCurrent();
            });
        }
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("cron_results");
    }
}

?>