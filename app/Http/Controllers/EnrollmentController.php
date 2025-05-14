<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Enrollment;
use App\Models\Student;

$enrollmentNumber = strtoupper(Str::random(12));

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'lrn' => 'nullable|unique:students,lrn',
            'firstName' => 'required|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'profilePhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'yearLevelId' => 'required|exists:year_levels,yearLevelId',
            'classArmId' => 'required|exists:class_arms,classArmId',
            'enrollmentType' => 'required|in:new,transferee,old/continuing',
        ]);

        DB::beginTransaction();

        try {
            // ✅ Get active school year
            $activeSchoolYear = \App\Models\SchoolYear::where('status', 1)->first();

            if (!$activeSchoolYear) {
                return response()->json([
                    'message' => 'No active school year found.'
                ], 422);
            }

            // Handle file upload
            if ($request->hasFile('profilePhoto')) {
                $file = $request->file('profilePhoto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profile_photos', $filename);
                $profilePhotoPath = 'storage/profile_photos/' . $filename;
            } else {
                $profilePhotoPath = null;
            }

            // Create student
            $student = Student::create([
                'lrn' => $validated['lrn'] ?? null,
                'firstName' => $validated['firstName'],
                'middleName' => $validated['middleName'] ?? null,
                'lastName' => $validated['lastName'],
                'suffix' => $validated['suffix'] ?? null,
                'profilePhoto' => $profilePhotoPath,
            ]);

            // Generate 12-char alphanumeric enrollment number
            $enrollmentNumber = strtoupper(Str::random(12));

            // Create enrollment
            $enrollment = Enrollment::create([
                'enrollmentNumber' => $enrollmentNumber,
                'schoolYearId' => $activeSchoolYear->schoolYearId,
                'yearLevelId' => $validated['yearLevelId'],
                'classArmId' => $validated['classArmId'],
                'studentId' => $student->studentId,
                'enrollmentType' => $validated['enrollmentType'],
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Student enrolled successfully.',
                'student' => $student,
                'enrollment' => $enrollment,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Enrollment failed.',
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
