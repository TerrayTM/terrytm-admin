<?php

class CreateMessagesTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("messages")) {
            $this->capsule::schema()->create("messages", function ($table) {
                $table->increments("id");
                $table->string("name", 32);
                $table->string("email", 32);
                $table->text("message");
                $table->boolean("has_seen")->default(false)->index();
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("messages");
    }
}

?>