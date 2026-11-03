<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductManagementRevisionTest extends TestCase
{
    use RefreshDatabase;

    private function createCompanyAndProduct($name = 'コーラ', $price = 120, $stock = 2)
    {
        $companyId = DB::table('companies')->insertGetId([
            'company_name' => 'メーカーA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $productId = DB::table('products')->insertGetId([
            'company_id' => $companyId,
            'product_name' => $name,
            'price' => $price,
            'stock' => $stock,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $productId;
    }

    public function test_ajax_search_filters_price_and_stock_and_returns_sorted_rows()
    {
        $this->actingAs(User::factory()->create());
        $this->createCompanyAndProduct('安い商品', 100, 1);
        $this->createCompanyAndProduct('対象商品', 200, 5);
        $this->createCompanyAndProduct('後の商品', 220, 4);
        $this->createCompanyAndProduct('高い商品', 500, 8);

        $response = $this->getJson(route('products.index', [
            'price_min' => 150,
            'price_max' => 250,
            'stock_min' => 3,
            'stock_max' => 6,
            'sort' => 'price',
            'direction' => 'desc',
        ]));

        $response->assertOk();
        $rows = $response->json('rows');
        $this->assertStringContainsString('対象商品', $rows);
        $this->assertStringNotContainsString('安い商品', $rows);
        $this->assertStringNotContainsString('高い商品', $rows);
        $this->assertLessThan(strpos($rows, '対象商品'), strpos($rows, '後の商品'));
    }

    public function test_ajax_delete_removes_the_product()
    {
        $this->actingAs(User::factory()->create());
        $productId = $this->createCompanyAndProduct();

        $this->deleteJson(route('products.destroy', $productId))
            ->assertOk()
            ->assertJsonPath('message', '商品を削除しました。');

        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }

    public function test_purchase_api_records_sale_and_decrements_stock()
    {
        $productId = $this->createCompanyAndProduct('コーラ', 120, 2);

        $this->postJson('/api/purchase', ['product_id' => $productId])
            ->assertCreated()
            ->assertJsonPath('data.product_id', $productId)
            ->assertJsonPath('data.remaining_stock', 1);

        $this->assertDatabaseHas('sales', ['product_id' => $productId]);
        $this->assertDatabaseHas('products', ['id' => $productId, 'stock' => 1]);
    }

    public function test_purchase_api_rejects_an_out_of_stock_product()
    {
        $productId = $this->createCompanyAndProduct('売切商品', 120, 0);

        $this->postJson('/api/purchase', ['product_id' => $productId])
            ->assertStatus(409)
            ->assertJsonPath('message', '在庫がありません。');

        $this->assertDatabaseMissing('sales', ['product_id' => $productId]);
        $this->assertDatabaseHas('products', ['id' => $productId, 'stock' => 0]);
    }

    public function test_purchase_api_rejects_invalid_and_unknown_product_ids()
    {
        $this->postJson('/api/purchase', ['product_id' => 'not-a-number'])
            ->assertUnprocessable();

        $this->postJson('/api/purchase', ['product_id' => 9999])
            ->assertNotFound();
    }
}
