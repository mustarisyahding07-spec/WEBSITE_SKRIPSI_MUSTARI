<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('destination_city_id')->nullable()->after('customer_address');
            $table->string('destination_city_name')->nullable()->after('destination_city_id');
            $table->string('postal_code')->nullable()->after('destination_city_name');
            $table->string('courier')->nullable()->after('postal_code'); // jne, pos, tiki
            $table->string('courier_service')->nullable()->after('courier'); // REG, YES, OKE
            $table->decimal('shipping_cost', 12, 2)->default(0)->after('courier_service');
            $table->decimal('latitude', 10, 7)->nullable()->after('shipping_cost');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'destination_city_id',
                'destination_city_name',
                'postal_code',
                'courier',
                'courier_service',
                'shipping_cost',
                'latitude',
                'longitude',
            ]);
        });
    }
};
