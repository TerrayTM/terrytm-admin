<?php

class CreateServersTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("servers")) {
            $this->capsule::schema()->create("servers", function ($table) {
                $table->increments("id");
                $table->string("url", 128);
                $table->boolean("allow_proxy")->default(false)->index();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("servers");
    }
}

?>