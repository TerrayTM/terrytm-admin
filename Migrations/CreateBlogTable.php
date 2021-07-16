<?php

class CreateBlogTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("blog")) {
            $this->capsule::schema()->create("blog", function ($table) {
                $table->increments("id");
                $table->string("name", 32)->index();
                $table->string("type", 32)->index();
                $table->date("date");
                $table->string("author", 64);
                $table->text("content");
                $table->unsignedInteger("backup_id")->nullable();
                $table->foreign("backup_id")->references("id")->on("blog")->onDelete("cascade");
                $table->timestamps();
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("blog");
    }
}

?>