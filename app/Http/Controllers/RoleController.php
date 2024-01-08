<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Contracts\View\Factory as Factory;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;

class RoleController extends Controller
{
    protected Role $roleModel;

    public function __construct(Role $roleModel) {
        $this -> roleModel = $roleModel;
    }

    public function index(): View|FoundationApplication|Factory|ContractsApplication
    {
        $roles = $this -> roleModel -> getAllRoles();
        return view("roles.index", compact("roles"));
    }

    public function show($roleId): View|FoundationApplication|Factory|ContractsApplication
    {
        $role = $this -> roleModel -> getRoleById($roleId);

        if (!$role) {
            abort(404);
        }

        return view("roles.show", compact("role"));
    }
}
