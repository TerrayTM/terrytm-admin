<?php

class CreateImageGroupsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("image_groups")) {
            $this->capsule::schema()->create("image_groups", function ($table) {
                $table->increments("id");
                $table->string("name", 32);
                $table->date("date");
                $table->string("link_id", 32)->index();
                $table->boolean("is_deleted")->default(false)->index();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("image_groups");
    }
}

?>