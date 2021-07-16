<?php

class CreateQueryPermissionsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("query_permissions")) {
            $this->capsule::schema()->create("query_permissions", function ($table) {
                $table->increments("id");
                $table->string("model", 32)->charset("utf8")->collation("utf8_bin");
                $table->text("rules");
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("query_permissions");
    }
}

?>
