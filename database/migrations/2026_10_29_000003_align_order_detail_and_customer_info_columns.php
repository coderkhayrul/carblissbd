<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $money = ['purchase_price', 'sale_price', 'mrp_price', 'total_cost', 'total_amount'];
        $missing = array_filter($money, fn ($column) => !Schema::hasColumn('order_details', $column));
        $missingReturned = !Schema::hasColumn('order_details', 'returned_qty');
        $missingReturnFlag = !Schema::hasColumn('order_details', 'exist_in_return');
        Schema::table('order_details', function (Blueprint $table) use ($missing, $missingReturned, $missingReturnFlag) {
            foreach ($missing as $column) {
                $table->decimal($column, in_array($column, ['total_cost', 'total_amount']) ? 12 : 10, 2)->default(0);
            }
            if ($missingReturned) $table->unsignedInteger('returned_qty')->default(0);
            if ($missingReturnFlag) $table->boolean('exist_in_return')->default(false);
        });
        if (!Schema::hasColumn('order_customer_infos', 'order_note')) {
            Schema::table('order_customer_infos', fn (Blueprint $table) => $table->text('order_note')->nullable());
        }

        if (in_array('sale_price', $missing) && Schema::hasColumn('order_details', 'unit_price')) {
            DB::table('order_details')->update(['sale_price' => DB::raw('unit_price')]);
        }
        if (in_array('total_amount', $missing) && Schema::hasColumn('order_details', 'line_total')) {
            DB::table('order_details')->update(['total_amount' => DB::raw('line_total')]);
        }
        // Historical purchase price and MRP were not stored; retain zero instead of inventing a past price.
        DB::table('order_customer_infos')->join('orders', 'orders.id', '=', 'order_customer_infos.order_id')
            ->whereNull('order_customer_infos.order_note')->whereNotNull('orders.notes')
            ->select('order_customer_infos.id', 'orders.notes')->orderBy('order_customer_infos.id')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) DB::table('order_customer_infos')->where('id', $row->id)->update(['order_note' => $row->notes]);
            }, 'order_customer_infos.id', 'id');
    }

    public function down(): void
    {
        // These columns now belong to the original create migrations; preserve them and their data on rollback.
    }
};
