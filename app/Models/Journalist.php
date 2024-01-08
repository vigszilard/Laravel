<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Journalist extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    protected $table = "journalists";
    public $timestamps = false;
    protected $fillable = ["email", "password", "firstname", "lastname", "role_id"];

    public function getJournalistById($journalistId)
    {
        return $this -> find($journalistId);
    }

    public function getJournalistByEmail($email)
    {
        return $this -> where("email", $email) -> first();
    }

    public function addJournalist($email, $password, $firstname, $lastname, $role_id)
    {
        return $this -> create([
            "email" => $email,
            "password" => $password,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "role_id" => $role_id,
        ]);
    }

    public function isEmailTaken($email)
    {
        return $this -> where("email", $email) -> exists();
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }
}

