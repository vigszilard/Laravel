<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;

class CategoryController extends Controller
{
    protected Category $categoryModel;

    public function __construct(Category $categoryModel)
    {
        $this -> categoryModel = $categoryModel;
    }

    public function index(): View|FoundationApplication|Factory|ContractsApplication
    {
        $categories = $this->categoryModel->getAllCategories();
        return view("categories.index", compact("categories"));
    }

    public function show($categoryId): View|FoundationApplication|Factory|ContractsApplication
    {
        $category = $this->categoryModel->getCategoryById($categoryId);

        if (!$category) {
            abort(404);
        }

        return view("categories.show", compact("category"));
    }

    public function create(): View|FoundationApplication|Factory|ContractsApplication
    {
        return view("categories.create");
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "name" => "required|unique:categories",
        ]);

        $this->categoryModel->addCategory($request->input("name"));

        return redirect()->route("categories.index")->with("success", "Category created successfully");
    }
}

