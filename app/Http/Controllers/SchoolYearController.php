<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolYear;


class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all school years from the database
        $schoolYears = SchoolYear::all();

        // Return the data as a JSON response
        return response()->json($schoolYears);
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
        $validated = $request->validate([
            'yearStart' => 'required|string',
            'yearEnd' => 'required|string',
        ]);

        $schoolYear = SchoolYear::create([
            'yearStart' => strtoupper($validated['yearStart']),
            'yearEnd' => strtoupper($validated['yearEnd']),
        ]);

        return response()->json($schoolYear, 201);
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
        $schoolYear = SchoolYear::findOrFail($id);

        if ($request->has('status')) {
            // Ensure only one school year is active
            if ($request->status == 1) {
                // Deactivate all other school years
                SchoolYear::where('status', 1)->update(['status' => 0]);
            }

            $schoolYear->status = $request->status;
            $schoolYear->save();

            return response()->json(['message' => 'School year status updated.']);
        }

        // Handle other updates if necessary...
        return response()->json(['message' => 'No update performed.'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
