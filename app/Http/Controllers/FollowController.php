<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(Request $request, User $user){
        $user->follows()->attach(Auth::id());

        return redirect()->back();
    }

    public function destroy(User $user){
        $user->follows()->detach(Auth::id());

        return redirect()->back();
    }
}
