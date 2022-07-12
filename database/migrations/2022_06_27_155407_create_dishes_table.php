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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_id')->constrained('menus')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name')->nullable()->default(NULL);
            $table->string('description')->nullable()->default(NULL);
            $table->decimal('price', 12, 2)->nullable()->default(NULL);
            $table->string('photo')->nullable()->default(NULL);
            $table->tinyInteger('active')->nullable()->default(NULL);
            $table->smallInteger('order')->nullable()->default(NULL);
            $table->decimal('kbju', 12, 2)->nullable()->default(NULL);
            $table->decimal('weight', 12, 2)->nullable()->default(NULL);
            $table->decimal('calories', 12, 2)->nullable()->default(NULL);

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
        Schema::dropIfExists('dishes');
    }
};
