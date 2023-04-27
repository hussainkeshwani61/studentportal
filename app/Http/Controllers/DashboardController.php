<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        if(empty(auth()->user()->id)){
            return redirect()->route('login');
        }
        $courses = Course::all();
        $enrolled_courses = Enrollment::where('student_id', auth()->user()->id)->pluck('course_id')->toArray();
        return view('dashboard', 
        ['courses' => $courses],
        ['enrolled_courses' => $enrolled_courses]
        );
    }
    
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
