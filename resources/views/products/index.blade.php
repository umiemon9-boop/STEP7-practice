<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>
<body>
    <header class="page-header">
        <h1 class="page-title">商品一覧</h1>
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

    <form class="search-form" action="{{ route('products.index') }}" method="GET">
        <div class="form-row">
            <label for="product_name">商品名</label>
            <input type="text" id="product_name" name="product_name" value="{{ $conditions['product_name'] }}">
        </div>

        <div class="form-row">
            <label for="company_id">メーカー</label>
            <select id="company_id" name="company_id">
                <option value="">すべて</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @selected($conditions['company_id'] == $company->id)>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="actions">
            <button type="submit">検索</button>
            <a class="button-link button-secondary" href="{{ route('products.index') }}">クリア</a>
        </div>
    </form>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>画像</th>
                <th>商品名</th>
                <th>メーカー</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->img_path)
                            <img class="product-image" src="{{ asset($product->img_path) }}" alt="{{ $product->product_name }}">
                        @else
                            画像なし
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->company_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a class="button-link button-secondary" href="{{ route('products.show', $product->id) }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
