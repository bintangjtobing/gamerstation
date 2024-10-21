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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId("admin_id")->constrained('admins')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId("category_id")->constrained('blog_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->longText('name')->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->longText('tags')->nullable();
            $table->longText('details')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('blogs');
    }
};
