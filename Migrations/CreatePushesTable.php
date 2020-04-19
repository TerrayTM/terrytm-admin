<?php

class CreatePushesTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("pushes")) {
            $this->capsule::schema()->create("pushes", function ($table) {
                $table->increments("id");
                $table->string("repository", 64)->index();
                $table->string("url", 64);
                $table->string("user", 32);
                $table->string("email", 32)->nullable();
                $table->boolean("is_built")->default(false)->index();
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("pushes");
    }
}

?>