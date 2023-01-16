<?php

use App\Models\Ingredient;
use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Ingredient::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->unsignedFloat('amount');
            $table->string('unit', 16);
            $table->unsignedBigInteger('menu_id');
            $table->timestamps();

            $table->foreign('menu_id')
                ->references('id')
                ->on(Menu::TABLE)
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredients');
    }
};
