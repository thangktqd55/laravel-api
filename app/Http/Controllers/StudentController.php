<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Student::get();

        return response()->json([
            "status" => "success",
            "data" => $student
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:students',
            'gender' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                "status" => 'Failed',
                "message" => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        
        Student::create($data);

        return response()->json([
            "status" => "success",
            "message" => "A new student is created"
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::findOrFail($id);

        return response()->json([
            "status" => "success",
            "message" => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);    

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:students',
            'gender' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                "status" => 'Failed',
                "message" => $validator->errors()
            ], 400);
        }

        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Student is successfully updated"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);

        $student->delete();

        return response()->json([
            "status" => "success",
            "message" => "Student is deleted successfully"
        ]);
    }
}
