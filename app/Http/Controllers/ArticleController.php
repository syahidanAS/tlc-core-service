<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategoryModel;
use App\Models\ArticleModel;
use App\Models\User;
use App\Models\UserArticleModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PDO;

class ArticleController extends Controller
{
    public function store(Request $request)
    {
        if ($request->file('image_uri')) {
            $file = $request->file('image_uri');
            $filename = date('YmdHi.') . $file->extension();
            $file->move(public_path('article/images'), $filename);
        } else {
            return response()->json([
                'message' => 'image uri is required',
            ], 400);
        }

        $article = new ArticleModel;
        $article->title = $request->title;
        $article->body = $request->body;
        $article->slug = Str::slug($request->title);
        $article->image_uri = $filename;
        $article->image_url = url('/article/images/' . $filename);
        $article->published_at = $request->published_at;
        $article->save();

        $articleCategoryPayload = [
            "article_id" => $article->id,
            "category_id" => $request->category_id
        ];
        $extractedToken = explode(' ', $request->header('Authorization'))[1];
        $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
        $response = User::where('id', $decoded->uid)->first();

        $userArticle = [
            "user_id" => $response->id,
            "article_id" => $article->id
        ];

        $resultCategory = ArticleCategoryModel::create($articleCategoryPayload);
        $resultUser = UserArticleModel::create($userArticle);

        if ($resultCategory && $resultUser) {
            return response()->json([
                'message' => 'article successfully created',
            ], 201);
        }
        return response()->json([
            'message' => 'something went wrong',
        ], 500);
    }

    public function get()
    {
        $result = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
            ->join('articles', 'article_category.article_id', '=', 'articles.id')
            ->join('categories', 'article_category.category_id', 'categories.id')
            ->join('user_article', 'articles.id', '=', 'user_article.article_id')
            ->join('users', 'user_article.user_id', '=', 'users.id')
            ->get();
        $count = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
            ->join('articles', 'article_category.article_id', '=', 'articles.id')
            ->join('categories', 'article_category.category_id', 'categories.id')
            ->join('user_article', 'articles.id', '=', 'user_article.article_id')
            ->join('users', 'user_article.user_id', '=', 'users.id')
            ->count();

        if ($result) {
            return response()->json([
                'message' => $count . ' Data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong'
        ], 500);
    }

    //No auth required
    public function getPublished(Request $request)
    {
        $result = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.published_at, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
            ->join('articles', 'article_category.article_id', '=', 'articles.id')
            ->join('categories', 'article_category.category_id', 'categories.id')
            ->join('user_article', 'articles.id', '=', 'user_article.article_id')
            ->join('users', 'user_article.user_id', '=', 'users.id')
            ->whereNotNull('articles.published_at')
            ->get();
        $count = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.published_at, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
            ->join('articles', 'article_category.article_id', '=', 'articles.id')
            ->join('categories', 'article_category.category_id', 'categories.id')
            ->join('user_article', 'articles.id', '=', 'user_article.article_id')
            ->join('users', 'user_article.user_id', '=', 'users.id')
            ->whereNotNull('articles.published_at')
            ->count();

        if ($result) {
            return response()->json([
                'message' => $count . ' Data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong'
        ], 500);
    }

    public function getPublishedByCategory($category_id){
        $result = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.published_at, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
        ->join('articles', 'article_category.article_id', '=', 'articles.id')
        ->join('categories', 'article_category.category_id', 'categories.id')
        ->join('user_article', 'articles.id', '=', 'user_article.article_id')
        ->join('users', 'user_article.user_id', '=', 'users.id')
        ->where('categories.id', $category_id)
        ->whereNotNull('articles.published_at')
        ->get();
    $count = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.published_at, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
        ->join('articles', 'article_category.article_id', '=', 'articles.id')
        ->join('categories', 'article_category.category_id', 'categories.id')
        ->join('user_article', 'articles.id', '=', 'user_article.article_id')
        ->join('users', 'user_article.user_id', '=', 'users.id')
        ->where('categories.id', $category_id)
        ->whereNotNull('articles.published_at')
        ->count();

        if ($result) {
            return response()->json([
                'message' => $count . ' Data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong'
        ], 500);
    }

    public function getBySlug($slug)
    {
        $result = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
            ->join('articles', 'article_category.article_id', '=', 'articles.id')
            ->join('categories', 'article_category.category_id', 'categories.id')
            ->join('user_article', 'articles.id', '=', 'user_article.article_id')
            ->join('users', 'user_article.user_id', '=', 'users.id')
            ->where('articles.slug', $slug)
            ->get();
        $count = ArticleCategoryModel::selectRaw('articles.title, articles.body, articles.slug, articles.image_uri,articles.image_url, articles.published_at, users.name AS writer, categories.category')
            ->join('articles', 'article_category.article_id', '=', 'articles.id')
            ->join('categories', 'article_category.category_id', 'categories.id')
            ->join('user_article', 'articles.id', '=', 'user_article.article_id')
            ->join('users', 'user_article.user_id', '=', 'users.id')
            ->where('articles.slug', $slug)
            ->count();

        if ($count == 0) {
            return response()->json([
                'message' => 'Article not found'
            ], 404);
        } else {
            return response()->json([
                'message' => $count . ' Data successfully retrieved',
                'data' => $result
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong'
        ], 500);
    }

    public function destroy($id)
    {
        if (!$id) {
            return response()->json([
                'message' => 'id must be filled',
            ], 400);
        }

        $findUri = ArticleModel::where('articles.id', $id)->first();

        $result = ArticleModel::destroy($id);
        $imagePath = public_path('article/images/' . $findUri->image_uri);
        File::delete($imagePath);

        if ($result) {
            return response()->json([
                'message' => 'data successfully deleted',
            ], 200);
        }
        return response()->json([
            'message' => 'something went wrong',
        ], 500);
    }

    public function update(Request $request)
    {
        if (!$request->id) {
            return response()->json([
                'message' => 'id must be filled',
            ], 400);
        }
        // If image is change
        if ($request->file('image_uri')) {
            $file = $request->file('image_uri');
            $filename = date('YmdHi.') . $file->extension();
            $file->move(public_path('article/images'), $filename);

            $payload = [
                "title" => $request->title,
                "body" => $request->body,
                "slug" => Str::slug($request->title),
                "image_uri" => $filename,
                "image_url" => url('/article/images/' . $filename),
            ];
            $findUri = ArticleModel::where('articles.id', $request->id)->first();
            $imagePath = public_path('article/images/' . $findUri->image_uri);
            File::delete($imagePath);

            //If image is not change
        } else {
            $payload = [
                "title" => $request->title,
                "body" => $request->body,
                "slug" => Str::slug($request->title),
            ];
        }

        $result = ArticleModel::where('id', $request->id)->update($payload);

        if ($result) {
            return response()->json([
                'message' => 'Article successfully updated',
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 500);



        // $extractedToken = explode(' ', $request->header('Authorization'))[1];
        // $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));

        // $uid = User::where('id', $decoded->uid)->first();

    }
}
