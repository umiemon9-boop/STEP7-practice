# 自動販売機商品管理システム

Laravel 9を使用した、自動販売機の商品管理・売上管理アプリケーションです。

## 使用技術

- PHP 8.2
- Laravel 9
- MySQL
- Laravel Breeze
- Blade
- CSS

## 主な機能

- 新規登録
- ログイン
- ログアウト
- 商品一覧表示
- 商品検索
- 商品詳細表示
- 商品登録
- 商品編集
- 商品削除
- 商品画像登録・表示
- 売上登録
- 売上一覧表示
- 商品別売上集計
- 購入時の在庫数減算
- 在庫0商品の購入制御

## セットアップ

```bash
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

## DB設定例

`.env.example` では、データベース名を `practice` にしています。

```env
DB_DATABASE=practice
DB_USERNAME=root
DB_PASSWORD=
```

MAMPなどでMySQLのパスワードが必要な場合は、`.env` の `DB_PASSWORD` を自分の環境に合わせて修正してください。`.env` はGit管理対象外です。

## 画面URL

```text
新規登録: /register
ログイン: /login
商品一覧: /products
商品登録: /products/create
売上一覧: /sales
```

商品管理・売上管理の画面はログイン必須です。
