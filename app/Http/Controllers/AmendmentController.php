<?php

namespace App\Http\Controllers;

use App\Models\Amendment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AmendmentController extends Controller
{
    protected Amendment $amendmentModel;

    public function __construct(Amendment $amendmentModel)
    {
        $this->amendmentModel = $amendmentModel;
    }

    public function index(): View
    {
        $amendments = $this->amendmentModel->all();
        return view("amendments.index", compact("amendments"));
    }

    public function show($amendmentId): View
    {
        $amendment = $this->amendmentModel->find($amendmentId);

        if (!$amendment) {
            abort(404);
        }

        return view("amendments.show", compact("amendment"));
    }

    public function create(): View
    {
        return view("amendments.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "text" => "required",
            "article_id" => "required|exists:articles,id",
        ]);

        $amendment = $this->amendmentModel->addAmendment(
            $request->input("text"),
            $request->input("article_id")
        );

        return redirect()->route("amendments.show", ["amendment" => $amendment->id])
            ->with("success", "Amendment created successfully");
    }

    public function destroy($amendmentId): RedirectResponse
    {
        $this->amendmentModel->deleteAmendment($amendmentId);

        return redirect()->route("amendments.index")
            ->with("success", "Amendment deleted successfully");
    }
}
