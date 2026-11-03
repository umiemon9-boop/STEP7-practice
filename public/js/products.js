$(function () {
    const $form = $('#search-form');
    const $message = $('#ajax-message');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    function loadProducts() {
        $message.text('検索中...');

        $.get($form.attr('action'), $form.serialize())
            .done(function (response) {
                $('#products-table-body').html(response.rows);
                $message.text('');
            })
            .fail(function (xhr) {
                const errors = xhr.responseJSON && xhr.responseJSON.errors;
                $message.text(errors ? Object.values(errors).flat().join(' ') : '検索に失敗しました。入力内容をご確認ください。');
            });
    }

    $form.on('submit', function (event) {
        event.preventDefault();
        loadProducts();
    });

    $('#products-table thead').on('click', 'th[data-sort]', function () {
        const column = $(this).data('sort');
        const $sort = $('#sort-column');
        const $direction = $('#sort-direction');
        const nextDirection = $sort.val() === column && $direction.val() === 'asc' ? 'desc' : 'asc';

        $sort.val(column);
        $direction.val(nextDirection);
        $('#products-table th[data-sort]').attr('aria-sort', 'none');
        $(this).attr('aria-sort', nextDirection === 'asc' ? 'ascending' : 'descending');
        loadProducts();
    });

    $('#products-table-body').on('submit', '.delete-product-form', function (event) {
        event.preventDefault();
        const form = this;

        if (!window.confirm('この商品を削除します。よろしいですか？')) {
            return;
        }

        const $button = $(form).find('button[type="submit"]');
        $button.prop('disabled', true);

        $.ajax({
            url: form.action,
            type: 'DELETE',
            data: $(form).serialize()
        }).done(function (response) {
            $(form).closest('tr').remove();
            if ($('#products-table-body tr').length === 0) {
                $('#products-table-body').html('<tr class="empty-row"><td colspan="8">該当する商品はありません。</td></tr>');
            }
            $message.text(response.message);
        }).fail(function (xhr) {
            $button.prop('disabled', false);
            $message.text(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '商品の削除に失敗しました。');
        });
    });
});
