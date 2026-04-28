<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zeropay_bank_deposits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->index();
            $table->foreignId('bank_account_id')->nullable()->constrained('zeropay_bank_accounts')->nullOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained('zeropay_transactions')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('AUD');
            $table->string('depositor_name')->nullable();
            $table->string('depositor_bsb')->nullable();
            $table->string('depositor_account')->nullable();
            $table->string('reference')->nullable()->index();
            $table->string('description')->nullable();
            $table->timestamp('deposited_at')->nullable();
            $table->string('status')->default('pending_review')->index();
            $table->integer('match_score')->default(0);
            $table->string('match_method')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zeropay_bank_deposits');
    }
};
