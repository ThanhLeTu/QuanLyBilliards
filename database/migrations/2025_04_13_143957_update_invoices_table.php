<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Chỉ thêm cột 'duration' nếu chưa có
            if (!Schema::hasColumn('invoices', 'duration')) {
                $table->string('duration')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
};
