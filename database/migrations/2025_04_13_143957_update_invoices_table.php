<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('customer_note')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('duration')->nullable();
            $table->integer('table_price')->default(0);
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'customer_note',
                'start_time',
                'end_time',
                'duration',
                'table_price',
            ]);
        });
    }
};
