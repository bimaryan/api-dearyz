<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function store()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $user->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'message' => 'You have successfully logged out.'
            ], 200);
        }

        return response()->json([
            'error' => 'Not authenticated.'
        ], 401);
    }
}
