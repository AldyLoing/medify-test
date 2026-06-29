<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_item_master_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('master_item_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_item_master_item');
    }
};
