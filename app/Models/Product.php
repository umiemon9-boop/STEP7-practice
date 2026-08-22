<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    public static function getList($conditions = [])
    {
        $query = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.company_id',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.img_path',
                'companies.company_name'
            );

        if (!empty($conditions['product_name'])) {
            $query->where('products.product_name', 'LIKE', '%' . $conditions['product_name'] . '%');
        }

        if (!empty($conditions['company_id'])) {
            $query->where('products.company_id', $conditions['company_id']);
        }

        return $query->get();
    }

    public static function registProduct($data)
    {
        return DB::table('products')->insert([
            'company_id' => $data['company_id'],
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'comment' => $data['comment'],
            'img_path' => $data['img_path'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function getDetail($id)
    {
        return DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.company_id',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'products.img_path',
                'companies.company_name'
            )
            ->where('products.id', $id)
            ->first();
    }

    public static function updateProduct($id, $data)
    {
        $values = [
            'company_id' => $data['company_id'],
            'product_name' => $data['product_name'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'comment' => $data['comment'],
            'updated_at' => now(),
        ];

        if (array_key_exists('img_path', $data)) {
            $values['img_path'] = $data['img_path'];
        }

        return DB::table('products')
            ->where('id', $id)
            ->update($values);
    }

    public static function deleteProduct($id)
    {
        return DB::table('products')
            ->where('id', $id)
            ->delete();
    }

}
