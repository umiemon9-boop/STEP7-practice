<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class Sale extends Model
{
    use HasFactory;

    public static function getList()
    {
        return DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'sales.id',
                'sales.created_at',
                'products.product_name',
                'products.price',
                'companies.company_name'
            )
            ->orderBy('sales.id', 'desc')
            ->get();
    }

    public static function purchase($product_id)
    {
        DB::beginTransaction();

        try {
            $product = DB::table('products')
                ->where('id', $product_id)
                ->lockForUpdate()
                ->first();

            if (is_null($product)) {
                throw new RuntimeException('商品が見つかりません。');
            }

            if ($product->stock <= 0) {
                throw new RuntimeException('在庫がありません。');
            }

            DB::table('sales')->insert([
                'product_id' => $product_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('products')
                ->where('id', $product_id)
                ->decrement('stock');

            DB::commit();
        } catch (RuntimeException $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public static function getSummary()
    {
        return DB::table('sales')
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.product_name',
                DB::raw('COUNT(sales.id) AS sales_count'),
                DB::raw('SUM(products.price) AS total_price')
            )
            ->groupBy('products.id', 'products.product_name')
            ->orderBy('products.id')
            ->get();
    }
}
