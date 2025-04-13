<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'start_time')) {
                $table->string('start_time')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'end_time')) {
                $table->string('end_time')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'table_price')) {
                $table->integer('table_price')->default(0);
            }
            // Bỏ customer_note nếu đã tồn tại
        });
    }
    
    
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['customer_note', 'start_time', 'end_time', 'table_price']);
        });
    }
    
};
