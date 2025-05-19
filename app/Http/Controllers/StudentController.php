<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\SchoolYear;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the active school year
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json([
                'message' => 'No active school year found.',
                'students' => []
            ], 200);
        }

        // Get students who are NOT enrolled in the active school year
        $students = Student::whereDoesntHave('enrollments', function ($query) use ($activeSchoolYear) {
            $query->where('schoolYearId', $activeSchoolYear->schoolYearId);
        })->with('enrollments')->get();

        return response()->json([
            'students' => $students
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
