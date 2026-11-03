@forelse ($products as $product)
    <tr data-product-id="{{ $product->id }}">
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
        <td><a class="button-link button-secondary" href="{{ route('products.show', $product->id) }}">詳細</a></td>
        <td>
            <form class="delete-product-form" action="{{ route('products.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="button-danger" type="submit">削除</button>
            </form>
        </td>
    </tr>
@empty
    <tr class="empty-row"><td colspan="8">該当する商品はありません。</td></tr>
@endforelse
