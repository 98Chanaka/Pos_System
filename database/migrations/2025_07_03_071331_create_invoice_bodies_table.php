<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('invoice_bodies')) {
            Schema::create('invoice_bodies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
                $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
                $table->string('item_code');
                $table->string('item_name');
                $table->string('company')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('discount', 5, 2)->default(0);
                $table->integer('quantity');
                $table->decimal('total', 10, 2);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('invoice_bodies');
    }
};
