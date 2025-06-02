<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitLogController extends Controller
{
    public function index()
    {
        $title = 'Git Log';
        $active = 'git-log';

        return view('git_log.index', compact('title', 'active'));
    }
}
