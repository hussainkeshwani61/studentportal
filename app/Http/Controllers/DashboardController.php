<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function profile(){
        if(empty(auth()->user()->id)){
            return redirect()->route('login');
        }
        $student = auth()->user();
        return view('view_profile', ['student' => $student]);
    }

    public function updateProfile(Request $request){
        if(empty(auth()->user()->id)){
            return redirect()->route('login');
        }
        $student = auth()->user();
        $student->name = $request->name;
        $student->surname = $request->surname;
        $student->save();
        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
