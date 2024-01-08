<?php

// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name'];

    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    public function getRoleById($roleId)
    {
        return Role::find($roleId);
    }
}
