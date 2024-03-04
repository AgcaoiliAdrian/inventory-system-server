<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index(){
        try {
            
            $grade = Grade::all();

            return response()->json($grade);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'grading' => 'required|string',
        ]);
    
        try {
            $existingGrade = Grade::where('grading', $validatedData['grading'])->first();
    
            if ($existingGrade) {
                return response()->json([
                    'message' => 'Grade already exists.'
                ]);
            }
    
            $grade = Grade::create([
                'grading' => $validatedData['grading']
            ]);
    
            return response()->json([
                'message' => 'Grade successfully added.'
            ]);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ]);
        }
    }       

    public function update($id, Request $request){
        try {
            $grade = Grade::find($id);
            // $grade -> brand_id = $request -> id;
            $grade -> grading = $request -> grade;
            $grade -> updated_at = now();
            $grade -> save();

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }

    public function show($id){
        try {
            $grade = Grade::find($id);

            return response()->json($grade);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th -> getMessage()
            ]);
        }
    }   
}
