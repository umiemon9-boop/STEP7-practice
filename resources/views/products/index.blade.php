<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

    <form class="search-form" id="search-form" action="{{ route('products.index') }}" method="GET">
        <div class="form-row">
            <label for="product_name">商品名</label>
            <input type="text" id="product_name" name="product_name" value="{{ $conditions['product_name'] ?? '' }}">
        </div>

        <div class="form-row">
            <label>価格</label>
            <input type="number" min="0" name="price_min" placeholder="下限" value="{{ $conditions['price_min'] ?? '' }}">
            <span>〜</span>
            <input type="number" min="0" name="price_max" placeholder="上限" value="{{ $conditions['price_max'] ?? '' }}">
        </div>

        <div class="form-row">
            <label>在庫数</label>
            <input type="number" min="0" name="stock_min" placeholder="下限" value="{{ $conditions['stock_min'] ?? '' }}">
            <span>〜</span>
            <input type="number" min="0" name="stock_max" placeholder="上限" value="{{ $conditions['stock_max'] ?? '' }}">
        </div>

        <input type="hidden" name="sort" id="sort-column" value="{{ $sort }}">
        <input type="hidden" name="direction" id="sort-direction" value="{{ $direction }}">

        <div class="form-row">
            <label for="company_id">メーカー</label>
            <select id="company_id" name="company_id">
                <option value="">すべて</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @selected(($conditions['company_id'] ?? '') == $company->id)>
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

    @if (session('error_message'))
        <p class="error-list">{{ session('error_message') }}</p>
    @endif

    <p id="ajax-message" role="status" aria-live="polite"></p>
    <table id="products-table" border="1">
        <thead>
            <tr>
                <th data-sort="id" aria-sort="{{ $sort === 'id' ? ($direction === 'asc' ? 'ascending' : 'descending') : 'none' }}">ID</th>
                <th>画像</th>
                <th data-sort="product_name">商品名</th>
                <th data-sort="company_name">メーカー</th>
                <th data-sort="price">価格</th>
                <th data-sort="stock">在庫数</th>
                <th>詳細</th>
                <th>削除</th>
            </tr>
        </thead>
        <tbody id="products-table-body">
            @include('products.partials.rows', ['products' => $products])
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/products.js') }}"></script>
</body>
</html>
