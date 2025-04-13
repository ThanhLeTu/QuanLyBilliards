<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('table_name');
            $table->integer('play_time_minutes'); // Thời gian chơi tính theo phút
            $table->decimal('play_cost', 10, 2);
            $table->decimal('services_cost', 10, 2);
            $table->decimal('total_payment', 10, 2);
            $table->timestamps();
            $table->string('customer_note')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->integer('table_price')->default(0);
               
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
