<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index() {
        return response()->json([
            'success' => true,
            "user" => auth()->user(),
            "code" => 200
        ],200);
    }
}
