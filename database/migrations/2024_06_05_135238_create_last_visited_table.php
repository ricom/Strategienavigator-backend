<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('last_visited_saves', function (Blueprint $table) {
            $table->foreignId("save_id")->constrained("saves")
                ->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->foreignId("user_id")->constrained("users")
                ->onDelete("CASCADE")->onUpdate("CASCADE");
            $table->timestamp("visited_at")->nullable(false);
            $table->index(["visited_at","user_id"]);
            $table->index(["visited_at"]);
            $table->primary(["save_id","user_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('last_visited');
    }
};
