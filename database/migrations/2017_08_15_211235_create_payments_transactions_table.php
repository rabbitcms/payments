<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreatePaymentsTransactionsTable
 */
class CreatePaymentsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('driver');
            $table->string('client');
            $table->unsignedTinyInteger('status');
            $table->unsignedTinyInteger('type');
            $table->unsignedInteger('card_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string("order_type")->charset('latin1');
            $table->unsignedBigInteger("order_id");
            $table->index(["order_type", "order_id"]);
            $table->decimal('amount', 16, 8);
            $table->string('invoice')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on($table->getTable())
                ->onDelete('set null');

            $table->foreign('card_id')
                ->references('id')
                ->on('payments_cards')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments_transactions');
    }
}
