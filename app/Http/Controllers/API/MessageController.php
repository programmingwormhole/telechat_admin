<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getUsers ()
    {
        $users = User::where('id', '!=', auth()->user()->id)->get();

        return response()->json($users);
    }
}
