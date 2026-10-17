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
        // Prodaný dárek: dárkové předplatné (N boxů) nebo poukaz na e-shop.
        // Řádek vzniká při vytvoření objednávky (status pending) a nese všechna data
        // z formuláře. Kód a kupon se vygenerují až po zaplacení objednávky.
        Schema::create('gift_purchases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_item_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->unique()->constrained()->nullOnDelete();
            // Kopie kódu kuponu pro hledání v adminu
            $table->string('code', 20)->nullable()->unique();

            // subscription | voucher
            $table->string('type', 20);
            // pending | expired | issuing | issued | redeemed | cancelled
            $table->string('status', 20)->default('pending')->index();

            // Dárkové předplatné: velikost boxu (počet balíčků 2/3/4) a počet měsíců
            $table->unsignedTinyInteger('bags')->nullable();
            $table->unsignedTinyInteger('months')->nullable();
            // Cena řádku; u poukazu jeho hodnota
            $table->decimal('amount', 10, 2);
            $table->char('currency', 3);
            $table->decimal('vat_rate', 5, 2);
            $table->string('locale', 5)->default('cs');

            // Certifikát
            $table->string('recipient_name', 60);
            $table->string('giver_name', 60);
            $table->string('note', 255)->nullable();
            // buyer = PDF přijde kupujícímu, recipient = pošleme obdarovanému v send_at
            $table->string('delivery', 20)->default('buyer');
            $table->string('recipient_email')->nullable();
            $table->dateTime('send_at')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable()->index();

            // Vystavení a doručení
            $table->dateTime('valid_until')->nullable();
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('issuing_started_at')->nullable();
            $table->string('pdf_path')->nullable();
            $table->dateTime('buyer_email_sent_at')->nullable();
            $table->dateTime('recipient_email_sent_at')->nullable();
            $table->unsignedTinyInteger('delivery_attempts')->default(0);
            $table->text('last_delivery_error')->nullable();

            // Uplatnění
            $table->char('start_month', 7)->nullable();
            $table->dateTime('redeemed_at')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable()->unique();
            $table->foreignId('redeemed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('ending_reminder_sent_at')->nullable();

            $table->timestamps();

            $table->index(['delivery', 'recipient_email_sent_at', 'send_at']);
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_purchases');
    }
};
