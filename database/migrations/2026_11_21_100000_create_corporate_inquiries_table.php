<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Poptávky firemních dárků ze stránky Pro firmy.
 *
 * Poptávka se uloží dřív, než odejde e-mail na info@kavi.cz, aby nezmizela,
 * když e-mail selže. status sleduje obchodní případ, stavy viz CorporateInquiry::STATUSES
 * (od 2026_11_22 new → communication → offer_sent → realization → done | lost).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corporate_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company');
            $table->string('email');
            $table->string('phone', 50)->nullable();
            $table->string('audience', 20); // klienti | zamestnanci | oboji
            $table->string('quantity', 20); // 10-24 | 25-49 | 50+
            $table->date('delivery_date')->nullable();
            $table->text('note')->nullable();
            $table->string('locale', 5)->default('cs');
            $table->json('utm')->nullable(); // UTM a click ID z URL stránky, odkud poptávka přišla
            $table->string('status', 20)->default('new');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corporate_inquiries');
    }
};
