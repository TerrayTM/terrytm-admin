<?php

class CreateBuildsTable {
    public static $required_migration = "CreatePushesTable";

    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("builds")) {
            $this->capsule::schema()->create("builds", function ($table) {
                $table->increments("id");
                $table->text("log");
                $table->decimal("duration", 8, 2);
                $table->boolean("is_successful");
                $table->boolean("tests_passed");
                $table->boolean("setup_passed");
                $table->boolean("twine_passed");
                $table->unsignedInteger("push_id");
                $table->foreign("push_id")->references("id")->on("pushes")->onDelete("cascade");
                $table->timestamp("timestamp");
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("builds");
    }
}

?>