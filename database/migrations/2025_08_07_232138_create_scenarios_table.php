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
        // Create scenarios table
        Schema::create('fbr_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('scenario_code', 10)->unique(); // SN001, SN002, etc.
            $table->string('name');
            $table->text('description');
            $table->json('business_types')->nullable(); // Array of business types this scenario applies to
            $table->string('seller_ntn_cnic')->nullable();
            $table->string('seller_business_name')->nullable();
            $table->string('seller_province')->default('Sindh');
            $table->string('seller_address')->default('Karachi');
            $table->string('buyer_ntn_cnic')->nullable();
            $table->string('buyer_business_name')->nullable();
            $table->string('buyer_province')->default('Sindh');
            $table->string('buyer_address')->default('Karachi');
            $table->enum('buyer_registration_type', ['Registered', 'Unregistered'])->nullable();
            $table->string('invoice_ref_no')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('scenario_code');
            $table->index(['is_active', 'scenario_code']);
        });

        // Create scenario items table for sample item data
        Schema::create('fbr_scenario_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scenario_id')->constrained('fbr_scenarios')->onDelete('cascade');
            $table->string('hs_code')->nullable();
            $table->string('product_description')->nullable();
            $table->string('rate')->nullable(); // Can be percentage, fixed amount, or text like "Exempt"
            $table->string('uom')->nullable(); // Unit of Measurement
            $table->decimal('quantity', 15, 4)->default(0);
            $table->decimal('value_sales_excluding_st', 15, 2)->default(0);
            $table->decimal('sales_tax_applicable', 15, 2)->default(0);
            $table->decimal('total_values', 15, 2)->default(0);
            $table->decimal('fixed_notified_value_or_retail_price', 15, 2)->default(0);
            $table->decimal('sales_tax_withheld_at_source', 15, 2)->default(0);
            $table->decimal('extra_tax', 15, 2)->default(0);
            $table->decimal('further_tax', 15, 2)->default(0);
            $table->decimal('fed_payable', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('sro_schedule_no')->nullable();
            $table->string('sro_item_serial_no')->nullable();
            $table->string('sale_type')->nullable();
            $table->timestamps();

            $table->index('scenario_id');
            $table->index('hs_code');
            $table->index('sale_type');
        });

        // Create business type scenarios mapping table
        Schema::create('fbr_business_type_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('business_type', 50);
            $table->string('scenario_code', 10);
            $table->timestamps();

            $table->unique(['business_type', 'scenario_code']);
            $table->index('business_type');
            $table->index('scenario_code');
            $table->foreign('scenario_code')->references('scenario_code')->on('fbr_scenarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fbr_business_type_scenarios');
        Schema::dropIfExists('fbr_scenario_items');
        Schema::dropIfExists('fbr_scenarios');
    }
};
