<?php

use App\Models\Menu;
use App\Models\User;
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
        Schema::create(Menu::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->string('image_url');
            $table->unsignedBigInteger('created_by_user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by_user_id')
                ->references('id')
                ->on(User::TABLE)
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
        Schema::dropIfExists('menus');
    }
};
