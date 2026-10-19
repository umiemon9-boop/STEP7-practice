<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
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

    public function store(ProductRequest $request)
    {
        $storedPath = null;

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $storedPath = $request->file('image')->store('public/images');
                $data['img_path'] = str_replace('public/', 'storage/', $storedPath);
            }

            DB::transaction(function () use ($data) {
                Product::registProduct($data);
            });
        } catch (\Throwable $exception) {
            if ($storedPath) {
                Storage::delete($storedPath);
            }

            report($exception);

            return back()->withInput()->with('error_message', '商品の登録に失敗しました。もう一度お試しください。');
        }

        return redirect()
            ->route('products.create')
            ->with('success_message', '商品を登録しました。');
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

    public function update(ProductRequest $request, $id)
    {
        $storedPath = null;

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $storedPath = $request->file('image')->store('public/images');
                $data['img_path'] = str_replace('public/', 'storage/', $storedPath);
            }

            DB::transaction(function () use ($id, $data) {
                Product::updateProduct($id, $data);
            });
        } catch (\Throwable $exception) {
            if ($storedPath) {
                Storage::delete($storedPath);
            }

            report($exception);

            return back()->withInput()->with('error_message', '商品の更新に失敗しました。もう一度お試しください。');
        }

        return redirect()
            ->route('products.edit', $id)
            ->with('success_message', '商品を更新しました。');
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                Product::deleteProduct($id);
            });
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with('error_message', '商品の削除に失敗しました。もう一度お試しください。');
        }

        return redirect()->route('products.index');
    }
}
