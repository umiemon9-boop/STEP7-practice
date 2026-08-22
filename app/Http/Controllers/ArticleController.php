<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function showList()
    {
        $model = new Article();
        $articles = $model->getList();

        return view('list', ['articles' => $articles]);
    }

    public function showRegistForm()
    {
        return view('regist');
    }

    public function registSubmit(ArticleRequest $request)
    {
        DB::beginTransaction();

        try {
            $model = new Article();
            $model->registArticle($request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back();
        }

        return redirect(route('regist'));
    }
}
