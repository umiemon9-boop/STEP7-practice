<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>
<body>
    <header class="page-header">
        <h1 class="page-title">商品登録</h1>
        <nav class="nav-links">
            <a href="{{ route('products.index') }}">商品一覧</a>
            <a href="{{ route('products.create') }}">商品登録</a>
            <a href="{{ route('sales.index') }}">売上一覧</a>
            <form class="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="button-secondary" type="submit">ログアウト</button>
            </form>
        </nav>
    </header>

    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form class="product-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <label for="company_id">メーカー</label>
            <select id="company_id" name="company_id">
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            @error('company_id')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="product_name">商品名</label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}">
            @error('product_name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="price">価格</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}">
            @error('price')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="stock">在庫数</label>
            <input type="number" id="stock" name="stock" value="{{ old('stock') }}">
            @error('stock')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="comment">コメント</label>
            <textarea id="comment" name="comment">{{ old('comment') }}</textarea>
            @error('comment')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row">
            <label for="image">商品画像</label>
            <input type="file" id="image" name="image">
            @error('image')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="actions">
            <button type="submit">登録</button>
            <a class="button-link button-secondary" href="{{ route('products.index') }}">一覧へ戻る</a>
        </div>
    </form>
</body>
</html>
