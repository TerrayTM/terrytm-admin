<?php

class CreateCalendarNotificationsTable {
    public static $required_migration = "CreateCalendarEventsTable";

    function __construct($capsule) {
        $this->capsule = $capsule;
    }

    public function up() {
        if (!$this->capsule::schema()->hasTable("calendar_notifications")) {
            $this->capsule::schema()->create("calendar_notifications", function ($table) {
                $table->increments("id");
                $table->string("email", 64);
                $table->boolean("should_notify")->default(true);
                $table->unsignedInteger("event_id");
                $table->foreign("event_id")->references("id")->on("calendar_events")->onDelete("cascade");
            });
            return true;
        }
        return false;
    }

    public function down() {
        $this->capsule::schema()->dropIfExists("calendar_notifications");
    }
}

?>