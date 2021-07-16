<?php

class CreateCalendarEventsTable {
    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("calendar_events")) {
            $this->capsule::schema()->create("calendar_events", function ($table) {
                $table->increments("id");
                $table->dateTime("start_date");
                $table->dateTime("end_date");
                $table->unsignedBigInteger("event_id")->index();
                $table->string("text", 256)->nullable()->default(null);
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("calendar_events");
    }
}

?>