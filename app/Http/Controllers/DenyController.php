<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Users;

class DenyController extends Controller
{
    //
    public function index()
    {

        return view("administrator::deny");
    }
}
