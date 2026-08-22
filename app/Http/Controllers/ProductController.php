<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function productRules()
    {
        return [
            'company_id' => ['required', 'integer'],
            'product_name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'comment' => ['nullable', 'string'],
            'image' => ['nullable', 'image'],
        ];
    }

    private function productMessages()
    {
        return [
            'company_id.required' => 'メーカーを選択してください。',
            'company_id.integer' => 'メーカーの指定が正しくありません。',
            'product_name.required' => '商品名を入力してください。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'price.required' => '価格を入力してください。',
            'price.integer' => '価格は整数で入力してください。',
            'price.min' => '価格は0以上で入力してください。',
            'stock.required' => '在庫数を入力してください。',
            'stock.integer' => '在庫数は整数で入力してください。',
            'stock.min' => '在庫数は0以上で入力してください。',
            'comment.string' => 'コメントは文字で入力してください。',
            'image.image' => '商品画像には画像ファイルを選択してください。',
        ];
    }

    public function index(Request $request)
    {
        $conditions = [
            'product_name' => $request->input('product_name'),
            'company_id' => $request->input('company_id'),
        ];
        $products = Product::getList($conditions);
        $companies = Company::getList();

        return view('products.index', compact('products', 'companies', 'conditions'));
    }

    public function create()
    {
        $companies = Company::getList();

        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->productRules(), $this->productMessages());

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $data['img_path'] = str_replace('public/', 'storage/', $path);
        }

        Product::registProduct($data);

        return redirect()->route('products.index');
    }

    public function show($id)
    {
        $product = Product::getDetail($id);

        if (is_null($product)) {
            abort(404);
        }

        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::getDetail($id);
        $companies = Company::getList();

        if (is_null($product)) {
            abort(404);
        }

        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate($this->productRules(), $this->productMessages());

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $data['img_path'] = str_replace('public/', 'storage/', $path);
        }

        Product::updateProduct($id, $data);

        return redirect()->route('products.show', $id);
    }

    public function destroy($id)
    {
        Product::deleteProduct($id);

        return redirect()->route('products.index');
    }
}
