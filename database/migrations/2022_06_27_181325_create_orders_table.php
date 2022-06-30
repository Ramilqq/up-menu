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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('project_id')->nullable()->default(NULL);
            $table->unsignedInteger('table_id')->nullable()->default(NULL);
            $table->unsignedInteger('user_id')->nullable()->default(NULL);
            $table->string('name')->nullable()->default(NULL);
            $table->string('status')->nullable()->default(NULL);
            $table->decimal('sum', 12, 2)->nullable()->default(NULL);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
