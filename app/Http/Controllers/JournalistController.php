<?php

namespace App\Http\Controllers;

use App\Models\Journalist;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Contracts\View\Factory as Factory;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;

class JournalistController extends Controller
{
    protected Journalist $journalistModel;

    public function __construct(Journalist $journalistModel)
    {
        $this -> journalistModel = $journalistModel;
    }

    public function index(): View|FoundationApplication|Factory|ContractsApplication
    {
        $journalists = $this -> journalistModel -> all();
        return view("journalists.index", compact("journalists"));
    }

    public function show($journalistId): View|FoundationApplication|Factory|ContractsApplication
    {
        $journalist = $this -> journalistModel -> getJournalistById($journalistId);

        if (!$journalist) {
            abort(404);
        }

        return view("journalists.show", compact("journalist"));
    }

    public function create(): View|FoundationApplication|Factory|ContractsApplication
    {
        $roles = Role::all();
        return view("journalists.create", compact("roles"));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "email" => "required|email|unique:journalists",
            "password" => "required|min:8",
            "firstname" => "required",
            "lastname" => "required",
            "role_id" => "required|exists:roles,id",
        ]);

        $journalist = $this -> journalistModel -> addJournalist(
            $request -> input("email"),
            bcrypt($request -> input("password")),
            $request -> input("firstname"),
            $request -> input("lastname"),
            $request -> input("role_id")
        );

        return redirect() -> route("journalists.show", ["journalist" => $journalist -> id])
            -> with("success", "Journalist created successfully");
    }
}
