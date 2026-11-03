<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => '入力内容を確認してください。',
                'errors' => $validator->errors(),
            ], 422);
        }

        $productId = (int) $validator->validated()['product_id'];

        if (is_null(Product::getDetail($productId))) {
            return response()->json(['message' => '商品が見つかりません。'], 404);
        }

        try {
            $purchase = Sale::purchase($productId);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }

        return response()->json([
            'message' => '購入しました。',
            'data' => $purchase,
        ], 201);
    }
}
