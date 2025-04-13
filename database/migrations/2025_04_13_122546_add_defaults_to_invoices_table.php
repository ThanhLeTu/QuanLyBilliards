<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('services_cost')->default(0)->change();
            $table->integer('play_cost')->default(0)->change();         
            $table->integer('play_time_minutes')->default(0)->change();
        });
    }
    
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Nếu muốn rollback, bạn có thể bỏ default đi
            $table->integer('services_cost')->change();
            $table->integer('play_cost')->change();
            $table->integer('total')->change();
            $table->integer('play_time_minutes')->change();
        });
    }
    
};
