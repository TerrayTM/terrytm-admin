<?php

class CreateSSLTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("ssl")) {
            $this->capsule::schema()->create("ssl", function ($table) {
                $table->increments("id");
                $table->string("url", 128);
                $table->boolean("is_valid")->default(true);
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("ssl");
    }
}

?>