<?php

class CreateSecurityLogTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("security_logs")) {
            $this->capsule::schema()->create("security_logs", function ($table) {
                $table->increments("id");
                $table->string("log", 128);
                $table->string("address", 64)->nullable();
                $table->boolean("important")->index();
                $table->timestamp("timestamp")->useCurrent();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("security_logs");
    }
}

?>