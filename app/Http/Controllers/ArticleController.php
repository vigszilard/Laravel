<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use App\Models\Article;
use App\Models\Category;
use App\Models\Journalist;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Contracts\View\View as ContractsView;
use Illuminate\Http\RedirectResponse;

class ArticleController extends Controller
{
    protected Article $articleModel;
    protected Category $categoryModel;

    public function __construct(Article $articleModel, Category $categoryModel)
    {
        $this->articleModel = $articleModel;
        $this->categoryModel = $categoryModel;
    }

    public function index(): ContractsView|FoundationApplication|Factory|ContractsApplication
    {
        $all_articles = (new Article)->getApprovedArticles();
        $all_categories = (new Category)->getAllCategories();
        $journalists = new Journalist();

        $articles_by_category = [];

        foreach ($all_articles as $article) {
            $category_id = $article->category_id;

            if (!isset($articles_by_category[$category_id])) {
                $articles_by_category[$category_id] = [];
            }

            $articles_by_category[$category_id][] = $article;
        }

        return view('index', compact('all_articles', 'all_categories', 'articles_by_category', 'journalists'));
    }

    public function dashboard(): ContractsView|FoundationApplication|Factory|ContractsApplication
    {
        $user = Auth::user();
        $declinedArticles = (new Article)->getDeclinedArticles();
        $writerArticles = (new Article)->getArticlesByAuthorId($user->id);
        $allCategories = (new Category)->getAllCategories();
        $amendments = new Amendment();

        return view('dashboard', compact('user', 'declinedArticles', 'writerArticles', 'allCategories', 'amendments'));
    }

    public function create(): View
    {
        $categories = $this->categoryModel->all();
        return view("articles.create", compact("categories"));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "title" => "required",
            "content" => "required",
            "author_id" => "required",
            "category_id" => "required|exists:categories,id",
        ]);

        $this->articleModel->addArticle(
            $request->input("title"),
            $request->input("content"),
            $request->input("author_id"),
            $request->input("category_id")
        );

        return redirect()->route("articles.index")->with("success", "Article created successfully");
    }

    public function showSubmissionForm(): ContractsView|FoundationApplication|Factory|ContractsApplication
    {
        // You can add logic here if needed
        return view('articles.submit');
    }

    public function submitArticle(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check if the user has the writer role
        if ($user->role_id !== 2) {
            return redirect()->route('dashboard')->with('error', 'Only writers are allowed to submit articles.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $article = new Article([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'author_id' => $user->id,
            'category_id' => $request->input('category_id'),
        ]);

        $result = $article->save();

        if ($result) {
            return redirect()->route('dashboard')->with('success', 'Article submitted successfully.');
        } else {
            return redirect()->route('dashboard')->with('error', 'Article submission failed. Please try again.');
        }
    }

    public function declineArticle(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 3) {
            return redirect()->route('dashboard')->with('error', 'Only editors are allowed to decline articles.');
        }

        $text = $request->input('text');
        $article_id = $request->input('article_id');

        $amendment = new Amendment();
        $result = $amendment->addAmendment($text, $article_id);

        if ($result) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('dashboard')->with('error', 'Failed to decline the article. Please try again.');
        }
    }

    public function updateArticle(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 2) {
            return redirect()->route('dashboard')->with('error', 'Only writers are allowed to update articles.');
        }

        $title = $request->input('title');
        $content = $request->input('content');
        $category_id = $request->input('category_id');
        $article_id = $request->input('article_id');
        $amendment_id = $request->input('amendment_id');

        $article = new Article();
        $amendment = new Amendment();

        $result = $article->updateArticle($article_id, $title, $content, $user->id, $category_id, 0);
        $result2 = $amendment->deleteAmendment($amendment_id);

        if ($result && $result2) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('dashboard')->with('error', 'Article update failed. Please try again.');
        }
    }

    public function approveArticle(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id !== 3) {
            return redirect()->route('dashboard')->with('error', 'Only editors are allowed to approve articles.');
        }

        $article_id = $request->input('article_id');

        $article = new Article();
        $old_article = $article->getArticleById($article_id);

        $result = $article->updateArticle($article_id, $old_article->title, $old_article->content,
            $old_article->author_id, $old_article->category_id, 1);

        if ($result) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('dashboard')->with('error', 'Failed to approve the article. Please try again.');
        }
    }

    public function show($id): Factory|FoundationApplication|ContractsView|RedirectResponse|ContractsApplication
    {
        $article = new Article();
        $category = new Category();
        $journalist = new Journalist();

        $articleData = $article->getArticleById($id);

        if (!$articleData) {
            return redirect()->route('index')->with('error', 'Article not found');
        }

        $journalistData = $journalist->getJournalistById($articleData->author_id);
        $categoryData = $category->getCategoryById($articleData->category_id);

        return view('article', compact('articleData', 'journalistData', 'categoryData'));
    }
}

