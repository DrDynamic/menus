<?php

use App\Models\Menu;
use App\Models\Ingredient;
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
        Schema::create('ingredient_menu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->unsignedFloat('amount');
            $table->string('unit', 16);

            $table->unique(['menu_id', 'ingredient_id']);

            $table->foreign('menu_id')
                ->references('id')
                ->on(Menu::TABLE);
            $table->foreign('ingredient_id')
                ->references('id')
                ->on(Ingredient::TABLE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredient_menu');
    }
};
