<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'gender' => 'required',
        ]);

        User::where('id', auth()->id())
            ->update($request->except('_token'));

        return redirect()->back()->with('message', 'Profile updated successfully!');
    }

    public function updateAvatar(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destination = public_path('/profile');
            $image->move($destination, $name);

            User::where('id', auth()->id())
                ->update(['image' => $name]);

            return redirect()->back()->with('message', 'Avatar updated successfully!');
        }
    }
}
