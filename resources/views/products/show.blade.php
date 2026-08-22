<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>
<body>
    <header class="page-header">
        <h1 class="page-title">商品詳細</h1>
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

    @if (session('error_message'))
        <p class="error-list">{{ session('error_message') }}</p>
    @endif

    <table border="1">
        <tr>
            <th>ID</th>
            <td>{{ $product->id }}</td>
        </tr>
        <tr>
            <th>商品名</th>
            <td>{{ $product->product_name }}</td>
        </tr>
        <tr>
            <th>メーカー</th>
            <td>{{ $product->company_name }}</td>
        </tr>
        <tr>
            <th>価格</th>
            <td>{{ $product->price }}</td>
        </tr>
        <tr>
            <th>在庫数</th>
            <td>{{ $product->stock }}</td>
        </tr>
        <tr>
            <th>コメント</th>
            <td>{{ $product->comment }}</td>
        </tr>
        <tr>
            <th>商品画像</th>
            <td>
                @if ($product->img_path)
                    <img class="product-image-large" src="{{ asset($product->img_path) }}" alt="{{ $product->product_name }}">
                @else
                    画像なし
                @endif
            </td>
        </tr>
    </table>

    <div class="actions">
        @if ($product->stock > 0)
            <form action="{{ route('sales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit">この商品を購入</button>
            </form>
        @else
            <span>在庫なし</span>
        @endif

        <a class="button-link button-secondary" href="{{ route('products.edit', $product->id) }}">編集</a>

        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="button-danger" type="submit">削除</button>
        </form>

        <a class="button-link button-secondary" href="{{ route('products.index') }}">一覧へ戻る</a>
    </div>
</body>
</html>
