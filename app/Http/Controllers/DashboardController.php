<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class DashboardController extends Controller
{
    public function index()
    {
        if(empty(auth()->user()->id)){
            return redirect()->route('welcome');
        }
        $courses = Course::all();
        $enrolled_courses = Enrollment::where('student_id', $id)->pluck('course_id')->toArray();
        return view('dashboard', 
        ['courses' => $courses],
        ['enrolled_courses' => $enrolled_courses]
        );
    }
    
    public function enroll(Request $request)
    {
        if(empty(auth()->user()->id)){
            return redirect()->route('welcome');
        }
        $enrollment = new Enrollment;
        $enrollment->student_id = auth()->user()->id;
        $enrollment->course_id = $request->course_id;
        $enrollment->save();
        $studentId = auth()->user()->student_id;
        $fees = $request->input('course_fee');
        $client = new Client();
        $response = $client->post('http://localhost:8001/api/generate_invoice/' . $studentId . '/' . $fees);
        if ($response->getStatusCode() == 201) {
            $invoice = json_decode($response->getBody());
            $invoiceId = $invoice->invoice->invoice_ref;
            return redirect()->route('dashboard')->with('success', 'Congrulation!, you had enrolled successfully. Your invoice reference number is ' . $invoiceId);
        } else {
            return response()->json(['message' => 'Error occured!, Failed to enroll course'], 400);
        }

    }

    public function graduation()
    {
        if(empty(auth()->user()->id)){
            return redirect()->route('welcome');
        }
        $student_id = auth()->user()->student_id;
        $client = new Client();
        $response = $client->post('http://localhost:8001/api/check_invoices/' . $student_id);
        if ($response->getStatusCode() == 200) {
            $invoices = json_decode($response->getBody());
            $invoices = $invoices->invoices;
            $graduation_status = 2;
            foreach($invoices as $invoice){
                if($invoice->status == 'UNPAID'){
                    $graduation_status = 1;
                    break;
                }
            }
            
            return view('graduation', ['invoices' => $invoices, 'graduation_status' => $graduation_status]);
        } else if($response->getStatusCode() == 404){
            return view('graduation', ['invoices' => [], 'graduation_status' => 2]);
        }
        else {
            return response()->json(['message' => 'Unable to get invoices'], 400);
        }
    }

    public function profile()
    {
        if(empty(auth()->user()->id)){
            return redirect()->route('welcome');
        }
        $student = auth()->user();
        return view('view_profile', ['student' => $student]);
    }

    public function updateProfile(Request $request)
    {
        if(empty(auth()->user()->id)){
            return redirect()->route('welcome');
        }
        $student = auth()->user();
        $student->name = $request->name;
        $student->surname = $request->surname;
        $student->save();
        return redirect()->route('profile')->with('success', 'Profile has been Updated successfully!.');
    }

}
