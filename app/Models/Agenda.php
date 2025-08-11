<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    // Table
    protected $table =  'litbang.agenda';

    protected $guarded = ['id'];
}
