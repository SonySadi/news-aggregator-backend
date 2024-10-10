<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NewsArticleController extends Controller
{
    public function index(Request $request)
    {
        // validation
        $request->validate([
            'search' => 'nullable|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'source' => 'nullable|string',
            'author' => 'nullable|string',
        ]);



        $query = NewsArticle::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%')
                ->orWhere('content', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('startDate')) {
            $query->where('published_at', '>=', $request->input('startDate'));
        }

        if ($request->has('endDate')) {
            $query->where('published_at', '<=', $request->input('endDate'));
        }

        if ($request->has('source')) {
            $query->where('source_name', $request->input('source'));
        } else {
            $user = Auth::guard('sanctum')->user();
            if ($user && !empty($user->preferred_sources)) {
                $query->whereIn('source_name', $user->preferred_sources);
            }

            if ($user && !empty($user->preferred_authors)) {
                $query->whereIn('author', $user->preferred_authors);
            }
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(10);

        return response()->json($articles);
    }

    public function show($id)
    {
        $article = NewsArticle::findOrFail($id);
        return response()->json($article);
    }

    public function getSources()
    {
        $sources = NewsArticle::select('source_name')
            ->distinct()
            ->orderBy('source_name')
            ->pluck('source_name');

        return response()->json($sources);
    }

    public function getAuthors()
    {
        $authors = NewsArticle::select('author')
            ->distinct()
            ->orderBy('author')
            ->pluck('author');

        return response()->json($authors);
    }
}
