<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>売上一覧</title>
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
</head>
<body>
    <header class="page-header">
        <h1 class="page-title">売上一覧</h1>
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

    <h2>商品別売上集計</h2>
    <table border="1">
        <thead>
            <tr>
                <th>商品ID</th>
                <th>商品名</th>
                <th>販売数</th>
                <th>売上金額</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summaries as $summary)
                <tr>
                    <td>{{ $summary->id }}</td>
                    <td>{{ $summary->product_name }}</td>
                    <td>{{ $summary->sales_count }}</td>
                    <td>{{ $summary->total_price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>売上履歴</h2>
    <table border="1">
        <thead>
            <tr>
                <th>売上ID</th>
                <th>商品名</th>
                <th>メーカー</th>
                <th>価格</th>
                <th>購入日時</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->id }}</td>
                    <td>{{ $sale->product_name }}</td>
                    <td>{{ $sale->company_name }}</td>
                    <td>{{ $sale->price }}</td>
                    <td>{{ $sale->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
