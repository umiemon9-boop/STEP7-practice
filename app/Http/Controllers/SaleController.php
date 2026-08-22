<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use RuntimeException;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::getList();
        $summaries = Sale::getSummary();

        return view('sales.index', compact('sales', 'summaries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'product_id' => ['required', 'integer'],
            ],
            [
                'product_id.required' => '商品を選択してください。',
                'product_id.integer' => '商品の指定が正しくありません。',
            ]
        );

        $product = Product::getDetail($data['product_id']);

        if (is_null($product)) {
            abort(404);
        }

        try {
            Sale::purchase($data['product_id']);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('products.show', $data['product_id'])
                ->with('error_message', $e->getMessage());
        }

        return redirect()->route('sales.index');
    }
}
