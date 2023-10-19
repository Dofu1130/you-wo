<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ArticleService
{
    public function createArticle($params, $image): Model
    {
        $result =  Article::create([
            "title" => $params['title'],
            "content" => $params['content'],
            "enabled" => $params['enabled'],
            "image" => $image
        ]);

        return $result;
    }

    public function getArticles($params): Collection
    {
        if ($params['search_word']) {
            $query = Article::where(function ($q) use ($params) {
                $q->orWhere('title', 'like', "%" . $params['search_word'] . "%")
                    ->orWhere('content', 'like', "%" . $params['search_word'] . "%");
            });
        } else {
            $query = Article::query();
        };

        $result = $query->active()
            ->orderBy($params['sort'], $params['order'])
            ->forPage($params['page'], $params['per_page'])
            ->get();

        return $result;
    }
}
