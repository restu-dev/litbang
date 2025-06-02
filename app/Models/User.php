<?php

namespace App\Models;

use App\Models\Jenjang;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Table
    protected $table =  'simpia.users';

    protected $guarded = ['id'];

    public function getAuthPassword()
    {
        return $this->password2;
    }
}
