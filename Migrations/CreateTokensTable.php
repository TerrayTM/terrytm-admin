<?php

class CreateTokensTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("tokens")) {
            $this->capsule::schema()->create("tokens", function ($table) {
                $table->increments("id");
                $table->string("value", 64)->index();
                $table->boolean("is_consumed")->default(false);
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("tokens");
    }
}

?>