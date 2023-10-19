<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Routing\Controller;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ArticleController extends Controller
{
    public $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function store(ArticleRequest $request)
    {
        $params = [
            "title" => $request->title,
            "content" => $request->content,
            "enabled" => $request->enabled
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('images', $fileName, 'public');
        } else {
            $fileName = "";
        }

        $result = $this->service->createArticle($params, $fileName);

        return response()->json($result->toArray());
    }

    public function show(Article $id)
    {
        return response()->json($id->toArray());
    }

    public function showMore(ArticleRequest $request)
    {
        $params = [
            "search_word" => $request->input('search_word'),
            "sort" => $request->input('sort', "created_at"),
            "order" => $request->input('order', "desc"),
            "page" => $request->input('page', 1),
            "per_page" => $request->input('per_page', 20),
        ];

        $result = $this->service->getArticles($params);

        return response()->json($result);
    }

    public function delete(Article $id)
    {
        Storage::disk('public')->delete($id->image);
        $result = $id->delete();

        if ($result) {
            return response()->json(['msg' => "Article deleted successfully."]);
        } else {
            throw new ResourceNotFoundException();
        }
    }

    public function update(ArticleRequest $request, Article $id)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('images', $fileName, 'public');

            Storage::disk('public')->delete($id->image);
        } else {
            $fileName = "";
        }

        $result = $id->update([
            "title" => $request->title,
            "content" => $request->content,
            "enabled" => $request->enabled,
            "image" => $fileName
        ]);

        if ($result) {
            return response()->json(['msg' => "Article updated successfully."]);
        } else {
            throw new ResourceNotFoundException();
        }
    }
}
