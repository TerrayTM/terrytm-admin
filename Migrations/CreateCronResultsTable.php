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
                $table->string("message", 128)->nullable();
                $table->decimal("duration", 8, 2);
                $table->boolean("is_successful");
                $table->timestamp("timestamp")->useCurrent();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("cron_results");
    }
}

?>