<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        $image_path = $user->image_path ? asset('storage/img/avatar/'.$user->image_path) : asset('storage/img/avatar/default_avatar.jpg');

        return view('users.show', compact('user', 'image_path'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function avatar(Request $request)
    {
        $user = Auth::user();

        $path = $request->file('image')->store('public/img/avatar');
        $user->image_path = basename($path);
        $user->save();
    
        return redirect()->route('user');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function avatar_delete(Request $request)
    {
        $user = Auth::user();

        Storage::delete('public/img/avatar'.$user->image_path);
        $user->image_path = null;
        $user->save();
        return redirect()->route('user');
    }
}
