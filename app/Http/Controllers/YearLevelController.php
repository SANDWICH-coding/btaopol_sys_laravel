<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YearLevel;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;

class YearLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year.'], 404);
        }

        $yearLevelIds = \DB::table('school_year_level')
            ->where('schoolYearId', $activeSchoolYear->schoolYearId)
            ->pluck('yearLevelId');

        $yearLevels = YearLevel::with([
            'classArms' => function ($query) use ($activeSchoolYear) {
                $query->where('schoolYearId', $activeSchoolYear->schoolYearId);
            }
        ])
            ->whereIn('yearLevelId', $yearLevelIds)
            ->get();

        return response()->json($yearLevels);
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
            'yearLevelName' => 'required|string|max:50',
        ]);

        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json([
                'message' => 'No active school year, unable to create year level.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Check if year level already exists
            $level = YearLevel::firstOrCreate(
                ['yearLevelName' => $validated['yearLevelName']],
                ['yearLevelName' => $validated['yearLevelName']]
            );

            // Insert to school_year_level (avoid duplicate)
            $exists = DB::table('school_year_level')
                ->where('schoolYearId', $activeSchoolYear->schoolYearId)
                ->where('yearLevelId', $level->yearLevelId)
                ->exists();

            if (!$exists) {
                DB::table('school_year_level')->insert([
                    'schoolYearId' => $activeSchoolYear->schoolYearId,
                    'yearLevelId' => $level->yearLevelId,
                ]);
            }

            DB::commit();

            return response()->json($level, 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create year level.',
                'error' => $e->getMessage()
            ], 500);
        }
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
