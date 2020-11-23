<?php

class CreateAccountsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("accounts")) {
            $this->capsule::schema()->create("accounts", function ($table) {
                $table->increments("id");
                $table->string("name", 128);
                $table->string("username", 128);
                $table->string("password", 128);
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("accounts");
    }
}

?>