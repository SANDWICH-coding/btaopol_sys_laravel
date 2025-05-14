<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ClassArm;
use App\Models\SchoolYear;
use App\Models\YearLevel;

class ClassArmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the active school year
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year found.'], 404);
        }

        // Fetch all classes for the active school year
        $classes = ClassArm::where('schoolYearId', $activeSchoolYear->schoolYearId)->get();

        return response()->json($classes);
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
        // Get the active school year
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year found.'], 404);
        }

        // Validate only the required fields
        $validator = Validator::make($request->all(), [
            'yearLevelId' => 'required|exists:year_levels,yearLevelId',
            'className' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create class using active school year ID
        $class = ClassArm::create([
            'schoolYearId' => $activeSchoolYear->schoolYearId,
            'yearLevelId' => $request->yearLevelId,
            'className' => $request->className,
        ]);

        return response()->json([
            'message' => 'Class created successfully',
            'data' => $class,
        ], 201);
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
        // Find the class arm to update
        $classArm = ClassArm::find($id);

        if (!$classArm) {
            return response()->json(['message' => 'Class not found.'], 404);
        }

        // Validate incoming data
        $validator = Validator::make($request->all(), [
            'yearLevelId' => 'required|exists:year_levels,yearLevelId',
            'className' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update fields
        $classArm->yearLevelId = $request->yearLevelId;
        $classArm->className = $request->className;
        $classArm->save();

        return response()->json([
            'message' => 'Class updated successfully',
            'data' => $classArm,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = ClassArm::find($id);

        if (!$class) {
            return response()->json(['message' => 'Class not found.'], 404);
        }

        $class->delete();

        return response()->json(['message' => 'Class deleted successfully.']);
    }
}
