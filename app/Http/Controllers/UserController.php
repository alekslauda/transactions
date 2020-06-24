<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json(
            [
                'status' => 'success',
                'data' => $users->toArray()
            ], 200);
    }

}
