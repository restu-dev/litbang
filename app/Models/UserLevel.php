<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable; // ini penting!

class UserLevel extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    // Table
    protected $table =  'litbang.user_level';

    protected $guarded = ['id'];

    // Ganti field login default (email → nip)
    public function getAuthIdentifierName()
    {
        return 'nip';
    }

    // Ganti password field default (password → user_pass)
    public function getAuthPassword()
    {
        return $this->pass_user;
    }

}
